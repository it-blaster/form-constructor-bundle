<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Symfony\Component\Form\FormBuilderInterface;

class SetValueBehaviorAction extends AbstractBehaviorAction
{
    public function getName()
    {
        return 'fc.label.behavior.actions.set_value';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldField($builder);

        $builder->add('value', 'text', array(
            'label'    => 'fc.label.admin.field.value',
            'required' => false
        ));
    }
}