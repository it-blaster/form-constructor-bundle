<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Fenrizbes\FormConstructorBundle\Item\Behavior\AbstractBehaviorItem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractBehaviorAction extends AbstractBehaviorItem
{
    protected $action_choices = array();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->buildFormActionField($builder);
    }

    protected function buildFormActionField(FormBuilderInterface $builder)
    {
        $builder->add('action', 'choice', array(
            'label'       => 'fc.label.behavior.action_action',
            'required'    => true,
            'choices'     => $this->action_choices,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    public function getAction($key)
    {
        return $this->action_choices[$key];
    }
}