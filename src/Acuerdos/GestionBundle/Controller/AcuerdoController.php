<?php

namespace Acuerdos\GestionBundle\Controller;

use Acuerdos\GestionBundle\Entity\AcuerdoPersona;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acuerdos\GestionBundle\Entity\Acuerdo;
use Acuerdos\GestionBundle\Form\AcuerdoType;

/**
 * Acuerdo controller.
 *
 */
class AcuerdoController extends Controller
{

    /**
     * Lists all Acuerdo entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('prefixedsessionservice')->setPrefix('acuerdo');

        $success = $this->get('orderbyservice')->OrderBy($request->query->get('column'), array('fechaCumplimientoIndicada', 'id', 'estado' ));
        $success = $success && $this->get('filterservice')->Filter($request->request->get('filter'));
        $count =  $em->getRepository("AcuerdosGestionBundle:Acuerdo")->countForAdminList($this->get('filterservice')->getText());
        $success = $success && $this->get('pagerservice')->Paging($request->query->get('page'), $count);

        if (!$success) {
            throw $this->createNotFoundException("Algunos de los parámetros proporcionados son incorrectos o están fuera de rango.");
        }

        $entities = $em->getRepository("AcuerdosGestionBundle:Acuerdo")->findAcuerdosCompletos($this->get('orderbyservice')->getColumn(),
            $this->get('orderbyservice')->getOrder(),
            $this->get('filterservice')->getText(),
            $this->get('pagerservice')->getFirstResult(),
            $this->get('pagerservice')->getMaxResult());

        return $this->render("AcuerdosGestionBundle:Acuerdo:index.html.twig", array(
            'entities' => $entities,
            'count' => $count,
            'column' => $this->get('orderbyservice')->getColumn(),
            'order' => $this->get('orderbyservice')->getOrder(),
            'filter' => $this->get('filterservice')->getText(),
            'page' => $this->get('pagerservice')->getPage(),
            'page_count' => $this->get('pagerservice')->getPageCount(),
        ));

//        $entities = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->findAcuerdosCompletos();
//
//        return $this->render('AcuerdosGestionBundle:Acuerdo:index.html.twig', array(
//            'entities' => $entities,
//        ));
    }
    /**
     * Creates a new Acuerdo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Acuerdo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAllOrdenados();

        if ($form->isValid()) {
            $anno = date_format($entity->getFechaCreacion(),'y');
            $entity->setIdAcuerdo($entity->getIdProcedencia()->getPrefijo()."-".$entity->getIdAcuerdo()."-".$anno);
            $listapersonas = new \Doctrine\Common\Collections\ArrayCollection();

            $autor = new AcuerdoPersona();
            $autor->setIdAcuerdo($entity);
            $autor->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($request->request->get('select-autor'))));
            $autor->setTipo("Autor");
            $listapersonas->add($autor);
            if($request->request->get('select-responsables-asignados')) {
                foreach ($request->request->get('select-responsables-asignados') as $indice => $valor) {
                    $responsable = new AcuerdoPersona();
                    $responsable->setIdAcuerdo($entity);
                    $responsable->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($valor)));
                    $responsable->setTipo("Responsable");
                    $listapersonas->add($responsable);
                }
            }
            if($request->request->get('select-ejecutores-asignados')) {
                foreach ($request->request->get('select-ejecutores-asignados') as $indice => $valor) {
                    $ejecutor = new AcuerdoPersona();
                    $ejecutor->setIdAcuerdo($entity);
                    $ejecutor->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($valor)));
                    $ejecutor->setTipo("Ejecutor");
                    $listapersonas->add($ejecutor);
                }
            }
            foreach($listapersonas as $persona){
                $em->persist($persona);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('acuerdo'));
        }

        return $this->render('AcuerdosGestionBundle:Acuerdo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'personas' => $personas,
        ));
    }

    /**
     * Creates a form to create a Acuerdo entity.
     *
     * @param Acuerdo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Acuerdo $entity)
    {
        $form = $this->createForm(new AcuerdoType(), $entity, array(
            'action' => $this->generateUrl('acuerdo_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Acuerdo entity.
     *
     */
    public function newAction()
    {
        $entity = new Acuerdo();
        $form   = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAllOrdenados();

        return $this->render('AcuerdosGestionBundle:Acuerdo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'personas' => $personas,
        ));
    }

    /**
     * Finds and displays a Acuerdo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Acuerdo entity.');
        }

        //para mostrar el id sin el prefijo
        $subcad = explode("-", $entity->getIdAcuerdo());
        $entity->setIdAcuerdo($subcad[sizeof($subcad)-1]);

        $personasacuerdo = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findPersonasAcuerdo($id);

        return $this->render('AcuerdosGestionBundle:Acuerdo:show.html.twig', array(
            'entity'      => $entity,
            'personas' => $personasacuerdo,
        ));
    }

    /**
     * Displays a form to edit an existing Acuerdo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Acuerdo entity.');
        }
        //para mostrar el id sin el prefijo
        $subcad = explode("-", $entity->getIdAcuerdo());
        $entity->setIdAcuerdo($subcad[sizeof($subcad)-2]);

        $totalpersonas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAllOrdenados();
        $personasacuerdo = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findPersonasAcuerdo($id);

        $editForm = $this->createEditForm($entity);

        return $this->render('AcuerdosGestionBundle:Acuerdo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'totalpersonas' => $totalpersonas,
            'selectpersonas' => $personasacuerdo,
        ));
    }

    /**
    * Creates a form to edit a Acuerdo entity.
    *
    * @param Acuerdo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Acuerdo $entity)
    {
        $form = $this->createForm(new AcuerdoType(), $entity, array(
            'action' => $this->generateUrl('acuerdo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Acuerdo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Acuerdo entity.');
        }
        $totalpersonas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAllOrdenados();
        $personasacuerdo = $em->getRepository('AcuerdosGestionBundle:AcuerdoPersona')->findPersonasAcuerdo($id);

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($personasacuerdo as $persona){
                $em->remove($persona);
            }
            $anno = date_format($entity->getFechaCreacion(),'y');
            $entity->setIdAcuerdo($entity->getIdProcedencia()->getPrefijo()."-".$entity->getIdAcuerdo()."-".$anno);
            $listapersonas = new \Doctrine\Common\Collections\ArrayCollection();

            $autor = new AcuerdoPersona();
            $autor->setIdAcuerdo($entity);
            $autor->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($request->request->get('select-autor'))));
            $autor->setTipo("Autor");
            $listapersonas->add($autor);
            if($request->request->get('select-responsables-asignados')){
                foreach($request->request->get('select-responsables-asignados') as $indice => $valor){
                    $responsable = new AcuerdoPersona();
                    $responsable->setIdAcuerdo($entity);
                    $responsable->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($valor)));
                    $responsable->setTipo("Responsable");
                    $listapersonas->add($responsable);
                }
            }
            if($request->request->get('select-ejecutores-asignados')) {
                foreach ($request->request->get('select-ejecutores-asignados') as $indice => $valor) {
                    $ejecutor = new AcuerdoPersona();
                    $ejecutor->setIdAcuerdo($entity);
                    $ejecutor->setIdPersona($em->getRepository('AcuerdosGestionBundle:Persona')->findOneById(intval($valor)));
                    $ejecutor->setTipo("Ejecutor");
                    $listapersonas->add($ejecutor);
                }
            }
            foreach($listapersonas as $persona){
                $em->persist($persona);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('acuerdo', array('id' => $id)));
        }

        return $this->render('AcuerdosGestionBundle:Acuerdo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'totalpersonas' => $totalpersonas,
            'selectpersonas' => $personasacuerdo,
        ));
    }
    /**
     * Deletes a Acuerdo entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Acuerdo entity.');
        }

        $em->remove($entity);
        try {
            $em->flush();
        }
        catch(\Exception $e) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'El elemento no pudo ser eliminado. Es posible que tenga registros relacionados.'
            );
        }


        return $this->redirect($this->generateUrl('acuerdo'));
    }

    public function editCumpRealAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcuerdosGestionBundle:Acuerdo')->find($id);

        $fecha_creacion = date_create(date($request->request->get('fecha-cump-real')));

        $entity->setFechaCumplimientoReal($fecha_creacion);
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('acuerdos_procedencia', array(
            'idprocedencia' => $entity->getIdProcedencia()->getId(),

        )));
    }

}
