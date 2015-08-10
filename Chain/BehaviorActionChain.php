<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Behavior\Action\AbstractBehaviorAction;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

class BehaviorActionChain
{
    /**
     * @var AbstractBehaviorAction[]
     */
    private $actions = array();

    /**
     * @param AbstractBehaviorAction $action
     * @param string $alias
     */
    public function addAction(AbstractBehaviorAction $action, $alias)
    {
        $this->actions[$alias] = $action;
    }

    /**
     * @param string $alias
     * @return AbstractBehaviorAction
     * @throws \Exception
     */
    public function getAction($alias)
    {
        if (!isset($this->actions[$alias])) {
            throw new \Exception('Action "'. $alias .'" not found');
        }

        return $this->actions[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasAction($alias)
    {
        return isset($this->actions[$alias]);
    }

    /**
     * @return AbstractBehaviorAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $alias
     * @param FcForm $fc_form
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias, FcForm $fc_form)
    {
        return new ParamsBuilder(
            $this->getAction($alias),
            $fc_form
        );
    }
}