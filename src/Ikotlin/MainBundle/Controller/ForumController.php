<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\Answer;
use Ikotlin\MainBundle\Entity\comment_vote;
use Ikotlin\MainBundle\Entity\Forum_question;
use Ikotlin\MainBundle\Entity\forum_view;
use Ikotlin\MainBundle\Entity\forum_vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class ForumController extends Controller
{
    /**
     * @Rest\Get("/forum/getforums")
     */
    public function forumInitialListAction(Request $request){
        $id=$request->get("id");
        $starts_at=$request->get("start");
        if(empty($starts_at)) {$starts_at=0; $length=15;}
        else $length=10;
        if(empty($id))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
               //do the work
                /** check for the search key / orderby */
                    $key=$request->get("keysearch");
                    $orderby=$request->get("orderby");
                    $forums=$em->getRepository("IkotlinMainBundle:Forum_question")->getForumsBy($starts_at,$length,$key,$orderby);

                return new View(array("forum"=>$forums),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);

    }

    /**
     * @Rest\Post("/forum/addforum")
     */
    public function addForumAction(Request $request){

        $request=json_decode($request->getContent(),true);
        $id=$request['id'];

        if(empty($id))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $valid=true;
                //do the work
                $forum= new Forum_question();
                if(!empty($request["subject"])) $forum->setSubject($request["subject"]); else $valid=false;
                if(!empty($request["content"])) $forum->setContent($request["content"]); else $valid=false;
                if(!empty($request["tags"])) $forum->setTags($request["tags"]);
                if(!empty($request["code"])) $forum->setCode($request["code"]);

                if($valid){
                    //add
                    $forum->setIdUser($u);
                    $em->persist($forum);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
                }
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forum/getforumcommments")
     */
    public function getForumCommentsAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        $starts_at=$request->get("start");
        if(empty($starts_at)) {$starts_at=0; $length=10;}
        else $length=8;
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            if(!empty($u) && !empty($forum)) {
                //do the work
                $comments=$em->getRepository("IkotlinMainBundle:Answer")->getCommentsByForum($starts_at,$length,$forumid);
                return new View(array("comments"=>$comments),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);

    }

    /**
     * @Rest\Post("/forum/addcomment")
     */
    public function addCommentToForumAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            if(!empty($u) && !empty($forum)) {
                $a=new Answer();
                $a->setIdUser($u);
                $a->setIdForum($forum);
                $a->setContent($request->get('commentcontent'));
                $em->persist($a);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/forum/markview")
     */
    public function markForumViewAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            $v=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_view")->findBy(array("idForum"=>$forum,"idUser"=>$u));
            if(empty($v)) {
                $v=new forum_view();
                $v->setIdForum($forum);
                $v->setIdUser($u);
                $em->persist($v);
                $em->flush();
                $count=count($em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_view")->findBy(array("idForum"=>$forum)));
                $forum->setViews($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp"=>$count),Response::HTTP_OK);
            }
            else
                return new View(array("resp"=>"no"),Response::HTTP_OK);
        }
    }

    /**
     * @Rest\Get("/forum/forum/upvote")
     */
    public function forumUpVoteAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            $up=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,"idUser"=>$u,
                "vote"=>1));
            $down=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,"idUser"=>$u,
                "vote"=>0));
            if(empty($up)) {
                $up=new forum_vote();
                $up->setIdUser($u);
                $up->setIdForum($forum);
                $up->setVote(true);
                $em->persist($up);
                if(!empty($down)) {
                    $em->remove($down[0]);
                }
                $em->flush();
                $cup=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,
                    "vote"=>1));
                $cdown=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,
                    "vote"=>0));
                $countUp=count($cup);
                $countDown=count($cdown);
                $count=$countUp-$countDown;
                $forum->setRating($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp"=>$count),Response::HTTP_OK);
            }
            else {
                $em->remove($up[0]);
                $forum->setRating($forum->getRating()-1);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp"=>"no"),Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forum/forum/downvote")
     */
    public function forumDownVoteAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            $up=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,"idUser"=>$u,
                "vote"=>1));
            $down=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,"idUser"=>$u,
                "vote"=>0));
            if(empty($down)) {
                $down=new forum_vote();
                $down->setIdUser($u);
                $down->setIdForum($forum);
                $down->setVote(false);
                $em->persist($down);
                if(!empty($up)) {
                    $em->remove($up[0]);
                }
                $em->flush();
                $cup=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,
                    "vote"=>1));
                $cdown=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(array("idForum"=>$forum,
                    "vote"=>0));
                $countUp=count($cup);
                $countDown=count($cdown);
                $count=$countUp-$countDown;
                $forum->setRating($count);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp"=>$count),Response::HTTP_OK);
            }
            else {
                $em->remove($down[0]);
                $forum->setRating($forum->getRating()+1);
                $em->persist($forum);
                $em->flush();
                return new View(array("resp"=>"no"),Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forum/comment/upvote")
     */
    public function commentUpVoteAction(Request $request){
        $id=$request->get("id");
        $commentid=$request->get("commentid");
        if(empty($id)|| empty($commentid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment=$em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            $up=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,"idUser"=>$u,
                "vote"=>1));
            $down=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,"idUser"=>$u,
                "vote"=>0));
            if(empty($up)) {
                $up=new comment_vote();
                $up->setIdUser($u);
                $up->setIdComment($comment);
                $up->setVote(true);
                $em->persist($up);
                if(!empty($down)) {
                    $em->remove($down[0]);
                }
                $em->flush();
                $cup=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,
                    "vote"=>1));
                $cdown=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,
                    "vote"=>0));
                $countUp=count($cup);
                $countDown=count($cdown);
                $count=$countUp-$countDown;
                $comment->setRating($count);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp"=>$count),Response::HTTP_OK);
            }
            else {
                $em->remove($up[0]);
                $comment->setRating($comment->getRating()-1);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp"=>$comment->getRating()),Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forum/comment/downvote")
     */
    public function commentDownVoteAction(Request $request){
        $id=$request->get("id");
        $commentid=$request->get("commentid");
        if(empty($id)|| empty($commentid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment=$em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            $up=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,"idUser"=>$u,
                "vote"=>1));
            $down=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,"idUser"=>$u,
                "vote"=>0));
            if(empty($down)) {
                $down=new comment_vote();
                $down->setIdUser($u);
                $down->setIdComment($comment);
                $down->setVote(false);
                $em->persist($down);
                if(!empty($up)) {
                    $em->remove($up[0]);
                }
                $em->flush();
                $cup=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,
                    "vote"=>1));
                $cdown=$em->getRepository("Ikotlin\\MainBundle\\Entity\\comment_vote")->findBy(array("idComment"=>$comment,
                    "vote"=>0));
                $countUp=count($cup);
                $countDown=count($cdown);
                $count=$countUp-$countDown;
                $comment->setRating($count);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp"=>$count),Response::HTTP_OK);
            }
            else {
                $em->remove($down[0]);
                $comment->setRating($comment->getRating()+1);
                $em->persist($comment);
                $em->flush();
                return new View(array("resp"=>$comment->getRating()),Response::HTTP_OK);
            }
        }
    }

    /**
     * @Rest\Get("/forum/getsignleforum")
     */
    public function getForumAction(Request $request){
        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id)|| empty($forumid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->getForumOptimized($forumid);
            $selfVote=$em->getRepository("Ikotlin\\MainBundle\\Entity\\forum_vote")->findBy(
                array("idForum"=>$forumid,"idUser"=>$id));
            if(empty($selfVote)) $selfVote=2;
            else if($selfVote[0]->isVote() == false) $selfVote=-1;
            else $selfVote=1;
            if(!empty($forum)) {
                return new View(array("forum"=>$forum,"selfvote"=>$selfVote),Response::HTTP_OK);
            }
            else
                return new View(array("Error"=>"No forum to process..."),Response::HTTP_OK);
        }
    }



    /**
     * @Rest\Post("/forum/edit")
     */
    public function editForumAction(Request $request){

        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $forumid=$request['idforum'];
        if(empty($id) || empty($forumid))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            if(!empty($u) && !empty($forum) && $forum->getIdUser()==$u) {
                $valid=true;
                //do the work
                if(!empty($request["subject"])) $forum->setSubject($request["subject"]); else $valid=false;
                if(!empty($request["content"])) $forum->setContent($request["content"]); else $valid=false;
                if(!empty($request["tags"])) $forum->setTags($request["tags"]);
                $forum->setCode($request["code"]);
                $forum->setEdited(new \DateTime());
                if($valid){
                    //add
                    $em->persist($forum);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
                }
            }
        }
        return new View(array("Error"=>"Wrong .."),Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/forum/delete")
     */
    public function deleteForum(Request $request){

        $id=$request->get("id");
        $forumid=$request->get("forumid");
        if(empty($id) || empty($forumid))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $forum=$em->getRepository("IkotlinMainBundle:Forum_question")->find($forumid);
            if(!empty($u) && !empty($forum) && $forum->getIdUser()==$u) {
                $em->remove($forum);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong data.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/comment/delete")
     */
    public function deleteComment(Request $request){

        $id=$request->get("id");
        $commentid=$request->get("commentid");
        if(empty($id) || empty($commentid))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $comment=$em->getRepository("IkotlinMainBundle:Answer")->find($commentid);
            if(!empty($u) && !empty($comment) && $comment->getIdUser()==$u) {
                $em->remove($comment);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong data.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/forum/getmine")
     */
    public function forumGetUsersAction(Request $request){
        $id=$request->get("id");
        $starts_at=$request->get("start");
        if(empty($starts_at)) {$starts_at=0; $length=15;}
        else $length=10;
        if(empty($id))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                //do the work

                $forums=$em->getRepository("IkotlinMainBundle:Forum_question")->getmyForumsOptimized($starts_at,$length,$id);

                return new View(array("forum"=>$forums),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);

    }

}
