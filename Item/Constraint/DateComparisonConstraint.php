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
                ))
            )
        ));
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $params = $fc_constraint->getParams();

        try {
            $value = new \DateTime($params['value']);
            $limit = clone $value;
        } catch (\Exception $e) {
            return;
        }

        $options['constraints'][] = new DateComparison(array(
            'groups'  => $this->getGroups($fc_constraint),
            'value'   => $value,
            'message' => $fc_constraint->getMessage(),
            'type'    => $this->constraints[$params['type']],
            'format'  => $options['format']
        ));

        switch ($params['type']) {
            case 'greater':
                $limit->modify('+1 day');
            case 'greater_or_equal':
                $attr = 'data-min-date';
                break;

            case 'less':
                $limit->modify('-1 day');
            case 'less_or_equal':
                $attr = 'data-max-date';
                break;

            default:
                return;
        }

        if (!isset($options['attr'])) {
            $options['attr'] = array();
        }

        $options['attr'][$attr] = $limit->format($options['format']);
    }
}