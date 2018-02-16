<?php

namespace Acuerdos\SeguridadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Acuerdos\SeguridadBundle\Entity\Role;
use Acuerdos\SeguridadBundle\Entity\User;
use Acuerdos\SeguridadBundle\Form\ChangePassType;
use Acuerdos\SeguridadBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('prefixedsessionservice')->setPrefix('usuarios');

        //Order, filter, paging...
        $success = $this->get('orderbyservice')->OrderBy($request->query->get('column'), array('name', 'username'));
        $success = $success && $this->get('filterservice')->Filter($request->request->get('filter'));
        $count =  $em->getRepository("AcuerdosSeguridadBundle:User")->countForAdminList($this->get('filterservice')->getText());
        $success = $success && $this->get('pagerservice')->Paging($request->query->get('page'), $count);

        if (!$success) {
            throw $this->createNotFoundException("Algunos de los parámetros proporcionados son incorrectos o están fuera de rango.");
        }

        //List...
        $entities = $em->getRepository("AcuerdosSeguridadBundle:User")->findAllForAdminList($this->get('orderbyservice')->getColumn(),
            $this->get('orderbyservice')->getOrder(),
            $this->get('filterservice')->getText(),
            $this->get('pagerservice')->getFirstResult(),
            $this->get('pagerservice')->getMaxResult());

//        $form_changepass = $this->createForm(new ChangePassType(), array(
////            'action' => $this->generateUrl('users_changepass', array('id' => 'id')),
//            'method' => 'POST',
//        ));

        return $this->render("AcuerdosSeguridadBundle:User:index.html.twig", array(
            'entities' => $entities,
            'count' => $count,
            'column' => $this->get('orderbyservice')->getColumn(),
            'order' => $this->get('orderbyservice')->getOrder(),
            'filter' => $this->get('filterservice')->getText(),
            'page' => $this->get('pagerservice')->getPage(),
            'page_count' => $this->get('pagerservice')->getPageCount(),
//            'form' => $form_changepass->createView(),
        ));
    }
    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //condificacion de contraseña
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $entity->setSalt(md5(time()));
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);

            $em = $this->getDoctrine()->getManager();

            //asignar rol de lectura por defecto si no se selecciono ninguno
            $role_defecto = $em->getRepository('AcuerdosSeguridadBundle:Role')->find(5);
            if(!$entity->getRoles()){
                $entity->addRole($role_defecto);
            }

            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Sus cambios fueron guardados satisfactoriamente!'
            );

            return $this->redirect($this->generateUrl('users_show', array('id' => $entity->getId())));
        }

        return $this->render('AcuerdosSeguridadBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('users_create'),
            'method' => 'POST',
        ));

        $form->add('password', 'password', array('label' => 'Password'));
        $form->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Los campos deben coincidir',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Repetir Password'),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('AcuerdosSeguridadBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return $this->render('AcuerdosSeguridadBundle:User:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('AcuerdosSeguridadBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('users_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Sus cambios fueron guardados satisfactoriamente!'
            );

            return $this->redirect($this->generateUrl('users_show', array('id' => $id)));
        }

        return $this->render('AcuerdosSeguridadBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
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


        return $this->redirect($this->generateUrl('users'));
    }

    public function changepassAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

        $user->setPassword($request->request->get('password'));
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('users'));

    }

    public function changepassUserAction(Request $request){

        $id = $request->request->get('id');
        $ruta = $request->request->get('ruta');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AcuerdosSeguridadBundle:User')->find($id);

        $previous_pass = $request->request->get('previous_password');
        $actual_pass = $user->getPassword();
        $actual_salt = $user->getSalt();

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        if($encoder->isPasswordValid($actual_pass, $previous_pass, $actual_salt)){
            $user->setPassword($request->request->get('password_user'));
            $user->setSalt(md5(time()));
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();
        }
        else{
            $this->get('session')->getFlashBag()->add(
                'error_changepass',
                'La contraseña anterior no es correcta.'
            );
        }
        return $this->redirect($ruta);
    }

}
