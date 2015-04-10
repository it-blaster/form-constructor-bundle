<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint\Admin;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class LengthType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min', 'text', array(
                'label'    => 'fc.label.admin.constraint.min',
                'required' => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('max', 'text', array(
                'label'       => 'fc.label.admin.constraint.max',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
        ;
    }
}