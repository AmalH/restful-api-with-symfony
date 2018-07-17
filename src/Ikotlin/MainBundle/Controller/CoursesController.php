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

class CoursesController extends Controller
{
    /**
     * @Rest\Get("/courses/getAllCourses")
     */
    public function getUserCourses(Request $request){
        $id=$request->get("id");
        if(empty($id))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                    $courses=$em->getRepository("IkotlinMainBundle:Course")->getUserCourses($id);
                return new View(array("forum"=>$courses),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);

    }


}
