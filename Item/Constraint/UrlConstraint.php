<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Validator\Constraints\Url;

class UrlConstraint extends AbstractConstraint
{
    protected $protocols = array('http', 'https');

    public function getName()
    {
        return 'fc.label.constraints.url';
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Url(array(
            'groups'    => $this->getGroups($fc_constraint),
            'message'   => $fc_constraint->getMessage(),
            'protocols' => $this->protocols
        ));
    }
}