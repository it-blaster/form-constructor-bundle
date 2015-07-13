<?php

namespace Fenrizbes\FormConstructorBundle\Item;

use Symfony\Component\Form\FormBuilderInterface;

interface ItemInterface
{
    public function getName();

    public function buildForm(FormBuilderInterface $builder, array $options);
}