<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Fenrizbes\FormConstructorBundle\Validator\Constraints\Date;
use Fenrizbes\FormConstructorBundle\Validator\Constraints\DateComparison;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DateComparisonConstraint extends ComparisonConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.date_comparison';
    }

    public function buildFormValueField(FormBuilderInterface $builder)
    {
        $builder->add('value', 'text', array(
            'label'       => 'fc.label.admin.constraint.value',
            'required'    => true,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                )),
                new Date(array(
                    'format'  => 'Y-m-d',
                    'message' => 'fc.constraint.admin.invalid_date'
                ))
            )
        ));
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $params = $fc_constraint->getParams();

        $options['constraints'][] = new DateComparison(array(
            'value'   => $params['value'],
            'message' => $fc_constraint->getMessage(),
            'type'    => $this->constraints[$params['type']],
            'format'  => $options['format']
        ));
    }
}