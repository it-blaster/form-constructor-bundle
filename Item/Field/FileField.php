<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\HttpFoundation\File\File;

class FileField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.file';
    }

    public function verboseValue($value, FcField $fc_field)
    {
        if ($value instanceof File) {
            $value = $value->getFilename();
        }

        return $value;
    }
}