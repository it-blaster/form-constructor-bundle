<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceOptionType extends AbstractType
{
    public function getName()
    {
        return 'choice_option';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', 'text', array(
                'label'    => 'fc.label.admin.choice.value',
                'required' => false
            ))
            ->add('label', 'text', array(
                'label'    => 'fc.label.admin.choice.label',
                'required' => false
            ))
            ->add('is_selected', 'checkbox', array(
                'label'    => 'fc.label.admin.choice.is_selected',
                'required' => false
            ))
        ;
    }
}