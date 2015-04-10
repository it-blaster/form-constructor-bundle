<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

class FieldCustomType extends FieldCommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('type', 'hidden')

            ->add('is_active', null, array(
                'label'    => 'fc.label.admin.field.is_active',
                'required' => false
            ))

            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'validate'))
        ;
    }
}