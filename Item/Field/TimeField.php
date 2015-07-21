<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Symfony\Component\Form\FormBuilderInterface;

class TimeField extends DateTimeField
{
    public function getName()
    {
        return 'fc.label.time';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormPlaceholderField($builder);
        $this->buildFormTimeFormatField($builder);
    }
}