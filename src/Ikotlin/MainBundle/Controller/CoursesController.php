<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class CoursesController extends Controller
{
    /**
     * @Rest\Get("/courses/getAllCourses")
     */
    public function getAllUserCourses(Request $request){
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
                return new View(array("courses"=>$courses),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Wrong authentification.."),Response::HTTP_OK);

    }
    
    /**
     * @Rest\Post("/courses/addCourse")
     */
    public function addCourseAction(Request $request){

        $id=$request->get("id");
        $courseindic=$request->get("courseindic");

        if(empty($id))
        {
            return new View(array("Error"=>"This user doesnt exist in database !"),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $course= new Course();
                    $course->setUserid($u);
                    $course->setCourseindic($courseindic);
                    $course->setEarnedbadge("0");
                     $course->setFinishedchapter("0");
                    $em->persist($course);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Either user or course id is wrong !"),Response::HTTP_OK);
    }
    
     /**
     * @Rest\Get("/courses/courseStarted")
     */
    public function isHasCourseAction(Request $request){

        $id=$request->get("id");
        $courseindic=$request->get("courseindic");

       if(empty($id))
        {
            return new View(array("Error"=>"Authentification.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                    $courses=$em->getRepository("IkotlinMainBundle:Course")->isHasCourse($id,$courseindic);
                return new View(array("courses"=>$courses),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Either user or course id is wrong !"),Response::HTTP_OK);
    }
    
     /**
     * @Rest\Post("/courses/addCourseBadge")
     */
    public function addBadgeActionToCourse(Request $request){

        $id=$request->get("id");
        $courseindic=$request->get("courseindic");
        $badgeindic=$request->get("badgeindic");

        if(empty($id))
        {
            return new View(array("Error"=>"This user doesnt exist in database !"),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            $course =$em->getRepository("IkotlinMainBundle:Course")->isHasCourse($id,$courseindic);
            if(!empty($u)) {
                    $course->setEarnedbadge($badgeindic);
                    $em->persist($course);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Either user or course id is wrong !"),Response::HTTP_OK);
    }
    
    


}
