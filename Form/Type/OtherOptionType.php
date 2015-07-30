<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OtherOptionType extends AbstractType
{
    public function getName()
    {
        return 'other_option';
    }

    public function getParent()
    {
        return 'text';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'linked_field'
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['linked_field'] = $options['linked_field'];
    }
}