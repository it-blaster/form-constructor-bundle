<?php

namespace Fenrizbes\FormConstructorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\AbstractComparison;

class DateComparison extends AbstractComparison
{
    public $format;
    public $type;
}
