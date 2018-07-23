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
use Ikotlin\MainBundle\Entity\Badge;

class UserController extends Controller {

    /**
     * @Rest\Post("/users/register")
     */
    public function registerUserAction(Request $request) {
       // $request = json_decode($request->getContent(), true);
        $id=$request->get("id");
        $email = $request->get("email");
        $username = $request->get("username");
        $pictureUrl = $request->get("pictureUrl");

        if (empty($email) || empty($id) || empty($username)) {
            return new View(array("Error" => "Empty data (not created).."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $usersByEmail = $em->getRepository("IkotlinMainBundle:User")->findBy(array("email" => $email));

            if (!empty($usersByEmail))
                return new View(array("Error" => "User already exists"), Response::HTTP_OK);

            $u = new User();
            $u->setId($id);
            $u->setEmail($email);
            $u->setUsername($username);
            $u->setPicture($pictureUrl);


            $em->persist($u);
            $em->flush();

            if (!empty($u))
                return new View(array("user" => $u), Response::HTTP_ACCEPTED);
        }
        return new View(array("Error" => "Wrong entries .."), Response::HTTP_OK);
    }

    
    /**
     * @Rest\Get("/users/getBadges")
     */
    /*public function getAllBadgesAction(Request $request) {
        $id = $request->get("id");

        if (empty($id)) {
            return new View(array("Error" => "Please provide id !"), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                $badges=$em->getRepository("IkotlinMainBundle:Badge")->getUserBadges($id);
                return new View(array("badges" =>$badges), Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error" => "Wrong entries .."), Response::HTTP_OK);
    }*/
    
     /**
     * @Rest\Post("/users/addBadge")
     */
    public function addBadgeAction(Request $request){

        $id=$request->get("id");
        $badgeindic=$request->get("badgeindic");

        if(empty($id))
        {
            return new View(array("Error"=>"This user doesnt exist in database !"),Response::HTTP_OK);
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $u= $em->getRepository("IkotlinMainBundle:User")->find($id);
            if(!empty($u)) {
                $badge= new Badge();
                    $badge->setUserid($u);
                    $badge->setBadgeindic($badgeindic);
                    $em->persist($badge);
                    $em->flush();
                    return new View(array("resp"=>"OK"),Response::HTTP_OK);
            }
        }
        return new View(array("Error"=>"Either user or course id is wrong !"),Response::HTTP_OK);
    }
    
     /**
     * @Rest\Get("/users/hasBadge")
     */
    public function isHasBadgeAction(Request $request) {
        $id = $request->get("id");
        $badgeindic = $request->get("badgeindic");

        if (empty($id)) {
            return new View(array("Error" => "Please provide id !"), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                $badges=$em->getRepository("IkotlinMainBundle:Badge")->isUserHasBadge($id,$badgeindic);
                return new View(array("badges" =>$badges), Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error" => "Wrong entries .."), Response::HTTP_OK);
    }
    
    /**
     * @Rest\Get("/users/getUser")
     */
    public function getUserAction(Request $request) {
        $id = $request->get("id");
        if (empty($id)) {
            return new View(array("Error" => "Authentification.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                return $u;
              //  return new View(array("users" => $u), Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error" => "Wrong authentification.."), Response::HTTP_OK);
    }
    
    /**
     * @Rest\Post("/users/setProfilePicture")
     */
    public function setProfilePictureUrlAction(Request $request) {
        $request = json_decode($request->getContent(), true);
        $id = $request['id'];
        $profile_picture = $request['profile_picture'];

        if (empty($id) || empty($profile_picture)) {
            return new View(array("Error" => "Empty data.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                $u->setPicture($profile_picture);
                $em->persist($u);
                $em->flush();
                return new View(array("user" => $u), Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error" => "Wrong entries .."), Response::HTTP_OK);
    }
    
  
    /**
     * @Rest\Post("/users/setUsername")
     */
    public function setUsernameAction(Request $request) {
        $request = json_decode($request->getContent(), true);
        $id = $request['id'];
        $username = $request['username'];

        if (empty($id) || empty($username)) {
            return new View(array("Error" => "Empty data.."), Response::HTTP_OK);
        } else {
            $em = $this->getDoctrine()->getManager();
            $u = $em->getRepository("IkotlinMainBundle:User")->find($id);
            if (!empty($u)) {
                $u->setUsername($username);
                $em->persist($u);
                $em->flush();
                return new View(array("user" => $u), Response::HTTP_ACCEPTED);
            }
        }
        return new View(array("Error" => "Wrong entries .."), Response::HTTP_OK);
    }

}
