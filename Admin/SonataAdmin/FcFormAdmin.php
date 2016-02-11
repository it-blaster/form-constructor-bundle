<?php

namespace Fenrizbes\FormConstructorBundle\Admin\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormPeer;
use Propel\PropelBundle\Validator\Constraints\UniqueObject;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Validator\Constraints\Regex;

class FcFormAdmin extends Admin
{
    protected $baseRouteName    = 'fenrizbes_fc_form';
    protected $baseRoutePattern = '/fenrizbes/fc/form';
    protected $fc_defaults;

    /**
     * @var FieldChain
     */
    protected $field_chain;

    public function setFcDefaults($fc_defaults)
    {
        $this->fc_defaults = $fc_defaults;
    }

    public function setFieldChain(FieldChain $field_chain)
    {
        $this->field_chain = $field_chain;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection
            ->add('get_fields',      $this->getRouterIdParameter() .'/get_fields')
            ->add('select_widget',   $this->getRouterIdParameter() .'/select_widget')
            ->add('create_field',    $this->getRouterIdParameter() .'/create_field')
            ->add('do_create_field', $this->getRouterIdParameter() .'/do_create_field/{type}')
            ->add('edit_field',      $this->getRouterIdParameter() .'/edit_field/{field_id}')
            ->add('do_edit_field',   $this->getRouterIdParameter() .'/do_edit_field/{field_id}')
            ->add('delete_field',    $this->getRouterIdParameter() .'/delete_field/{field_id}')
            ->add('do_delete_field', $this->getRouterIdParameter() .'/do_delete_field/{field_id}')
            ->add('move_field',      $this->getRouterIdParameter() .'/move_field/{field_id}/{to}')
            ->add('set_field_state', $this->getRouterIdParameter() .'/set_field_state/{item_id}/{active}')

            ->add('select_constraint',    $this->getRouterIdParameter() .'/select_constraint/{field_id}')
            ->add('create_constraint',    $this->getRouterIdParameter() .'/create_constraint/{field_id}')
            ->add('do_create_constraint', $this->getRouterIdParameter() .'/do_create_constraint/{field_id}/{constraint}')
            ->add('edit_constraint',      $this->getRouterIdParameter() .'/edit_constraint/{constraint_id}')
            ->add('do_edit_constraint',   $this->getRouterIdParameter() .'/do_edit_constraint/{constraint_id}')
            ->add('delete_constraint',    $this->getRouterIdParameter() .'/delete_constraint/{constraint_id}')
            ->add('do_delete_constraint', $this->getRouterIdParameter() .'/do_delete_constraint/{constraint_id}')
            ->add('set_constraint_state', $this->getRouterIdParameter() .'/set_constraint_state/{item_id}/{active}')

            ->add('get_listeners',      $this->getRouterIdParameter() .'/get_listeners')
            ->add('select_listener',    $this->getRouterIdParameter() .'/select_listener')
            ->add('create_listener',    $this->getRouterIdParameter() .'/create_listener')
            ->add('do_create_listener', $this->getRouterIdParameter() .'/do_create_listener/{listener}')
            ->add('edit_listener',      $this->getRouterIdParameter() .'/edit_listener/{listener_id}')
            ->add('do_edit_listener',   $this->getRouterIdParameter() .'/do_edit_listener/{listener_id}')
            ->add('delete_listener',    $this->getRouterIdParameter() .'/delete_listener/{listener_id}')
            ->add('do_delete_listener', $this->getRouterIdParameter() .'/do_delete_listener/{listener_id}')
            ->add('set_listener_state', $this->getRouterIdParameter() .'/set_listener_state/{item_id}/{active}')

            ->add('get_templates',      $this->getRouterIdParameter() .'/get_templates')
            ->add('select_template',    $this->getRouterIdParameter() .'/select_template')
            ->add('create_template',    $this->getRouterIdParameter() .'/create_template')
            ->add('do_create_template', $this->getRouterIdParameter() .'/do_create_template/{template}')
            ->add('edit_template',      $this->getRouterIdParameter() .'/edit_template/{template_id}')
            ->add('do_edit_template',   $this->getRouterIdParameter() .'/do_edit_template/{template_id}')
            ->add('delete_template',    $this->getRouterIdParameter() .'/delete_template/{template_id}')
            ->add('do_delete_template', $this->getRouterIdParameter() .'/do_delete_template/{template_id}')
            ->add('set_template_state', $this->getRouterIdParameter() .'/set_template_state/{item_id}/{active}')

            ->add('get_behaviors',      $this->getRouterIdParameter() .'/get_behaviors')
            ->add('do_create_behavior', $this->getRouterIdParameter() .'/do_create_behavior')
            ->add('delete_behavior',    $this->getRouterIdParameter() .'/delete_behavior/{behavior_id}')
            ->add('do_delete_behavior', $this->getRouterIdParameter() .'/do_delete_behavior/{behavior_id}')
            ->add('set_behavior_state', $this->getRouterIdParameter() .'/set_behavior_state/{item_id}/{active}')

            ->add('select_behavior_condition',    $this->getRouterIdParameter() .'/select_behavior_condition/{behavior_id}')
            ->add('create_behavior_condition',    $this->getRouterIdParameter() .'/create_behavior_condition/{behavior_id}')
            ->add('do_create_behavior_condition', $this->getRouterIdParameter() .'/do_create_behavior_condition/{behavior_id}/{condition}')
            ->add('edit_behavior_condition',      $this->getRouterIdParameter() .'/edit_behavior_condition/{condition_id}')
            ->add('do_edit_behavior_condition',   $this->getRouterIdParameter() .'/do_edit_behavior_condition/{condition_id}')
            ->add('delete_behavior_condition',    $this->getRouterIdParameter() .'/delete_behavior_condition/{condition_id}')
            ->add('do_delete_behavior_condition', $this->getRouterIdParameter() .'/do_delete_behavior_condition/{condition_id}')
            ->add('set_behavior_condition_state', $this->getRouterIdParameter() .'/set_behavior_condition_state/{item_id}/{active}')

            ->add('select_behavior_action',    $this->getRouterIdParameter() .'/select_behavior_action/{behavior_id}/{check}')
            ->add('create_behavior_action',    $this->getRouterIdParameter() .'/create_behavior_action/{behavior_id}/{check}')
            ->add('do_create_behavior_action', $this->getRouterIdParameter() .'/do_create_behavior_action/{behavior_id}/{check}/{action}')
            ->add('edit_behavior_action',      $this->getRouterIdParameter() .'/edit_behavior_action/{action_id}')
            ->add('do_edit_behavior_action',   $this->getRouterIdParameter() .'/do_edit_behavior_action/{action_id}')
            ->add('delete_behavior_action',    $this->getRouterIdParameter() .'/delete_behavior_action/{action_id}')
            ->add('do_delete_behavior_action', $this->getRouterIdParameter() .'/do_delete_behavior_action/{action_id}')
            ->add('set_behavior_action_state', $this->getRouterIdParameter() .'/set_behavior_action_state/{item_id}/{active}')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('Title', null, array(
                'label' => 'fc.label.admin.title'
            ))
            ->add('IsActive', null, array(
                'label' => 'fc.label.admin.is_active'
            ))
            ->add('IsWidget', null, array(
                'label' => 'fc.label.admin.is_widget'
            ))
            ->add('CreatedAt', null, array(
                'label'  => 'fc.label.admin.created_at',
                'format' => $this->fc_defaults['datetime_format']
            ))
            ->add('UpdatedAt', null, array(
                'label'  => 'fc.label.admin.updated_at',
                'format' => $this->fc_defaults['datetime_format']
            ))
            ->add('_action', 'actions', array(
                'label'    => 'fc.label.admin.actions',
                'sortable' => false,
                'actions'  => array(
                    'show'   => array(),
                    'edit'   => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('fc.label.admin.with.form_main')
                ->add('Title', null, array(
                    'label' => 'fc.label.admin.title'
                ))
                ->add('Alias', null, array(
                    'label'       => 'fc.label.admin.alias',
                    'constraints' => array(
                        new Regex(array(
                            'pattern' => '/^\w+$/',
                            'message' => 'fc.constraint.admin.not_alphanumeric'
                        ))
                    )
                ))
                ->add('Message', $this->fc_defaults['text_editor'], array(
                    'label'    => 'fc.label.admin.message',
                    'required' => false
                ))
        ;

        if (!$this->getSubject()->isNew()) {
            $formMapper
                ->add('IsActive', null, array(
                    'label'    => 'fc.label.admin.is_active',
                    'required' => false
                ))
            ;
        }

        $formMapper
            ->end()
            ->with('fc.label.admin.with.form_additional')
                ->add('Method', 'choice', array(
                    'label'   => 'fc.label.admin.method',
                    'choices' => array(
                        'POST' => 'POST',
                        'GET'  => 'GET'
                    )
                ))
                ->add('Action', null, array(
                    'label' => 'fc.label.admin.action'
                ))
                ->add('Button', null, array(
                    'label' => 'fc.label.admin.button'
                ))
                ->add('IsAjax', null, array(
                    'label'    => 'fc.label.admin.is_ajax',
                    'required' => false
                ))
                ->add('IsWidget', null, array(
                    'label'    => 'fc.label.admin.is_widget',
                    'required' => false
                ))
            ->end()

            ->setHelps(array(
                'Alias'    => 'fc.help.admin.alias',
                'Action'   => 'fc.help.admin.action',
                'Button'   => 'fc.help.admin.button',
                'IsAjax'   => 'fc.help.admin.is_ajax',
                'IsWidget' => 'fc.help.admin.is_widget'
            ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement->addConstraint(new UniqueObject(array(
            'fields'  => 'alias',
            'message' =>
                $this->trans('fc.label.admin.alias', array(), $this->getTranslationDomain()).': '.
                $this->trans('fc.constraint.admin.not_unique', array(), 'validators')
        )));

        /** @var FcForm $object */
        if ($this->field_chain->hasField($object->getAlias())) {
            $errorElement->addViolation(
                $this->trans('fc.message.admin.form.alias_conflicts', array(), $this->getTranslationDomain())
            );
        }

        if (
            $object->isColumnModified(FcFormPeer::IS_WIDGET)
            &&
            !$object->getIsWidget()
            &&
            $object->isUsedAsWidget()
        ) {
            $errorElement->addViolation(
                $this->trans('fc.message.admin.form.is_used_as_widget', array(), $this->getTranslationDomain())
            );
        }
    }

    protected function configureShowFields(ShowMapper $filter)
    {
        $filter->add('id');
    }
}
