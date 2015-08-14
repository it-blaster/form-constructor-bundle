<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Condition;

use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition;
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

    public function getComparisonLabel($key)
    {
        return $this->comparison_choices[$key];
    }

    public function check(FcFormBehaviorCondition $condition, array $data)
    {
        $params      = $condition->getParams();
        $data_value  = (isset($data[ $params['field'] ]) ? (string) $data[ $params['field'] ] : '');
        $check_value = (string) $params['value'];

        if (preg_match('/^-?(\d+\.)?\d+$/', $params['value'])) {
            $data_value  = (float) $data_value;
            $check_value = (float) $check_value;
        }

        switch ($params['comparison']) {
            case 'not_equal':        return $data_value !== $check_value;
            case 'greater':          return $data_value > $check_value;
            case 'greater_or_equal': return $data_value >= $check_value;
            case 'less':             return $data_value < $check_value;
            case 'less_or_equal':    return $data_value <= $check_value;
        }

        return $data_value === $check_value;
    }
}