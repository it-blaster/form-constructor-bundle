<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Symfony\Component\Form\FormBuilderInterface;

class EnableDisableBehaviorAction extends AbstractBehaviorAction
{
    protected $action_choices = array(
        'enable'  => 'fc.label.behavior.action_action.enable',
        'disable' => 'fc.label.behavior.action_action.disable'
    );

    public function getName()
    {
        return 'fc.label.behavior.actions.enable_disable';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldField($builder, true);
        $this->buildFormActionField($builder);
    }
}