<?php

namespace Fenrizbes\FormConstructorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Date extends Constraint
{
    public $format;
    public $message;
}