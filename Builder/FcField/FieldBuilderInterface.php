<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcField;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;

interface FieldBuilderInterface
{
    public function __construct(array $constraints, FcField $fc_field);

    public function getField();

    public function getType();

    public function getOptions();
}