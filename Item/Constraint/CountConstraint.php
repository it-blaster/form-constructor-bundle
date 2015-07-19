<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Validator\Constraints\Count;

class CountConstraint extends LengthConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.count';
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Count(
            $this->buildConstraintOptions($fc_constraint)
        );
    }
}