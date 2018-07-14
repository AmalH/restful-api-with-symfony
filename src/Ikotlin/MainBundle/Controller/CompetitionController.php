<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\Competition;
use Ikotlin\MainBundle\Entity\Competition_Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class CompetitionController extends Controller
{

    /**
     * @Rest\Post("/competition/addcompetition")
     */
    public function addCompetitionAction(Request $request){

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
                $competition=new Competition();

                if(!empty($request["title"])) $competition->setTitle($request["title"]); else $valid=false;
                if(!empty($request["content"])) $competition->setContent($request["content"]); else $valid=false;
                if(!empty($request["level"])) $competition->setLevel($request["level"]); else $valid=false;

                if($valid){
                    //add
                    $competition->setIdUser($u);
                    $em->persist($competition);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
                }
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }


    /**
     * @Rest\Post("/competition/addanswer")
     */
    public function addAnswerCompetitionAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $competition=$request["competitionid"];
        if(empty($id)|| empty($competition))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $competition=$em->getRepository("IkotlinMainBundle:Competition")->find($competition);

            if(!empty($u) && !empty($competition)) {
                $a=new Competition_Answer();
                $a->setIdUser($u);
                $a->setIdCompetition($competition);
                $a->setContent($request['content']);
                $competition->setSolved($competition->getSolved()+1);
                $em->persist($competition);
                $em->persist($a);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/competition/getcompetitions")
     */
    public function competitionListAction(Request $request){
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
                $level=$request->get("level");
                $order=$request->get("order");
                $competitions=$em->getRepository("IkotlinMainBundle:Competition")->getCompetitions($starts_at,$length,$level,$order);

                return new View(array("competitions"=>$competitions),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/competition/getanswers")
     */
    public function answersListAction(Request $request){
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
                $level=$request->get("level");
                $competitions=$em->getRepository("IkotlinMainBundle:Competition_Answer")->getCompetitionAnswers($starts_at,$length,$level,$u);

                return new View(array("competitions"=>$competitions),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/competition/delete")
     */
    public function deleteCompetitionAction(Request $request){

        $id=$request->get("id");
        $competition=$request->get("competitionid");
        if(empty($id) || empty($competition))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $competition=$em->getRepository("IkotlinMainBundle:Competition")->find($competition);
            if(!empty($u) && !empty($competition) && $competition->getIdUser()==$u) {
                $em->remove($competition);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong data.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/competition/deleteanswer")
     */
    public function deleteCompetitionAnswer(Request $request){

        $id=$request->get("id");
        $answer=$request->get("answerid");
        if(empty($id) || empty($answer))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $answer=$em->getRepository("IkotlinMainBundle:Competition_Answer")->find($answer);
            if(!empty($u) && !empty($answer) && $answer->getIdUser()==$u) {
                $em->remove($answer);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong data.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/competition/getanswer")
     */
    public function getAnswerAction(Request $request){
        $id=$request->get("id");
        $answer=$request->get("idanswer");
        if(empty($id) || empty($answer))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                //do the work
                $answer=$em->getRepository("IkotlinMainBundle:Competition_Answer")->getCompetitionAnswerOptimized($answer);

                return new View(array("resp"=>$answer),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/competition/getcompetition")
     */
    public function getCompetitionAction(Request $request){
        $id=$request->get("id");
        $competition=$request->get("idcompetition");
        if(empty($id) || empty($competition))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                //do the work
                $competition=$em->getRepository("IkotlinMainBundle:Competition")->getCompetitionOptimized($competition);

                return new View(array("resp"=>$competition),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }
    
    
        /**
     * @Rest\Post("/competition/editanswer")
     */
    public function editAnswerCompetitionAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $competition=$request["competitionid"];
        $answerid=$request["answerid"];
        if(empty($id)|| empty($competition) || empty($answerid))
        {
            return new View(array("Error"=>"Missing data to process.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $answer=$em->getRepository("IkotlinMainBundle:Competition_Answer")->find($answerid);
            if(!empty($u) && !empty($competition)) {
                $a=$answer;
                $a->setContent($request['content']);
                $a->setCreated(new \DateTime());
                $em->persist($a);
                $em->flush();
                return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);
    }

}
