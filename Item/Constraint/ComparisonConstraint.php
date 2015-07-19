<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ComparisonConstraint extends AbstractConstraint
{
    protected $constraints = array(
        'equal'            => '\Symfony\Component\Validator\Constraints\EqualTo',
        'not_equal'        => '\Symfony\Component\Validator\Constraints\NotEqualTo',
        'greater'          => '\Symfony\Component\Validator\Constraints\GreaterThan',
        'greater_or_equal' => '\Symfony\Component\Validator\Constraints\GreaterThanOrEqual',
        'less'             => '\Symfony\Component\Validator\Constraints\LessThan',
        'less_or_equal'    => '\Symfony\Component\Validator\Constraints\LessThanOrEqual'
    );

    public function getName()
    {
        return 'fc.label.constraints.comparison';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'label'    => 'fc.label.admin.constraint.comparison_type',
                'required' => true,
                'choices'  => array(
                    'equal'            => 'fc.label.admin.constraint.comparison_equal',
                    'not_equal'        => 'fc.label.admin.constraint.comparison_not_equal',
                    'greater'          => 'fc.label.admin.constraint.comparison_greater',
                    'greater_or_equal' => 'fc.label.admin.constraint.comparison_greater_or_equal',
                    'less'             => 'fc.label.admin.constraint.comparison_less',
                    'less_or_equal'    => 'fc.label.admin.constraint.comparison_less_or_equal'
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('value', 'text', array(
                'label'       => 'fc.label.admin.constraint.value',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
        ;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $params = $fc_constraint->getParams();

        $options['constraints'][] = new $this->constraints[$params['type']](array(
            'value'   => $params['value'],
            'message' => $fc_constraint->getMessage()
        ));
    }
}