<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Form;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\om\BaseFcForm;

class FcForm extends BaseFcForm
{
    protected $entrances;
    protected $is_used_as_widget;
    protected $fields;
    protected $steps_count;

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

    public function getStepsCount()
    {
        if (is_null($this->steps_count)) {
            $this->handleFields($this);
        }

        return $this->steps_count;
    }

    /**
     * @return FcField[]
     */
    public function getFieldsRecursively()
    {
        if (is_null($this->fields)) {
            $this->handleFields($this);
        }

        return $this->fields;
    }

    protected function handleFields(FcForm $fc_form)
    {
        if (is_null($this->steps_count)) {
            $this->steps_count = 1;
        }

        foreach ($fc_form->getFields() as $fc_field) {
            if ($fc_field->isCustom()) {
                $this->handleFields($fc_field->getCustomWidget());
            } else {
                $fc_field->setStep($this->steps_count);
                $this->fields[] = $fc_field;

                if ('step' == $fc_field->getType()) {
                    $this->steps_count++;
                }
            }
        }
    }

    public function getStepNumber($field_name)
    {
        foreach ($this->getFieldsRecursively() as $fc_field) {
            if ($fc_field->getName() == $field_name) {
                return $fc_field->getStep();
            }
        }

        return 1;
    }

    public function getStepField($step)
    {
        foreach ($this->getFieldsRecursively() as $fc_field) {
            if ('step' == $fc_field->getType() && $fc_field->getStep() == $step) {
                return $fc_field;
            }
        }

        return 1;
    }
}
