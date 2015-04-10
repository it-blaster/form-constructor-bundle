<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFormListener;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;

interface ListenerBuilderInterface
{
    public function getEvent();

    public function getHandler(FcFormEventListener $fc_listener);

    public function getPriority();
}