<?php

namespace Ikotlin\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;


class MainController extends FOSRestController
{
    public function indexAction()
    {
        return $this->render('IkotlinMainBundle:Default:main.html.twig');
    }
}
