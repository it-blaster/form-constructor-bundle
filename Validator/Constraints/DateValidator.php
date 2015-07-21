<?php

namespace Fenrizbes\FormConstructorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value || $value instanceof \DateTime) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        $date  = \DateTime::createFromFormat($constraint->format, $value);

        if (!$date || $date->format($constraint->format) != $value) {
            $this->context->addViolation($constraint->message, array(
                '{{ value }}' => $value
            ));
        }
    }
}