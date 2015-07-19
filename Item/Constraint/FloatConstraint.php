<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

class FloatConstraint extends RegexConstraint
{
    protected $pattern = '/^-?(\d+\.)?\d+$/';

    public function getName()
    {
        return 'fc.label.constraints.float';
    }
}