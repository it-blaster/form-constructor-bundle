<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFieldConstraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;

interface ConstraintBuilderInterface
{
    public function __construct(FcFieldConstraint $fc_constraint);

    public function addConstraint(&$field_options);
}