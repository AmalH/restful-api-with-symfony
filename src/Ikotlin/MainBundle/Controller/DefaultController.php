<?php

namespace Ikotlin\MainBundle\Controller;

use Ikotlin\MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ParameterBag;


class DefaultController extends FOSRestController
{
    public function indexAction()
    {
        return $this->render('IkotlinMainBundle:Default:index.html.twig');
    }


    private function getTokenForUser(User $user)
    {
        $userName = "";
        $password = "huligan_kola";

       /* $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'drle_torca']);

        return $token;*/
    }

}
