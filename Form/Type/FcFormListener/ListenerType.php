<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ListenerType extends AbstractType
{
    protected $listeners;
    protected $action;

    public function __construct(array $listeners, $action = null)
    {
        $this->listeners  = $listeners;
        $this->action     = $action;
    }

    public function getName()
    {
        return 'fc_field_listener';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listener', 'choice', array(
                'label'       => 'fc.label.admin.listener',
                'choices'     => $this->buildConstraintChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildConstraintChoices()
    {
        $listeners = array();

        foreach ($this->listeners as $name => $item) {
            $listeners[$name] = $item['label'];
        }

        return $listeners;
    }
}