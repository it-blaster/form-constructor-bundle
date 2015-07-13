<?php

namespace Fenrizbes\FormConstructorBundle\Controller\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin\FieldCommonType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin\FieldCustomType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint\Admin\ConstraintCommonType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint\ConstraintType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcField\WidgetType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener\Admin\ListenerCommonType;
use Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener\ListenerType;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraintQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListenerQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\CoreBundle\Exception\InvalidParameterException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FcFormAdminController extends CRUDController
{
    /**
     * Deletes form
     *
     * @param int|null|string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        if ($this->getRestMethod() == 'DELETE') {
            /** @var FcForm $object */
            $fc_form = $this->admin->getObject($id);

            if ($fc_form->isUsedAsWidget()) {
                $this->addFlash('sonata_flash_error', $this->admin->trans(
                    'fc.message.admin.form.is_used_as_widget',
                    array(),
                    'FenrizbesFormConstructorBundle'
                ));

                return $this->redirectTo($fc_form);
            }
        }

        return parent::deleteAction($id);
    }

    /**
     * Batch forms deletion
     *
     * @param ProxyQueryInterface $query
     * @return RedirectResponse
     */
    public function batchActionDelete(ProxyQueryInterface $query)
    {
        /** @var FcForm $fc_form */
        foreach ($query->find() as $fc_form) {
            if ($fc_form->isUsedAsWidget()) {
                $this->addFlash('sonata_flash_error', $this->admin->trans(
                    'fc.message.admin.form.is_used_as_widget',
                    array(),
                    'FenrizbesFormConstructorBundle'
                ));

                return new RedirectResponse($this->admin->generateUrl('list', array(
                    'filter' => $this->admin->getFilterParameters()
                )));
            }
        }

        return parent::batchActionDelete($query);
    }

    /**
     * Renders fields' tab
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getFieldsAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_form = FcFormQuery::create()->findPk($id);
        if (!$fc_form instanceof FcForm) {
            throw $this->createNotFoundException();
        }

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:edit_tab_fields.html.twig', array(
            'object' => $fc_form
        ));
    }

    /**
     * Renders field's widgets' choice
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function selectWidgetAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(new WidgetType(
            $this->container->get('fc.field.chain'),
            $this->admin->generateUrl('create_field', array('id' => $id)),
            $id
        ));

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Returns FormType for a specific field
     *
     * @param FcField $field
     * @param $form_action
     * @return \Symfony\Component\Form\Form
     * @throws \Exception
     */
    protected function getFieldFormType(FcField $field, $form_action)
    {
        $chain = $this->get('fc.field.chain');
        $alias = $field->getType();

        if ($chain->hasField($alias)) {
            $type = new FieldCommonType($form_action, $this->get('translator'), $chain->getParamsBuilder($alias));
        } else {
            $custom_widget = FcFormQuery::create()
                ->filterByAlias($alias)
                ->filterByIsWidget(true)
                ->findOne();

            if (!$custom_widget instanceof FcForm) {
                throw new \Exception('Field "'. $alias .'" not found');
            }

            $type = new FieldCustomType($form_action, $this->get('translator'));
        }

        return $this->createForm($type, $field);
    }

    /**
     * Renders field's create form
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Sonata\CoreBundle\Exception\InvalidParameterException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Exception
     */
    public function createFieldAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $widget_form = $this->createForm(new WidgetType($this->container->get('fc.field.chain')));
        $widget_form->handleRequest($request);
        $widget_data = $widget_form->getData();
        if (!is_array($widget_data) || !isset($widget_data['type'])) {
            throw new InvalidParameterException('Widget\'s name not passed');
        }

        $field = new FcField();
        $field->setType($widget_data['type']);

        $form_action = $this->admin->generateUrl('do_create_field', array(
            'id'   => $id,
            'type' => $widget_data['type']
        ));

        $form = $this->getFieldFormType($field, $form_action);

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Creates and saves a field
     *
     * @param Request $request
     * @param $id
     * @param $type
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doCreateFieldAction(Request $request, $id, $type)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_form = FcFormQuery::create()->findPk($id);
            if (!$fc_form instanceof FcForm) {
                throw $this->createNotFoundException();
            }

            $field = new FcField();
            $field->setType($type);
            $field->setFcForm($fc_form);

            $form_action = $this->admin->generateUrl('do_create_field', array(
                'id'   => $id,
                'type' => $type
            ));

            $form = $this->getFieldFormType($field, $form_action);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $field->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Renders field's edit form
     *
     * @param Request $request
     * @param $field_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editFieldAction(Request $request, $field_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $field = FcFieldQuery::create()->findPk($field_id);
        if (!$field instanceof FcField) {
            throw $this->createNotFoundException();
        }

        $form_action = $this->admin->generateUrl('do_edit_field', array(
            'id'       => $field->getFormId(),
            'field_id' => $field->getId()
        ));

        $form = $this->getFieldFormType($field, $form_action);

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Saves field's changes
     *
     * @param Request $request
     * @param $field_id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doEditFieldAction(Request $request, $field_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $field = FcFieldQuery::create()->findPk($field_id);
            if (!$field instanceof FcField) {
                throw $this->createNotFoundException();
            }

            $form_action = $this->admin->generateUrl('do_edit_field', array(
                'id'       => $field->getFormId(),
                'field_id' => $field->getId()
            ));

            $form = $this->getFieldFormType($field, $form_action);
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var FcField $field */
                $field = $form->getData();
                $field->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Changes field's sortable rank
     *
     * @param Request $request
     * @param $field_id
     * @param $to
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function moveFieldAction(Request $request, $field_id, $to)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_field = FcFieldQuery::create()->findPk($field_id);
        if (!$fc_field instanceof FcField) {
            throw $this->createNotFoundException();
        }

        switch ($to) {
            case 'down':
                if (!$fc_field->isLast()) {
                    $fc_field->moveDown();
                }
                break;

            case 'up':
                if (!$fc_field->isFirst()) {
                    $fc_field->moveUp();
                }
                break;
        }

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Renders field's delete confirmation
     *
     * @param Request $request
     * @param $field_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function deleteFieldAction(Request $request, $field_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_field = FcFieldQuery::create()->findPk($field_id);
        if (!$fc_field instanceof FcField) {
            throw $this->createNotFoundException();
        }

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcField:delete_confirmation.html.twig', array(
            'item' => $fc_field
        ));
    }

    /**
     * Deletes a field
     *
     * @param Request $request
     * @param $field_id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doDeleteFieldAction(Request $request, $field_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_field = FcFieldQuery::create()->findPk($field_id);
            if (!$fc_field instanceof FcField) {
                throw $this->createNotFoundException();
            }

            $fc_field->delete();
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Sets field's active state
     *
     * @param Request $request
     * @param $item_id
     * @param $active
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function setFieldStateAction(Request $request, $item_id, $active)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_field = FcFieldQuery::create()->findPk($item_id);
        if (!$fc_field instanceof FcField) {
            throw $this->createNotFoundException();
        }

        $fc_field->setIsActive($active);
        $fc_field->save();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Renders constraints' choice
     *
     * @param Request $request
     * @param $id
     * @param $field_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function selectConstraintAction(Request $request, $id, $field_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(new ConstraintType(
            $this->container->get('fc.constraint.chain'),
            $this->admin->generateUrl('create_constraint', array(
                'id'       => $id,
                'field_id' => $field_id
            ))
        ));

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Renders constraint's create form
     *
     * @param Request $request
     * @param $id
     * @param $field_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Sonata\CoreBundle\Exception\InvalidParameterException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createConstraintAction(Request $request, $id, $field_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $chain = $this->container->get('fc.constraint.chain');

        $constraint_form = $this->createForm(new ConstraintType($chain));
        $constraint_form->handleRequest($request);
        $constraint_data = $constraint_form->getData();
        if (!is_array($constraint_data) || !isset($constraint_data['constraint'])) {
            throw new InvalidParameterException('Constraint\'s name not passed');
        }

        $alias = $constraint_data['constraint'];

        $form_action = $this->admin->generateUrl('do_create_constraint', array(
            'id'         => $id,
            'field_id'   => $field_id,
            'constraint' => $alias
        ));

        $field_constraint = new FcFieldConstraint();
        $field_constraint->setConstraint($alias);

        $form = $this->createForm(
            new ConstraintCommonType($form_action, $chain->getParamsBuilder($alias)),
            $field_constraint
        );

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Creates and saves a constraint
     *
     * @param Request $request
     * @param $field_id
     * @param $constraint
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doCreateConstraintAction(Request $request, $field_id, $constraint)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_field = FcFieldQuery::create()->findPk($field_id);
            if (!$fc_field instanceof FcField) {
                throw $this->createNotFoundException();
            }

            $form_action = $this->admin->generateUrl('do_create_constraint', array(
                'id'         => $fc_field->getFormId(),
                'field_id'   => $fc_field->getId(),
                'constraint' => $constraint
            ));

            $field_constraint = new FcFieldConstraint();
            $field_constraint->setConstraint($constraint);
            $field_constraint->setFcField($fc_field);

            $form = $this->createForm(
                new ConstraintCommonType(
                    $form_action,
                    $this->container->get('fc.constraint.chain')->getParamsBuilder($constraint)
                ),
                $field_constraint
            );
            $form->handleRequest($request);

            if ($form->isValid()) {
                $field_constraint->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Renders constraint's edit form
     *
     * @param Request $request
     * @param $id
     * @param $constraint_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editConstraintAction(Request $request, $id, $constraint_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_constraint = FcFieldConstraintQuery::create()->findPk($constraint_id);
        if (!$fc_constraint instanceof FcFieldConstraint) {
            throw $this->createNotFoundException();
        }

        $form_action = $this->admin->generateUrl('do_edit_constraint', array(
            'id'            => $id,
            'constraint_id' => $fc_constraint->getId()
        ));

        $form = $this->createForm(
            new ConstraintCommonType(
                $form_action,
                $this->container->get('fc.constraint.chain')->getParamsBuilder($fc_constraint->getConstraint())
            ),
            $fc_constraint
        );

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function doEditConstraintAction(Request $request, $id, $constraint_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_constraint = FcFieldConstraintQuery::create()->findPk($constraint_id);
            if (!$fc_constraint instanceof FcFieldConstraint) {
                throw $this->createNotFoundException();
            }

            $form_action = $this->admin->generateUrl('do_edit_constraint', array(
                'id'            => $id,
                'constraint_id' => $fc_constraint->getId()
            ));

            $form = $this->createForm(
                new ConstraintCommonType(
                    $form_action,
                    $this->container->get('fc.constraint.chain')->getParamsBuilder($fc_constraint->getConstraint())
                ),
                $fc_constraint
            );
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var FcFieldConstraint $fc_constraint */
                $fc_constraint = $form->getData();
                $fc_constraint->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Renders constraint's delete confirmation
     *
     * @param Request $request
     * @param $constraint_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function deleteConstraintAction(Request $request, $constraint_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_constraint = FcFieldConstraintQuery::create()->findPk($constraint_id);
        if (!$fc_constraint instanceof FcFieldConstraint) {
            throw $this->createNotFoundException();
        }

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcFieldConstraint:delete_confirmation.html.twig', array(
            'item' => $fc_constraint
        ));
    }

    /**
     * Deletes a constraint
     *
     * @param Request $request
     * @param $constraint_id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doDeleteConstraintAction(Request $request, $constraint_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_constraint = FcFieldConstraintQuery::create()->findPk($constraint_id);
            if (!$fc_constraint instanceof FcFieldConstraint) {
                throw $this->createNotFoundException();
            }

            $fc_constraint->delete();
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Sets constraint's active state
     *
     * @param Request $request
     * @param $item_id
     * @param $active
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function setConstraintStateAction(Request $request, $item_id, $active)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_constraint = FcFieldConstraintQuery::create()->findPk($item_id);
        if (!$fc_constraint instanceof FcFieldConstraint) {
            throw $this->createNotFoundException();
        }

        $fc_constraint->setIsActive($active);
        $fc_constraint->save();

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Renders listeners' tab
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getListenersAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_form = FcFormQuery::create()->findPk($id);
        if (!$fc_form instanceof FcForm) {
            throw $this->createNotFoundException();
        }

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:edit_tab_listeners.html.twig', array(
            'object' => $fc_form
        ));
    }

    /**
     * Renders listener's choice
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function selectListenerAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(new ListenerType(
            $this->container->get('fc.listener.chain'),
            $this->admin->generateUrl('create_listener', array('id' => $id))
        ));

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Renders listener's create form
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Sonata\CoreBundle\Exception\InvalidParameterException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createListenerAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $chain = $this->container->get('fc.listener.chain');

        $listener_form = $this->createForm(new ListenerType($chain));
        $listener_form->handleRequest($request);
        $listener_data = $listener_form->getData();
        if (!is_array($listener_data) || !isset($listener_data['listener'])) {
            throw new InvalidParameterException('Listener\'s name not passed');
        }

        $alias = $listener_data['listener'];

        $fc_listener = new FcFormEventListener();
        $fc_listener->setListener($alias);

        $form_action = $this->admin->generateUrl('do_create_listener', array(
            'id'       => $id,
            'listener' => $alias
        ));

        $form = $this->createForm(
            new ListenerCommonType(
                $form_action,
                $this->get('translator'),
                $chain->getParamsBuilder($alias)
            ),
            $fc_listener
        );

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Creates and saves a listener
     *
     * @param Request $request
     * @param $id
     * @param $listener
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doCreateListenerAction(Request $request, $id, $listener)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_form = FcFormQuery::create()->findPk($id);
            if (!$fc_form instanceof FcForm) {
                throw $this->createNotFoundException();
            }

            $fc_listener = new FcFormEventListener();
            $fc_listener->setListener($listener);
            $fc_listener->setFcForm($fc_form);

            $form_action = $this->admin->generateUrl('do_create_listener', array(
                'id'       => $id,
                'listener' => $listener
            ));

            $form = $this->createForm(
                new ListenerCommonType(
                    $form_action,
                    $this->get('translator'),
                    $this->container->get('fc.listener.chain')->getParamsBuilder($listener)
                ),
                $fc_listener
            );
            $form->handleRequest($request);

            if ($form->isValid()) {
                $fc_listener->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Renders listener's edit form
     *
     * @param Request $request
     * @param $listener_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editListenerAction(Request $request, $listener_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_listener = FcFormEventListenerQuery::create()->findPk($listener_id);
        if (!$fc_listener instanceof FcFormEventListener) {
            throw $this->createNotFoundException();
        }

        $form_action = $this->admin->generateUrl('do_edit_listener', array(
            'id'          => $fc_listener->getFormId(),
            'listener_id' => $fc_listener->getId()
        ));

        $form = $this->createForm(
            new ListenerCommonType(
                $form_action,
                $this->get('translator'),
                $this->container->get('fc.listener.chain')->getParamsBuilder($fc_listener->getListener())
            ),
            $fc_listener
        );

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Saves listener's changes
     *
     * @param Request $request
     * @param $listener_id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doEditListenerAction(Request $request, $listener_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_listener = FcFormEventListenerQuery::create()->findPk($listener_id);
            if (!$fc_listener instanceof FcFormEventListener) {
                throw $this->createNotFoundException();
            }

            $form_action = $this->admin->generateUrl('do_edit_listener', array(
                'id'          => $fc_listener->getFormId(),
                'listener_id' => $fc_listener->getId()
            ));

            $form = $this->createForm(
                new ListenerCommonType(
                    $form_action,
                    $this->get('translator'),
                    $this->container->get('fc.listener.chain')->getParamsBuilder($fc_listener->getListener())
                ),
                $fc_listener
            );
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var FcFormEventListener $field */
                $fc_listener = $form->getData();
                $fc_listener->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }

    /**
     * Renders listener's delete confirmation
     *
     * @param Request $request
     * @param $listener_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function deleteListenerAction(Request $request, $listener_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_listener = FcFormEventListenerQuery::create()->findPk($listener_id);
        if (!$fc_listener instanceof FcFormEventListener) {
            throw $this->createNotFoundException();
        }

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcFormListener:delete_confirmation.html.twig', array(
            'item' => $fc_listener
        ));
    }

    /**
     * Deletes a listener
     *
     * @param Request $request
     * @param $listener_id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function doDeleteListenerAction(Request $request, $listener_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createAccessDeniedException();
            }

            $fc_listener = FcFormEventListenerQuery::create()->findPk($listener_id);
            if (!$fc_listener instanceof FcFormEventListener) {
                throw $this->createNotFoundException();
            }

            $fc_listener->delete();
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * Sets listener's active state
     *
     * @param Request $request
     * @param $item_id
     * @param $active
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function setListenerStateAction(Request $request, $item_id, $active)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $fc_listener = FcFormEventListenerQuery::create()->findPk($item_id);
        if (!$fc_listener instanceof FcFormEventListener) {
            throw $this->createNotFoundException();
        }

        $fc_listener->setIsActive($active);
        $fc_listener->save();

        return new JsonResponse(array(
            'success' => true
        ));
    }
}
