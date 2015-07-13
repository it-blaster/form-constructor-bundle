<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Listener\AbstractListener;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;

class ListenerChain
{
    /**
     * @var AbstractListener[]
     */
    private $listeners = array();

    /**
     * @param AbstractListener $listener
     * @param string $alias
     */
    public function addListener(AbstractListener $listener, $alias)
    {
        $this->listeners[$alias] = $listener;
    }

    /**
     * @param string $alias
     * @return AbstractListener
     * @throws \Exception
     */
    public function getListener($alias)
    {
        if (!isset($this->listeners[$alias])) {
            throw new \Exception('Listener "'. $alias .'" not found');
        }

        return $this->listeners[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasListener($alias)
    {
        return isset($this->listeners[$alias]);
    }

    /**
     * @return AbstractListener[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param string $alias
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias)
    {
        return new ParamsBuilder(
            $this->getListener($alias)
        );
    }
}