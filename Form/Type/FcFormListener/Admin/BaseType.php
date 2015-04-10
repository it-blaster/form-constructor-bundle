<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener\Admin;

use Symfony\Component\Form\AbstractType;

class BaseType extends AbstractType
{
    public function getName()
    {
        return 'params';
    }
}