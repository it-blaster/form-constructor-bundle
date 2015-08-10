<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Condition;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValueBehaviorCondition extends AbstractBehaviorCondition
{
    protected $comparison_choices = array(
        'equal'            => 'fc.label.behavior.condition_comparison.equal',
        'not_equal'        => 'fc.label.behavior.condition_comparison.not_equal',
        'greater'          => 'fc.label.behavior.condition_comparison.greater',
        'greater_or_equal' => 'fc.label.behavior.condition_comparison.greater_or_equal',
        'less'             => 'fc.label.behavior.condition_comparison.less',
        'less_or_equal'    => 'fc.label.behavior.condition_comparison.less_or_equal'
    );

    public function getName()
    {
        return 'fc.label.behavior.conditions.value';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('comparison', 'choice', array(
                'label'       => 'fc.label.behavior.condition_comparison',
                'required'    => true,
                'choices'     => $this->comparison_choices,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('value', 'text', array(
                'label'    => 'fc.label.admin.field.value',
                'required' => false
            ))
        ;
    }

    public function getComparison($key)
    {
        return $this->comparison_choices[$key];
    }
}