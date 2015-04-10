<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFieldConstraint;

use Symfony\Component\Validator\Constraints\NotBlank;

class NotBlankBuilder extends BaseBuilder
{
    public function addConstraint(&$field_options)
    {
        $field_options['required'] = true;

        $field_options['constraints'][] = new NotBlank(array(
            'message' => $this->fc_constraint->getMessage()
        ));
    }
}