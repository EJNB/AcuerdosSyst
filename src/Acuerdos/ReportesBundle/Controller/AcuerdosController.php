<?php

namespace Acuerdos\ReportesBundle\Controller;

use Acuerdos\GestionBundle\Entity\StrAcuerdoPersona;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Time;

class AcuerdosController extends Controller
{
    public function indexAcuerdosAction()
    {

        return $this->render('AcuerdosGestionBundle:Acuerdo:index.html.twig', array(

        ));
    }

    public function seleccionProcedenciasAction()
    {

        $em = $this->getDoctrine()->getManager();
        $procedencias = $em->getRepository('AcuerdosGestionBundle:Procedencia')->findCantAcuerdosProcedencia();
        $personas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAllOrdenados();

        return $this->render('AcuerdosReportesBundle:Acuerdos:seleccionProcedencias.html.twig', array(
            'procedencias' => $procedencias,
            'personas' => $personas,
        ));
    }

    public function acuerdosProcedenciaAction($idprocedencia, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('prefixedsessionservice')->setPrefix('reporteacuerdo');

        $filter_text = $request->request->get('text-find');
        $filter_state = $request->request->get('select-state');

        if($request->request->get('ind-date-start-find')!=null){
            $filter_start_indic = date_create(date($request->request->get('ind-date-start-find')));}
        else{$filter_start_indic = "";}

        if($request->request->get('ind-date-end-find')!=null){
            $filter_end_indic = date_create(date($request->request->get('ind-date-end-find')));}
        else{$filter_end_indic = "";}

        if($request->request->get('real-date-start-find')!=null){
            $filter_start_real = date_create(date($request->request->get('real-date-start-find')));}
        else{$filter_start_real = "";}

        if($request->request->get('real-date-end-find')!=null){
            $filter_end_real = date_create(date($request->request->get('real-date-end-find')));}
        else{$filter_end_real = "";}

        $procedencia_select = $em->getRepository('AcuerdosGestionBundle:Procedencia')->findOneById($idprocedencia);
        $procedencias = $em->getRepository('AcuerdosGestionBundle:Procedencia')->findAll();

        if($procedencia_select){

            $success = $this->get('orderbyservice')->OrderBy($request->query->get('column'), array('fechaCumplimientoIndicada',
                'id', 'descripcion', 'fechaCumplimientoReal'));
            $count =  $em->getRepository("AcuerdosGestionBundle:Acuerdo")->countAcuerdosCompletosReporte($idprocedencia,
                $filter_text, $filter_state, $filter_start_indic, $filter_end_indic, $filter_start_real, $filter_end_real);

            if (!$success) {
                throw $this->createNotFoundException("Algunos de los parámetros proporcionados son incorrectos o están fuera de rango.");
            }


            $entities = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->findAcuerdosCompletosReporte(
                $idprocedencia, $filter_text, $filter_state, $filter_start_indic, $filter_end_indic, $filter_start_real,
                $filter_end_real,$this->get('orderbyservice')->getColumn(),$this->get('orderbyservice')->getOrder());
        }
        return $this->render('AcuerdosReportesBundle:Acuerdos:acuerdosProcedencia.html.twig', array(
            'entities' => $entities,
            'procedencia_select' => $procedencia_select,
            'procedencias' => $procedencias,
            'count' => $count,
            'column' => $this->get('orderbyservice')->getColumn(),
            'order' => $this->get('orderbyservice')->getOrder(),
        ));
    }


    public function acuerdosMsgAction(Request $request){

        $textmsg = $request->request->get('text-msg');
        $ids = $request->request->get('select-acuerdo');
        $idprocedencia = intval($request->request->get('text-idprocedencia'));


        $em = $this->getDoctrine()->getManager();
        $listacuerdos = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->findById($ids);
        $listapersona = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findByIdAcuerdo($ids);

        foreach($listacuerdos as $acuerdo){
            $message = \Swift_Message::newInstance()
                ->setSubject('Cumplimiento de acuerdos')
                ->setFrom('acuerdos@cubanacan.tur.cu')
                ->setBody(
                    $this->renderView(
                        'AcuerdosReportesBundle:Msg:msg.txt.twig',
                        array(
                            'acuerdo' => $acuerdo,
                            'textmsg' => $textmsg,
                        )
                    )
                )
            ;

            foreach($listapersona as $persona){
                if($persona->getIdAcuerdo()->getId()== $acuerdo->getId()){
                    if($persona->getTipo()=="Responsable"){
                        $message->setTo($persona->getIdPersona()->getEmail());

                    }
                    if($persona->getTipo()=="Ejecutor"){
                        $message->setCc($persona->getIdPersona()->getEmail());

                    }
                }

            }

            $this->get('mailer')->send($message);

        }
        return $this->redirect($this->generateUrl('acuerdos_procedencia', array(
            'idprocedencia' => $idprocedencia,
        )));


    }


    public function acuerdosPdfAction(Request $request){

        $ids = $request->request->get('select-acuerdo');
        $idprocedencia = intval($request->request->get('text-idprocedencia'));

        $em = $this->getDoctrine()->getManager();
        $procedencia = $em->getRepository('AcuerdosGestionBundle:Procedencia')->find($idprocedencia);
        $listacuerdos = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->findById($ids);
        $listapersona = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findByIdAcuerdo($ids);

        $entities = new ArrayCollection();
        foreach($listacuerdos as $acuerdo){
            $acuerdopersonas = new StrAcuerdoPersona();
            $acuerdopersonas->setAcuerdo($acuerdo);

            $personas = new ArrayCollection();
            foreach($listapersona as $persona){
                if($persona->getIdAcuerdo()->getId()== $acuerdo->getId()){
                    $personas->add($persona);
                }
            }
            $acuerdopersonas->setPersonas($personas);
            $entities->add($acuerdopersonas);

        }

        $fecha = date('d:m:Y-H:i:s');

        $pdfGenerator = $this->get('siphoc.pdf.generator');
        $pdfGenerator->setName('acuerdos-'.$procedencia->getPrefijo().'-'.$fecha.'.pdf');

        return $pdfGenerator->downloadFromView(
            'AcuerdosReportesBundle:Acuerdos:acuerdosProcedenciaPdf.html.twig', array(
                'entities' => $entities,
                'procedencia' => $procedencia,
            )
        );
    }

    public function acuerdosPersonaAction(Request $request){

        $id = $request->request->get('select-pers');
        $rol = $request->request->get('select-rol');

        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('AcuerdosGestionBundle:Persona')->find($id);
        $acuerdospersona = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findPersonaTipo($id,$rol);

        return $this->render('AcuerdosReportesBundle:Acuerdos:acuerdosPersona.html.twig', array(
            'acuerdos' => $acuerdospersona,
            'persona' => $persona,
        ));


    }
}
