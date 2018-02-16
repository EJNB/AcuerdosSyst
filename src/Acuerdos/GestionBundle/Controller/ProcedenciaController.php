<?php

namespace Acuerdos\GestionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acuerdos\GestionBundle\Entity\Procedencia;
use Acuerdos\GestionBundle\Form\ProcedenciaType;

/**
 * Procedencia controller.
 *
 */
class ProcedenciaController extends Controller
{

    /**
     * Lists all Procedencia entities.
     *
     */
    public function indexAction(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $entities = $em->getRepository('AcuerdosGestionBundle:Procedencia')->findAll();
//
//        return $this->render('AcuerdosGestionBundle:Procedencia:index.html.twig', array(
//            'entities' => $entities,
//        ));

        $em = $this->getDoctrine()->getManager();
        $this->get('prefixedsessionservice')->setPrefix('procedencia');

        //Order, filter, paging...
        $success = $this->get('orderbyservice')->OrderBy($request->query->get('column'), array('procedencia', 'prefijo'));
        $success = $success && $this->get('filterservice')->Filter($request->request->get('filter'));
        $count =  $em->getRepository("AcuerdosGestionBundle:Procedencia")->countForAdminList($this->get('filterservice')->getText());
        $success = $success && $this->get('pagerservice')->Paging($request->query->get('page'), $count);

        if (!$success) {
            throw $this->createNotFoundException("Algunos de los parámetros proporcionados son incorrectos o están fuera de rango.");
        }

        //List...
        $entities = $em->getRepository("AcuerdosGestionBundle:Procedencia")->findAllForAdminList($this->get('orderbyservice')->getColumn(),
            $this->get('orderbyservice')->getOrder(),
            $this->get('filterservice')->getText(),
            $this->get('pagerservice')->getFirstResult(),
            $this->get('pagerservice')->getMaxResult());

        return $this->render("AcuerdosGestionBundle:Procedencia:index.html.twig", array(
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
     * Creates a new Procedencia entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Procedencia();
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

            return $this->redirect($this->generateUrl('procedencia', array('id' => $entity->getId())));
        }

        return $this->render('AcuerdosGestionBundle:Procedencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Procedencia entity.
     *
     * @param Procedencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Procedencia $entity)
    {
        $form = $this->createForm(new ProcedenciaType(), $entity, array(
            'action' => $this->generateUrl('procedencia_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Procedencia entity.
     *
     */
    public function newAction()
    {
        $entity = new Procedencia();
        $form   = $this->createCreateForm($entity);

        return $this->render('AcuerdosGestionBundle:Procedencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Procedencia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Procedencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Procedencia entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('AcuerdosGestionBundle:Procedencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Procedencia entity.
    *
    * @param Procedencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Procedencia $entity)
    {
        $form = $this->createForm(new ProcedenciaType(), $entity, array(
            'action' => $this->generateUrl('procedencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Procedencia entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosGestionBundle:Procedencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Procedencia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Los cambios fueron guardados satisfactoriamente!'
            );

            return $this->redirect($this->generateUrl('procedencia'));
        }

        return $this->render('AcuerdosGestionBundle:Procedencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Procedencia entity.
     *
     */
    public function deleteAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcuerdosGestionBundle:Procedencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Procedencia entity.');
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

        return $this->redirect($this->generateUrl('procedencia'));
    }

}
