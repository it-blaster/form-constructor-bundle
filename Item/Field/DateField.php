<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Symfony\Component\Form\FormBuilderInterface;

class DateField extends DateTimeField
{
    public function getName()
    {
        return 'fc.label.date';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormPlaceholderField($builder);
        $this->buildFormDateFormatField($builder);
    }
}