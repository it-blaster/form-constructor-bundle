<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFieldConstraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;

abstract class BaseBuilder implements ConstraintBuilderInterface
{
    /**
     * @var FcFieldConstraint
     */
    protected $fc_constraint;

    public function __construct(FcFieldConstraint $fc_constraint)
    {
        $this->fc_constraint = $fc_constraint;
    }
}