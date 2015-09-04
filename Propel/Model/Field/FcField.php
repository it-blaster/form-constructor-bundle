<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\om\BaseFcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;

class FcField extends BaseFcField
{
    /**
     * @var FcFieldConstraint[]
     */
    protected $constraints;

    protected $custom_widget = false;
    protected $insert_rank   = null;
    protected $step          = 1;

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
        if (is_null($this->constraints)) {
            $this->constraints = array();

            foreach ($this->getFcForm()->getConstraints() as $fc_constraint) {
                if ($fc_constraint->getFieldId() == $this->getId()) {
                    $this->constraints[] = $fc_constraint;
                }
            }
        }

        return $this->constraints;
    }

    public function setStep($step)
    {
        $this->step = $step;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function setInsertRank($insert_rank)
    {
        $this->insert_rank = $insert_rank;
    }

    public function getInsertRank()
    {
        return $this->insert_rank;
    }
}
