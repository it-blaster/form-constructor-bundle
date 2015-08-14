<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Form;

use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehavior;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraintQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\om\BaseFcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestQuery;

class FcForm extends BaseFcForm
{
    protected $entrances;
    protected $is_used_as_widget;
    protected $steps_count;
    protected $positions = array();
    protected $fields_templates = array();

    /**
     * @var FcFieldConstraint[]
     */
    protected $constraints;

    /**
     * @var FcFormBehavior[]
     */
    protected $behaviors;

    /**
     * @var FcField[]
     */
    protected $fields;

    /**
     * @var FcFormTemplate[]
     */
    protected $templates;

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

    public function getConstraints()
    {
        if (is_null($this->constraints)) {
            $fields = array();
            foreach ($this->getFieldsRecursively() as $fc_field) {
                $fields[] = $fc_field->getId();
            }

            $this->constraints = FcFieldConstraintQuery::create()
                ->filterByFieldId($fields)
                ->find()
            ;
        }

        return $this->constraints;
    }

    public function getStepsCount()
    {
        if (is_null($this->steps_count)) {
            $this->getFieldsRecursively();
        }

        return $this->steps_count;
    }

    /**
     * @param bool $all
     * @return FcField[]
     */
    public function getFieldsRecursively($all = false)
    {
        if (is_null($this->fields)) {
            $this->fields = array();

            $this->handleFields($this, $all);
        }

        return $this->fields;
    }

    protected function handleFields(FcForm $fc_form, $all = false)
    {
        if (is_null($this->steps_count)) {
            $this->steps_count = 1;
        }

        foreach ($fc_form->getFields($all) as $fc_field) {
            if ($fc_field->isCustom()) {
                $this->handleFields($fc_field->getCustomWidget(), $all);
            } else {
                if ('hidden' == $fc_field->getType()) {
                    $fc_field->setStep(1);
                    $this->fields = array($fc_field->getName() => $fc_field) + $this->fields;
                } else {
                    $fc_field->setStep($this->steps_count);
                    $this->fields[$fc_field->getName()] = $fc_field;
                }

                if ('step' == $fc_field->getType()) {
                    $this->steps_count++;
                }
            }
        }
    }

    public function getStepNumber($field_name)
    {
        $fc_field = $this->getFieldByName($field_name);
        if (!is_null($fc_field)) {
            return $fc_field->getStep();
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

    public function getFieldByName($name)
    {
        $this->getFieldsRecursively();

        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        return null;
    }

    public function getTemplates($all = false)
    {
        if (is_null($this->templates)) {
            $this->templates = array();

            /** @var FcFormTemplate[] $templates */
            $templates = FcFormTemplateQuery::create()
                ->filterByFcForm($this)
                ->_if(!$all)
                    ->filterByIsActive(true)
                ->_endif()
                ->find()
            ;

            foreach ($templates as $template) {
                $this->templates[$template->getId()] = $template;
            }
        }

        return $this->templates;
    }

    public function getFieldTemplates($field_name)
    {
        if (!isset($this->fields_templates[$field_name])) {
            $this->fields_templates[$field_name] = array();

            foreach ($this->getTemplates() as $fc_template) {
                $params = $fc_template->getParams();

                if (in_array($field_name, $params['fields'])) {
                    $this->fields_templates[$field_name][$fc_template->getId()] = $fc_template->getTemplate();
                }
            }
        }

        return $this->fields_templates[$field_name];
    }

    protected function getFieldTemplateId($template, $field_name)
    {
        foreach ($this->getFieldTemplates($field_name) as $id => $name) {
            if ($name == $template) {
                return $id;
            }
        }

        return null;
    }

    public function getFieldTemplate($template, $field_name)
    {
        foreach ($this->getFieldTemplates($field_name) as $id => $name) {
            if ($name == $template) {
                return $this->templates[$id];
            }
        }

        return null;
    }

    protected function calcTemplatePositions($id)
    {
        if (isset($this->positions[$id])) {
            return;
        }

        $this->positions[$id] = array();

        if (!isset($this->templates[$id])) {
            return;
        }

        $params = $this->templates[$id]->getParams();
        $index  = 1;
        $prev   = null;

        foreach ($this->getFieldsRecursively() as $name => $fc_field) {
            if (in_array($name, $params['fields'])) {
                $this->positions[$id][$name] = array(
                    'position' => 0,
                    'is_first' => false,
                    'is_last'  => false
                );

                if (1 == $index) {
                    $this->positions[$id][$name]['is_first'] = true;
                }

                $this->positions[$id][$name]['position'] = $index++;

                $prev = $name;
            } else {
                if (null !== $prev) {
                    $this->positions[$id][$prev]['is_last'] = true;
                    $prev = null;
                }

                $index = 1;
            }
        }
    }

    public function getInTemplatePosition($template, $field_name)
    {
        $id = $this->getFieldTemplateId($template, $field_name);
        $this->calcTemplatePositions($id);

        if (!isset($this->positions[$id][$field_name])) {
            return null;
        }

        return $this->positions[$id][$field_name]['position'];
    }

    public function increaseInTemplatePosition($template, $field_name)
    {
        $id       = $this->getFieldTemplateId($template, $field_name);
        $position = $this->getInTemplatePosition($template, $field_name);
        $skip     = true;

        if (null === $id || null === $position) {
            return;
        }

        foreach ($this->positions[$id] as $name => &$data) {
            if ($name == $field_name) {
                $skip = false;
            }

            if ($skip) {
                continue;
            }

            if ($data['position'] >= $position) {
                $data['position']++;
            }

            if ($data['is_last']) {
                return;
            }
        }
    }

    public function getIsFirstInTemplate($template, $field_name)
    {
        $id = $this->getFieldTemplateId($template, $field_name);
        $this->calcTemplatePositions($id);

        if (!isset($this->positions[$id][$field_name])) {
            return null;
        }

        return $this->positions[$id][$field_name]['is_first'];
    }

    public function getIsLastInTemplate($template, $field_name)
    {
        $id = $this->getFieldTemplateId($template, $field_name);
        $this->calcTemplatePositions($id);

        if (!isset($this->positions[$id][$field_name])) {
            return null;
        }

        return $this->positions[$id][$field_name]['is_last'];
    }

    public function getCountTodayRequests()
    {
        return FcRequestQuery::create()
            ->filterByFcForm($this)
            ->filterByCreatedAt(array(
                'min' => date('Y-m-d 00:00:00'),
                'max' => date('Y-m-d 23:23:59')
            ))
            ->count()
        ;
    }

    public function getBehaviors()
    {
        if (is_null($this->behaviors)) {
            $this->behaviors = FcFormBehaviorQuery::create()
                ->filterByFcForm($this)
                ->filterByIsActive(true)
                ->find()
            ;
        }

        return $this->behaviors;
    }
}
