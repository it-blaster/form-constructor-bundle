<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin;

use Symfony\Component\Form\FormBuilderInterface;

class TextType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeholder', 'text', array(
                'label'    => 'fc.label.admin.field.placeholder',
                'required' => false
            ))
        ;
    }
}