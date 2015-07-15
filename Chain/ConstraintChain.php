<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Constraint\AbstractConstraint;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

class ConstraintChain
{
    /**
     * @var AbstractConstraint[]
     */
    private $constraints = array();

    /**
     * @param AbstractConstraint $constraint
     * @param string $alias
     */
    public function addConstraint(AbstractConstraint $constraint, $alias)
    {
        $this->constraints[$alias] = $constraint;
    }

    /**
     * @param string $alias
     * @return AbstractConstraint
     * @throws \Exception
     */
    public function getConstraint($alias)
    {
        if (!isset($this->constraints[$alias])) {
            throw new \Exception('Constraint "'. $alias .'" not found');
        }

        return $this->constraints[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasConstraint($alias)
    {
        return isset($this->constraints[$alias]);
    }

    /**
     * @return AbstractConstraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param string $alias
     * @param FcForm $fc_form
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias, FcForm $fc_form)
    {
        return new ParamsBuilder(
            $this->getConstraint($alias),
            $fc_form
        );
    }
}