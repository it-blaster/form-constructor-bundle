<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Behavior;

use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\om\BaseFcFormBehavior;

class FcFormBehavior extends BaseFcFormBehavior
{
    protected $conditions;
    protected $actions = array();

    public function getThenActions($all = false)
    {
        if (!isset($actions['then'])) {
            $actions['then'] = $this->getActionsByCheck(true, $all);
        }

        return $actions['then'];
    }

    public function getElseActions($all = false)
    {
        if (!isset($actions['else'])) {
            $actions['else'] = $this->getActionsByCheck(false, $all);
        }

        return $actions['else'];
    }

    protected function getActionsByCheck($check, $all = false)
    {
        return FcFormBehaviorActionQuery::create()
            ->filterByFcFormBehavior($this)
            ->filterByCheck($check)
            ->_if(!$all)
                ->filterByIsActive(true)
            ->_endif()
            ->find()
        ;
    }

    public function getConditions()
    {
        if (is_null($this->conditions)) {
            $this->conditions = FcFormBehaviorConditionQuery::create()
                ->filterByFcFormBehavior($this)
                ->filterByIsActive(true)
                ->find()
            ;
        }

        return $this->conditions;
    }

    public function isFirstCondition($condition = null)
    {
        $conditions = $this->getFcFormBehaviorConditions();
        if (!count($conditions)) {
            return true;
        }

        /** @var FcFormBehaviorCondition $first_condition */
        $first_condition = $conditions->getFirst();
        if ($first_condition->isNew()) {
            return true;
        }

        if ($condition instanceof FcFormBehaviorCondition && !$condition->isNew()) {
            return $condition->getId() == $first_condition->getId();
        }

        return false;
    }
}
