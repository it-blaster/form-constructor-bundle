<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Request;

use Fenrizbes\FormConstructorBundle\Propel\Model\Request\om\BaseFcRequest;

class FcRequest extends BaseFcRequest
{
    protected $handled_data;

    public function __toString()
    {
        return '#'. $this->getId();
    }

    public function __isset($name)
    {
        if (preg_match('/^Data_([\w\-]+)$/', $name, $matches)) {
            return $this->issetInData($matches[1]);
        }

        return false;
    }

    public function __get($name)
    {
        if (preg_match('/^Data_([\w\-]+)$/', $name, $matches)) {
            if ($this->issetInData($matches[1])) {
                return $this->handled_data[ $matches[1] ]['value'];
            }
        }

        throw new \PropelException('Call to undefined property: '. $name);
    }

    public function issetInData($name)
    {
        if (is_null($this->handled_data)) {
            $this->handled_data = array();

            foreach ($this->getData() as $item) {
                $this->handled_data[ $item['name'] ] = $item;
            }
        }

        return isset($this->handled_data[$name]);
    }

    public function __call($name, $params)
    {
        $result = null;

        try {
            $result = parent::__call($name, $params);
        } catch (\Exception $e) {
            if (preg_match('/Data_(\w+)/', $name, $matches)) {
                $result = $this->__get($name);
            }
        }

        return $result;
    }
}
