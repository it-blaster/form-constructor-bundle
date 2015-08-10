<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CopyValueBehaviorAction extends AbstractBehaviorAction
{
    public function getName()
    {
        return 'fc.label.behavior.actions.copy_value';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source_field', 'choice', array(
                'label'       => 'fc.label.behavior.action_source_field',
                'required'    => true,
                'choices'     => $this->getFieldsChoices(),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('target_field', 'choice', array(
                'label'       => 'fc.label.behavior.action_target_field',
                'required'    => true,
                'choices'     => $this->getFieldsChoices(),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
        ;
    }
}