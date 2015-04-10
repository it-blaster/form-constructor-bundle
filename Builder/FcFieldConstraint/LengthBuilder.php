<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFieldConstraint;

use Symfony\Component\Validator\Constraints\Length;

class LengthBuilder extends BaseBuilder
{
    public function addConstraint(&$field_options)
    {
        $constraint_options = array(
            'minMessage'   => $this->fc_constraint->getMessage(),
            'maxMessage'   => $this->fc_constraint->getMessage(),
            'exactMessage' => $this->fc_constraint->getMessage()
        );

        $params = $this->fc_constraint->getParams();

        if ($params['min']) {
            $constraint_options['min'] = $params['min'];
        }

        if ($params['max']) {
            $constraint_options['max'] = $params['max'];
        }

        $field_options['constraints'][] = new Length($constraint_options);
    }
}