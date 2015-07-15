<?php

namespace Fenrizbes\FormConstructorBundle\Item;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\FormBuilderInterface;

interface ItemInterface
{
    public function getName();

    public function setFcForm(FcForm $fc_form);

    public function getFcForm();

    public function buildForm(FormBuilderInterface $builder, array $options);
}