<?php

namespace Acuerdos\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcuerdosGestionBundle:Default:index.html.twig', array());
    }
}
