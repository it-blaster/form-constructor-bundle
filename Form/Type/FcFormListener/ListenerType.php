<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener;

use Fenrizbes\FormConstructorBundle\Chain\ListenerChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ListenerType extends AbstractType
{
    /**
     * @var ListenerChain
     */
    protected $listener_chain;

    protected $action;

    public function __construct(ListenerChain $listener_chain, $action = null)
    {
        $this->listener_chain = $listener_chain;
        $this->action         = $action;
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

        foreach ($this->listener_chain->getListeners() as $alias => $listener) {
            $listeners[$alias] = $listener->getName();
        }

        return $listeners;
    }
}