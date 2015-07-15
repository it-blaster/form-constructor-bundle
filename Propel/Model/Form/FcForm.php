<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Form;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\om\BaseFcForm;

class FcForm extends BaseFcForm
{
    protected $entrances;
    protected $is_used_as_widget;

    /**
     * @param bool $all
     * @return FcField[]
     */
    public function getFields($all = false)
    {
        return FcFieldQuery::create()
            ->filterByFcForm($this)
            ->_if(!$all)
                ->filterByIsActive(true)
            ->_endif()
            ->orderByRank()
            ->find()
        ;
    }

    public function getEntrances()
    {
        if (is_null($this->entrances)) {
            $this->entrances = FcFieldQuery::create()
                ->filterByType($this->getAlias())
                ->find();
        }

        return $this->entrances;
    }

    public function isUsedAsWidget()
    {
        if (is_null($this->is_used_as_widget)) {
            $this->is_used_as_widget = (bool)$this->getEntrances()->count();
        }

        return $this->is_used_as_widget;
    }

    public function getListeners($all = false)
    {
        return FcFormEventListenerQuery::create()
            ->filterByFcForm($this)
            ->_if(!$all)
                ->filterByIsActive(true)
            ->_endif()
            ->find();
    }
}
