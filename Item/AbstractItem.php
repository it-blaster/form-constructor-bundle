<?php

namespace Fenrizbes\FormConstructorBundle\Item;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractItem implements ItemInterface
{
    /**
     * @var FcForm
     */
    protected $fc_form;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {}

    public function setFcForm(FcForm $fc_form)
    {
        $this->fc_form = $fc_form;
    }

    public function getFcForm()
    {
        return $this->fc_form;
    }
}