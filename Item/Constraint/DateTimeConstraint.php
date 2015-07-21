<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Fenrizbes\FormConstructorBundle\Validator\Constraints\Date;

class DateTimeConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.datetime';
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Date(array(
            'message' => $fc_constraint->getMessage(),
            'format'  => $options['format']
        ));
    }
}