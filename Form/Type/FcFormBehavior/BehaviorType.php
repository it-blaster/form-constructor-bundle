<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormBehavior;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BehaviorType extends AbstractType
{
    /**
     * @var BehaviorChain
     */
    protected $behavior_chain;

    protected $action;

    public function __construct(BehaviorChain $behavior_chain, $action = null)
    {
        $this->behavior_chain = $behavior_chain;
        $this->action         = $action;
    }

    public function getName()
    {
        return 'fc_field_behavior';
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
            ->add('behavior', 'choice', array(
                'label'       => 'fc.label.admin.behavior',
                'choices'     => $this->buildBehaviorChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildBehaviorChoices()
    {
        $behaviors = array();

        foreach ($this->behavior_chain->getBehaviors() as $alias => $behavior) {
            $behaviors[$alias] = $behavior->getName();
        }

        return $behaviors;
    }
}