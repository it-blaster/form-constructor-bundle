<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcField;

class TextBuilder extends BaseBuilder
{
    public function getOptions()
    {
        $options = parent::getOptions();
        $params  = $this->fc_field->getParams();

        if ($params['placeholder']) {
            if (!isset($options['attr'])) {
                $options['attr'] = array();
            }

            $options['attr']['placeholder'] = $params['placeholder'];
        }

        return $options;
    }
}