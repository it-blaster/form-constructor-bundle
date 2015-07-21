<?php

namespace Fenrizbes\FormConstructorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;

class DateComparisonValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (is_string($value) && !empty($value)) {
            $value = \DateTime::createFromFormat($constraint->format, $value);
        }

        $class = $constraint->type .'Validator';

        /** @var AbstractComparisonValidator $validator */
        $validator = new $class();
        $validator->initialize($this->context);
        $validator->validate($value, $constraint);
    }
}