<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Behavior;

use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\om\BaseFcFormBehavior;

class FcFormBehavior extends BaseFcFormBehavior
{
    public function getThenActions()
    {
        return FcFormBehaviorActionQuery::create()
            ->filterByFcFormBehavior($this)
            ->filterByCheck(true)
            ->find()
        ;
    }

    public function getElseActions()
    {
        return FcFormBehaviorActionQuery::create()
            ->filterByFcFormBehavior($this)
            ->filterByCheck(false)
            ->find()
        ;
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
