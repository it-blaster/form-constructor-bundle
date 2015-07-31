<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Behavior\AbstractBehavior;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

class BehaviorChain
{
    /**
     * @var AbstractBehavior[]
     */
    private $behaviors = array();

    /**
     * @param AbstractBehavior $behavior
     * @param string $alias
     */
    public function addBehavior(AbstractBehavior $behavior, $alias)
    {
        $this->behaviors[$alias] = $behavior;
    }

    /**
     * @param string $alias
     * @return AbstractBehavior
     * @throws \Exception
     */
    public function getBehavior($alias)
    {
        if (!isset($this->behaviors[$alias])) {
            throw new \Exception('Behavior "'. $alias .'" not found');
        }

        return $this->behaviors[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasBehavior($alias)
    {
        return isset($this->behaviors[$alias]);
    }

    /**
     * @return AbstractBehavior[]
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @param string $alias
     * @param FcForm $fc_form
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias, FcForm $fc_form)
    {
        return new ParamsBuilder(
            $this->getBehavior($alias),
            $fc_form
        );
    }
}