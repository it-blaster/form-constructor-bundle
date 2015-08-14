<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Condition;

use Fenrizbes\FormConstructorBundle\Item\Behavior\AbstractBehaviorItem;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition;

abstract class AbstractBehaviorCondition extends AbstractBehaviorItem
{
    abstract public function check(FcFormBehaviorCondition $condition, array $data);
}