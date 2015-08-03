<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Form;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\om\BaseFcForm;

class FcForm extends BaseFcForm
{
    protected $entrances;
    protected $is_used_as_widget;
    protected $steps_count;
    protected $positions = array();

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

    public function getStepsCount()
    {
        if (is_null($this->steps_count)) {
            $this->getFieldsRecursively();
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
                $this->fields[$fc_field->getName()] = $fc_field;

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
        $templates = array();

        foreach ($this->getTemplates() as $fc_template) {
            $params = $fc_template->getParams();

            if (in_array($field_name, $params['fields'])) {
                $templates[$fc_template->getId()] = $fc_template->getTemplate();
            }
        }

        return $templates;
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
            $this->positions[$id][$name] = array(
                'position' => 0,
                'is_first' => false,
                'is_last'  => false
            );

            if (in_array($name, $params['fields'])) {
                if (1 == $index) {
                    $this->positions[$id][$name]['is_first'] = true;
                }

                $this->positions[$id][$name]['position'] = $index++;
            } else {
                if (null !== $prev) {
                    $this->positions[$id][$prev]['is_last'] = true;
                }

                $index = 1;
            }

            $prev = $name;
        }
    }

    public function getInTemplatePosition($template, $field_name)
    {
        $id = $this->getFieldTemplateId($template, $field_name);

        $this->calcTemplatePositions($id);

        if (!isset($this->positions[$id][$field_name])) {
            return false;
        }

        return $this->positions[$id][$field_name]['position'];
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
}
