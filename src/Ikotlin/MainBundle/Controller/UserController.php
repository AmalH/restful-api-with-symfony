<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ParameterBag;


class UserController extends Controller
{
    /**
     * @Rest\Get("/user/authentification")
     */
    public function logUserInAction(Request $request){
        $id=$request->get("id");

        if(empty($id))
        {
            return new View(array("Error"=>"Empty data.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $u->setLastlogged(new \DateTime());
                $em->persist($u);
                $em->flush();
                return new View(array("user"=>$u),Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/user/register")
     */
    public function registerUserAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $email=$request["email"];
        $username=$request["username"];

        if(empty($email) || empty($id) || empty($username))
        {
            return new View(array("Error"=>"Empty data (not created).."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $usersByEmail=$em->getRepository("IkotlinMainBundle:User")->findBy(array("email"=>$email));

            if(!empty($usersByEmail))
                return new View(array("Error"=>"User already exists"),Response::HTTP_OK);

            $u=new User();
            $u->setId($id);
            $u->setEmail($email);
            $u->setUsername($username);


            $em->persist($u);
            $em->flush();

            if(!empty($u)) return new View(array("user"=>$u),Response::HTTP_ACCEPTED);
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);

    }

    /**
     * @Rest\Post("/user/setprofilepicture")
     */
    public function setprofilepictureurlAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $profile_picture=$request['profile_picture'];

        if(empty($id)||empty($profile_picture))
        {
            return new View(array("Error"=>"Empty data.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $u->setPicture($profile_picture);
                $em->persist($u);
                $em->flush();
                return new View(array("user"=>$u),Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/user/setbadges")
     */
    public function setBadgesAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $badges=$request['badges'];

        if(empty($id)||empty($badges))
        {
            return new View(array("Error"=>"Empty data.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $u->setBadges($badges);
                $em->persist($u);
                $em->flush();
                return new View(array("user"=>$u),Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/user/getbadges")
     */
    public function getBadgesAction(Request $request){
        $id=$request->get("id");

        if(empty($id))
        {
            return new View(array("Error"=>"Empty data.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                return new View(array("badges"=>$u->getBadges()),Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);
    }
    
        /**
     * @Rest\Post("/user/setusername")
     */
    public function setUsernameAction(Request $request){
        $request=json_decode($request->getContent(),true);
        $id=$request['id'];
        $username=$request['username'];

        if(empty($id)||empty($username))
        {
            return new View(array("Error"=>"Empty data.."),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $u->setUsername($username);
                $em->persist($u);
                $em->flush();
                return new View(array("user"=>$u),Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error"=>"Wrong entries .."),Response::HTTP_OK);
    }


}
