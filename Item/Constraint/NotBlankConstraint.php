<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class NotBlankConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.not_blank';
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['required'] = true;

        $options['constraints'][] = new NotBlank(array(
            'groups'  => $this->getGroups($fc_constraint),
            'message' => $fc_constraint->getMessage()
        ));
    }
}