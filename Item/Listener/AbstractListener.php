<?php

namespace Fenrizbes\FormConstructorBundle\Item\Listener;

use Fenrizbes\FormConstructorBundle\Item\AbstractItem;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

abstract class AbstractListener extends AbstractItem
{
    public function buildListener(FormBuilderInterface $builder, FcFormEventListener $fc_listener)
    {
        $builder->addEventListener(
            $this->buildEventName($fc_listener),
            $this->buildEventHandler($fc_listener),
            $this->buildListenerPriority($fc_listener)
        );
    }

    protected function buildEventName(FcFormEventListener $fc_listener)
    {
        return FormEvents::POST_SUBMIT;
    }

    protected function buildEventHandler(FcFormEventListener $fc_listener)
    {
        return function() {};
    }

    protected function buildListenerPriority(FcFormEventListener $fc_listener)
    {
        return 0;
    }
}