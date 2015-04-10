<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin;

use Symfony\Component\Form\AbstractType;

class BaseType extends AbstractType
{
    public function getName()
    {
        return 'params';
    }
}