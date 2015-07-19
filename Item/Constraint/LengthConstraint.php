<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class LengthConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.length';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min', 'text', array(
                'label'       => 'fc.label.admin.constraint.min',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('max', 'text', array(
                'label'       => 'fc.label.admin.constraint.max',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
        ;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Length(
            $this->buildConstraintOptions($fc_constraint)
        );
    }

    protected function buildConstraintOptions(FcFieldConstraint $fc_constraint)
    {
        $constraint_options = array(
            'minMessage'   => $fc_constraint->getMessage(),
            'maxMessage'   => $fc_constraint->getMessage(),
            'exactMessage' => $fc_constraint->getMessage()
        );

        $params = $fc_constraint->getParams();

        if ($params['min']) {
            $constraint_options['min'] = $params['min'];
        }

        if ($params['max']) {
            $constraint_options['max'] = $params['max'];
        }

        return $constraint_options;
    }
}