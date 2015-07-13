<?php

namespace Fenrizbes\FormConstructorBundle\Item;

use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractItem implements ItemInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {}
}