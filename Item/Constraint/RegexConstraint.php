<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Validator\Constraints\Regex;

class RegexConstraint extends AbstractConstraint
{
    protected $pattern = '/.*/';
    protected $match   = true;

    public function getName()
    {
        return 'fc.label.constraints.regex';
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Regex(array(
            'pattern' => $this->pattern,
            'match'   => $this->match,
            'message' => $fc_constraint->getMessage()
        ));
    }
}