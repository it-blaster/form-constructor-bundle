<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Symfony\Component\Form\FormBuilderInterface;

class ShowHideBehaviorAction extends AbstractBehaviorAction
{
    protected $action_choices = array(
        'show' => 'fc.label.behavior.action_action.show',
        'hide' => 'fc.label.behavior.action_action.hide'
    );

    public function getName()
    {
        return 'fc.label.behavior.actions.show_hide';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldField($builder, true);
        $this->buildFormActionField($builder);
    }
}