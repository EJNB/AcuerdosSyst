<?php

namespace Acuerdos\ReportesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Cumplimiento de acuerdos')
            ->setFrom('acuerdos@cubanacan.tur.cu')
            ->setTo('informatico1@cubanacan.tur.cu')
            ->setBody("Hola"
//                $this->renderView(
//                    'HelloBundle:Hello:email.txt.twig',
//                    array('name' => $name)
//                )
            )
        ;
        $this->get('mailer')->send($message);
        return $this->render('AcuerdosReportesBundle:Default:index.html.twig');
    }
}
