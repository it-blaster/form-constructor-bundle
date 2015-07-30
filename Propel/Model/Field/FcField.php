<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\om\BaseFcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;

class FcField extends BaseFcField
{
    protected $custom_widget = false;
    protected $step = 1;

    public function __toString()
    {
        if ($this->isCustom()) {
            return $this->getCustomWidget()->getTitle();
        }

        if (!is_null($this->getLabel())) {
            return $this->getLabel();
        }

        return $this->getName();
    }

    public function isCustom()
    {
        return is_null($this->getName()) && $this->getCustomWidget() instanceof FcForm;
    }

    /**
     * @return FcForm
     */
    public function getCustomWidget()
    {
        if ($this->custom_widget === false) {
            $this->custom_widget = FcFormQuery::create()
                ->filterByAlias($this->getType())
                ->filterByIsWidget(true)
                ->findOne()
            ;
        }

        return $this->custom_widget;
    }

    public function getConstraints()
    {
        return FcFieldConstraintQuery::create()
            ->filterByFcField($this)
            ->filterByIsActive(true)
            ->find()
        ;
    }

    public function setStep($step)
    {
        $this->step = $step;
    }

    public function getStep()
    {
        return $this->step;
    }
}
