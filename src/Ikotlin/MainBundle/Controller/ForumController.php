<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\Answer;
use Ikotlin\MainBundle\Entity\CommentVote;
use Ikotlin\MainBundle\Entity\ForumQuestion;
use Ikotlin\MainBundle\Entity\ForumView;
use Ikotlin\MainBundle\Entity\ForumVote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class ForumController extends Controller {
    
    
    
    /*     * *************************************** 
      FORUM POSTS
     * *************************************** */

    /**
     * @Rest\Get("/forums/getAllQuestions")
     */
    public function getAllQuestionsAction(Request $request) {
        $id = $request->get("id");
        $starts_at = $request->get("start");
        if (empty($starts_at)) {
            $starts_at = 0;
            $length = 15;
        } else
            $length = 10;
        if (empty($id)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                //do the work
                /** check for the search key / orderby */
                $key = $request->get("keysearch");
                $orderby = $request->get("orderby");
                $forums = $em->getRepository("IkotlinMainBundle:ForumQuestion")->getForumsBy($starts_at, $length, $key, $orderby);

                return new View(array("forum" => $forums), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forums/getSingleQuestion")
     */
    public function getSingleQuestionAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->getForumOptimized($questionId);
            $selfVote = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(
                    array("idForum" => $questionId, "idUser" => $id));
            if (empty($selfVote))
                $selfVote = 2;
            else if ($selfVote[0]->isVote() == false)
                $selfVote = -1;
            else
                $selfVote = 1;
            if (!empty($forum)) {
                return new View(array("forum" => $forum, "selfvote" => $selfVote), Response::HTTP_OK);
            } else
                return new View(array("Error" => "No forum to process..."), Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Post("/forums/addQuestion")
     */
    public function addQuestionAction(Request $request) {

        $request = json_decode($request->getContent(), true);
        $id = $request['id'];

        if (empty($id)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                $valid = true;
                //do the work
                $forum = new ForumQuestion();
                if (!empty($request["subject"]))
                    $forum->setSubject($request["subject"]);
                else
                    $valid = false;
                if (!empty($request["content"]))
                    $forum->setContent($request["content"]);
                else
                    $valid = false;
                if (!empty($request["tags"]))
                    $forum->setTags($request["tags"]);
                if (!empty($request["code"]))
                    $forum->setCode($request["code"]);

                if ($valid) {
                    //add
                    $forum->setIdUser($u);
                    $em->persist($forum);
                    $em->flush();
                    return new View(array("resp" => "OK"), Response::HTTP_OK);
                }
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/forums/editQuestion")
     */
    public function editQuestionAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            if (!empty($u) && !empty($forum) && $forum->getIdUser() == $u) {
                $valid = true;
                if (!empty($request->get("subject")))
                    $forum->setSubject($request->get("subject"));
                else
                    $valid = false;
                if (!empty($request->get("content")))
                    $forum->setContent($request->get("content"));
                else
                    $valid = false;
                if (!empty($request->get("tags")))
                    $forum->setTags(($request->get("tags")));
                $forum->setCode(($request->get("code")));
                $forum->setEdited(new \DateTime());
                if ($valid) {
                    $em->persist($forum);
                    $em->flush();
                    return new View(array("resp" => "OK"), Response::HTTP_OK);
                }
            }
        }
        return new View(array("Error" => "Wrong .."), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forums/deleteQuestion")
     */
    public function deleteQuestion(Request $request) {

        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            if (!empty($u) && !empty($forum) && $forum->getIdUser() == $u) {
                $em->remove($forum);
                $em->flush();
                return new View(array("resp" => "OK"), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong data.."), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forums/markQuestionAsSeen")
     */
    public function markQuestionAsSeenAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            $v = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumView")->findBy(array("idForum" => $forum, "idUser" => $u));
            if (empty($v)) {
                $v = new ForumView();
                $v->setIdForum($forum);
                $v->setIdUser($u);
                $em->persist($v);
                $em->flush();
                $count = count($em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumView")->findBy(array("idForum" => $forum)));
                $forum->setViews($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp" => $count), Response::HTTP_OK);
            } else
                return new View(array("resp" => "no"), Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Get("/forums/questionUpvotes")
     */
    public function questionUpVoteAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            $up = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum, "idUser" => $u,
                "vote" => 1));
            $down = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum, "idUser" => $u,
                "vote" => 0));
            if (empty($up)) {
                $up = new ForumVote();
                $up->setIdUser($u);
                $up->setIdForum($forum);
                $up->setVote(true);
                $em->persist($up);
                if (!empty($down)) {
                    $em->remove($down[0]);
                }
                $em->flush();
                $cup = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum,
                    "vote" => 1));
                $cdown = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum,
                    "vote" => 0));
                $countUp = count($cup);
                $countDown = count($cdown);
                $count = $countUp - $countDown;
                $forum->setRating($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp" => $count), Response::HTTP_OK);
            } else {
                $em->remove($up[0]);
                $forum->setRating($forum->getRating() - 1);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp" => "no"), Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forums/questionDownvotes")
     */
    public function questionDownVoteAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            $up = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum, "idUser" => $u,
                "vote" => 1));
            $down = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum, "idUser" => $u,
                "vote" => 0));
            if (empty($down)) {
                $down = new ForumVote();
                $down->setIdUser($u);
                $down->setIdForum($forum);
                $down->setVote(false);
                $em->persist($down);
                if (!empty($up)) {
                    $em->remove($up[0]);
                }
                $em->flush();
                $cup = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum,
                    "vote" => 1));
                $cdown = $em->getRepository("Ikotlin\\MainBundle\\Entity\\ForumVote")->findBy(array("idForum" => $forum,
                    "vote" => 0));
                $countUp = count($cup);
                $countDown = count($cdown);
                $count = $countUp - $countDown;
                $forum->setRating($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp" => $count), Response::HTTP_OK);
            } else {
                $em->remove($down[0]);
                $forum->setRating($forum->getRating() + 1);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp" => "no"), Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forums/getCurrentUserQuestions")
     */
    public function getCurrentUserQuestionsAction(Request $request) {
        $id = $request->get("id");
        $starts_at = $request->get("start");
        if (empty($starts_at)) {
            $starts_at = 0;
            $length = 15;
        } else
            $length = 10;
        if (empty($id)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                //do the work

                $forums = $em->getRepository("IkotlinMainBundle:ForumQuestion")->getmyForumsOptimized($starts_at, $length, $id);

                return new View(array("forum" => $forums), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }

    /*     * *************************************** 
      COMMENTS
     * *************************************** */

    /**
     * @Rest\Get("/forums/getCommments")
     */
    public function getForumCommentsAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        $starts_at = $request->get("start");
        if (empty($starts_at)) {
            $starts_at = 0;
            $length = 10;
        } else
            $length = 8;
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            if (!empty($u) && !empty($forum)) {
                //do the work
                $comments = $em->getRepository("IkotlinMainBundle:Answer")->getCommentsByForum($starts_at, $length, $questionId);
                return new View(array("comments" => $comments), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/forums/addComment")
     */
    public function addCommentQuestionAction(Request $request) {
        $id = $request->get("id");
        $questionId = $request->get("questionId");
        if (empty($id) || empty($questionId)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum = $em->getRepository("IkotlinMainBundle:ForumQuestion")->find($questionId);
            if (!empty($u) && !empty($forum)) {
                $a = new Answer();
                $a->setIdUser($u);
                $a->setIdForum($forum);
                $a->setContent($request->get('commentcontent'));
                $em->persist($a);
                $em->flush();
                return new View(array("resp" => "OK"), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forums/getCommentUpvotes")
     */
    public function getcommentUpvotesAction(Request $request) {
        $id = $request->get("id");
        $commentid = $request->get("commentid");
        if (empty($id) || empty($commentid)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment = $em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            $up = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment, "idUser" => $u,
                "vote" => 1));
            $down = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment, "idUser" => $u,
                "vote" => 0));
            if (empty($up)) {
                $up = new CommentVote();
                $up->setIdUser($u);
                $up->setIdComment($comment);
                $up->setVote(true);
                $em->persist($up);
                if (!empty($down)) {
                    $em->remove($down[0]);
                }
                $em->flush();
                $cup = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment,
                    "vote" => 1));
                $cdown = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment,
                    "vote" => 0));
                $countUp = count($cup);
                $countDown = count($cdown);
                $count = $countUp - $countDown;
                $comment->setRating($count);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp" => $count), Response::HTTP_OK);
            } else {
                $em->remove($up[0]);
                $comment->setRating($comment->getRating() - 1);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp" => $comment->getRating()), Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forums/getCommentDownvotes")
     */
    public function getCommentDownvotesAction(Request $request) {
        $id = $request->get("id");
        $commentid = $request->get("commentid");
        if (empty($id) || empty($commentid)) {
            return new View(array("Error" => "Missing data to process.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment = $em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            $up = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment, "idUser" => $u,
                "vote" => 1));
            $down = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment, "idUser" => $u,
                "vote" => 0));
            if (empty($down)) {
                $down = new CommentVote();
                $down->setIdUser($u);
                $down->setIdComment($comment);
                $down->setVote(false);
                $em->persist($down);
                if (!empty($up)) {
                    $em->remove($up[0]);
                }
                $em->flush();
                $cup = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment,
                    "vote" => 1));
                $cdown = $em->getRepository("Ikotlin\\MainBundle\\Entity\\CommentVote")->findBy(array("idComment" => $comment,
                    "vote" => 0));
                $countUp = count($cup);
                $countDown = count($cdown);
                $count = $countUp - $countDown;
                $comment->setRating($count);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp" => $count), Response::HTTP_OK);
            } else {
                $em->remove($down[0]);
                $comment->setRating($comment->getRating() + 1);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp" => $comment->getRating()), Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("forums/deleteComment")
     */
    public function deleteComment(Request $request) {

        $id = $request->get("id");
        $commentid = $request->get("commentid");
        if (empty($id) || empty($commentid)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment = $em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            if (!empty($u) && !empty($comment) && $comment->getIdUser() == $u) {
                $em->remove($comment);
                $em->flush();
                return new View(array("resp" => "OK"), Response::HTTP_OK);
            }
        }
        return new View(array("Error" => "Wrong data.."), Response::HTTP_OK);
    }

}
