<?php

namespace Acuerdos\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcuerdosSeguridadBundle:Default:index.html.twig', array('name' => $name));
    }
}
