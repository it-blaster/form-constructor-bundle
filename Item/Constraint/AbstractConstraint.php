<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Item\AbstractItem;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;

abstract class AbstractConstraint extends AbstractItem
{
    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {}

    public function getGroups(FcFieldConstraint $fc_constraint)
    {
        return array(
            $fc_constraint->getConstraint() .'_'. $fc_constraint->getFieldId()
        );
    }
}