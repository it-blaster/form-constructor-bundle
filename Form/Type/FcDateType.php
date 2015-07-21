<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

class FcDateType extends FcDateTimeType
{
    public function getName()
    {
        return 'fc_date';
    }
}