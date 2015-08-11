<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Request;

use Fenrizbes\FormConstructorBundle\Propel\Model\Request\om\BaseFcRequest;

class FcRequest extends BaseFcRequest
{
    public function __toString()
    {
        return '#'. $this->getId();
    }
}
