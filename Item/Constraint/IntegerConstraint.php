<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

class IntegerConstraint extends RegexConstraint
{
    protected $pattern = '/^-?\d+$/';

    public function getName()
    {
        return 'fc.label.constraints.integer';
    }
}