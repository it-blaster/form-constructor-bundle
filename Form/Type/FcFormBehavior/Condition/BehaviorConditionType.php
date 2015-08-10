<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormBehavior\Condition;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorConditionChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BehaviorConditionType extends AbstractType
{
    /**
     * @var BehaviorConditionChain
     */
    protected $condition_chain;

    protected $action;

    public function __construct(BehaviorConditionChain $condition_chain, $action = null)
    {
        $this->condition_chain = $condition_chain;
        $this->action          = $action;
    }

    public function getName()
    {
        return 'fc_field_condition';
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
            ->add('condition', 'choice', array(
                'label'       => 'fc.label.admin.behavior.condition',
                'choices'     => $this->buildTemplateChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildTemplateChoices()
    {
        $conditions = array();

        foreach ($this->condition_chain->getConditions() as $alias => $condition) {
            $conditions[$alias] = $condition->getName();
        }

        return $conditions;
    }
}