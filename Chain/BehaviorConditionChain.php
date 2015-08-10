<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Behavior\Condition\AbstractBehaviorCondition;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

class BehaviorConditionChain
{
    /**
     * @var AbstractBehaviorCondition[]
     */
    private $conditions = array();

    /**
     * @param AbstractBehaviorCondition $condition
     * @param string $alias
     */
    public function addCondition(AbstractBehaviorCondition $condition, $alias)
    {
        $this->conditions[$alias] = $condition;
    }

    /**
     * @param string $alias
     * @return AbstractBehaviorCondition
     * @throws \Exception
     */
    public function getCondition($alias)
    {
        if (!isset($this->conditions[$alias])) {
            throw new \Exception('Condition "'. $alias .'" not found');
        }

        return $this->conditions[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasCondition($alias)
    {
        return isset($this->conditions[$alias]);
    }

    /**
     * @return AbstractBehaviorCondition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string $alias
     * @param FcForm $fc_form
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias, FcForm $fc_form)
    {
        return new ParamsBuilder(
            $this->getCondition($alias),
            $fc_form
        );
    }
}