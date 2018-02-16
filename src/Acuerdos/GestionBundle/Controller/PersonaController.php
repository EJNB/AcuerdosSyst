<?php

namespace Acuerdos\GestionBundle\Controller;

use Acuerdos\GestionBundle\Entity\Acuerdo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acuerdos\GestionBundle\Entity\Persona;
use Acuerdos\GestionBundle\Form\PersonaType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Persona controller.
 *
 */
class PersonaController extends Controller
{

    /**
     * Lists all Persona entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('prefixedsessionservice')->setPrefix('persona');

        //Order, filter, paging...
        $success = $this->get('orderbyservice')->OrderBy($request->query->get('column'), array('descripcion', 'cargo', 'email'));
        $success = $success && $this->get('filterservice')->Filter($request->request->get('filter'));
        $count =  $em->getRepository("AcuerdosGestionBundle:Persona")->countForAdminList($this->get('filterservice')->getText());
        $success = $success && $this->get('pagerservice')->Paging($request->query->get('page'), $count);

        if (!$success) {
            throw $this->createNotFoundException("Algunos de los parámetros proporcionados son incorrectos o están fuera de rango.");
        }

        //List...
        $entities = $em->getRepository("AcuerdosGestionBundle:Persona")->findAllForAdminList($this->get('orderbyservice')->getColumn(),
            $this->get('orderbyservice')->getOrder(),
            $this->get('filterservice')->getText(),
            $this->get('pagerservice')->getFirstResult(),
            $this->get('pagerservice')->getMaxResult());

        return $this->render("AcuerdosGestionBundle:Persona:index.html.twig", array(
            'entities' => $entities,
            'count' => $count,
            'column' => $this->get('orderbyservice')->getColumn(),
            'order' => $this->get('orderbyservice')->getOrder(),
            'filter' => $this->get('filterservice')->getText(),
            'page' => $this->get('pagerservice')->getPage(),
            'page_count' => $this->get('pagerservice')->getPageCount(),
        ));
    }
    /**
     * Creates a new Persona entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Persona();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Los cambios fueron guardados satisfactoriamente!'
            );

            return $this->redirect($this->generateUrl('persona'));
        }

        return $this->render('AcuerdosGestionBundle:Persona:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Persona entity.
     *
     * @param Persona $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Persona $entity)
    {
        $form = $this->createForm(new PersonaType(), $entity, array(
            'action' => $this->generateUrl('persona_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Persona entity.
     *
     */
    public function newAction()
    {
        $entity = new Persona();
        $form   = $this->createCreateForm($entity);

        return $this->render('AcuerdosGestionBundle:Persona:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Persona entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Persona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Persona entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('AcuerdosGestionBundle:Persona:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Persona entity.
    *
    * @param Persona $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Persona $entity)
    {
        $form = $this->createForm(new PersonaType(), $entity, array(
            'action' => $this->generateUrl('persona_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Persona entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Persona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Persona entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Los cambios fueron guardados satisfactoriamente!'
            );

            return $this->redirect($this->generateUrl('persona'));
        }

        return $this->render('AcuerdosGestionBundle:Persona:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Persona entity.
     *
     */
    public function deleteAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcuerdosGestionBundle:Persona')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Persona entity.');
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


        return $this->redirect($this->generateUrl('persona'));
    }

    public function createPersonaFromAcuerdoAction(){
        $request = $this->get('request');

        $em = $this->getDoctrine()->getManager();
        $persona = new Persona();
        $persona->setDescripcion($request->request->get('descripcion'));
        $persona->setCargo($request->request->get('cargo'));
        $persona->setEmail($request->request->get('email'));
        $em->persist($persona);
        $em->flush();

        return $this->redirect($this->generateUrl('acuerdo_new'));

    }

    public function updateListaFromAcuerdoAction(){
        $request = $this->get('request');


        $em = $this->getDoctrine()->getManager();

        $personas = $em->getRepository('AcuerdosGestionBundle:Persona')->findAll();
        return $this->render('AcuerdosGestionBundle:Default:personas.html.twig', array(
            'personas' => $personas,
        ));


    }


}
