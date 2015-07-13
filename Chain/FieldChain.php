<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Field\AbstractField;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;

class FieldChain
{
    /**
     * @var AbstractField[]
     */
    private $fields = array();

    /**
     * @param AbstractField $field
     * @param string $alias
     */
    public function addField(AbstractField $field, $alias)
    {
        $this->fields[$alias] = $field;
    }

    /**
     * @param string $alias
     * @return AbstractField
     * @throws \Exception
     */
    public function getField($alias)
    {
        if (!isset($this->fields[$alias])) {
            throw new \Exception('Field "'. $alias .'" not found');
        }

        return $this->fields[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasField($alias)
    {
        return isset($this->fields[$alias]);
    }

    /**
     * @return AbstractField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $alias
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias)
    {
        return new ParamsBuilder(
            $this->getField($alias)
        );
    }
}