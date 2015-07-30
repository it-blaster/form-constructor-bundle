<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StepType extends AbstractType
{
    public function getName()
    {
        return 'step';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'button_prev',
            'button_next'
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['button_prev'] = $options['button_prev'];
        $view->vars['button_next'] = $options['button_next'];
    }
}