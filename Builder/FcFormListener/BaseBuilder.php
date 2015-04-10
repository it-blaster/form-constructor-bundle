<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFormListener;

use Symfony\Component\Form\FormEvents;

abstract class BaseBuilder implements ListenerBuilderInterface
{
    public function getEvent()
    {
        return FormEvents::POST_SUBMIT;
    }

    public function getPriority()
    {
        return 0;
    }
}