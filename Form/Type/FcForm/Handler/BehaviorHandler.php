<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Handler;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorActionChain;
use Fenrizbes\FormConstructorBundle\Chain\BehaviorConditionChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehavior;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorAction;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\FormInterface;

class BehaviorHandler
{
    protected $validation_actions = array(
        'on_off_validation',
        'on_off_validator'
    );

    /**
     * @var BehaviorConditionChain
     */
    protected $condition_chain;

    /**
     * @var BehaviorActionChain
     */
    protected $action_chain;

    /**
     * @var FcForm
     */
    protected $fc_form;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var array
     */
    protected $data;

    protected $all_groups = array();
    protected $del_groups = array();

    public function __construct(BehaviorConditionChain $condition_chain, BehaviorActionChain $action_chain, FcForm $fc_form)
    {
        $this->condition_chain = $condition_chain;
        $this->action_chain    = $action_chain;
        $this->fc_form         = $fc_form;
    }

    public function handle(FormInterface $form)
    {
        $this->form = $form;
        $this->data = $form->getData();

        foreach ($this->fc_form->getConstraints() as $fc_constraint) {
            $this->all_groups[] = $fc_constraint->getConstraint() .'_'. $fc_constraint->getFieldId();
        }

        foreach ($this->fc_form->getBehaviors() as $behavior) {
            $this->doActions($behavior);
        }

        return array_diff($this->all_groups, $this->del_groups);
    }

    protected function doActions(FcFormBehavior $behavior)
    {
        $check = $this->checkBehavior($behavior);

        foreach ($behavior->getActionsByCheck($check) as $action) {
            if (in_array($action->getAction(), $this->validation_actions)) {
                $this->doAction($action);
            }
        }
    }

    protected function checkBehavior(FcFormBehavior $behavior)
    {
        $check = true;

        foreach ($behavior->getConditions() as $condition) {
            $condition_check = $this->checkCondition($condition);

            if ($condition->getOperator()) {
                $check = $check && $condition_check;
            } elseif (!$check) {
                $check = $condition_check;
            }
        }

        return $check;
    }

    protected function checkCondition(FcFormBehaviorCondition $condition)
    {
        if (!$this->condition_chain->hasCondition($condition->getCondition())) {
            return true;
        }

        return $this->condition_chain
            ->getCondition($condition->getCondition())
            ->check($condition, $this->data)
        ;
    }

    protected function doAction(FcFormBehaviorAction $action)
    {
        $params     = $action->getParams();
        $fields     = $params['field'];
        $validators = null;

        if ($params['action'] != 'disable') {
            return;
        }

        if (!is_array($fields)) {
            $fields = array($fields);
        }

        if ($action->getAction() == 'on_off_validator') {
            $validators = $params['validator'];
        }

        $this->disableValidators($fields, $validators);
    }

    protected function disableValidators(array $fields, $validators = null)
    {
        foreach ($fields as $field) {
            if (!$this->form->has($field)) {
                continue;
            }

            $fc_field = $this->fc_form->getFieldByName($field);
            if (!$fc_field instanceof FcField) {
                continue;
            }

            foreach ($fc_field->getConstraints() as $fc_constraint) {
                if (null === $validators || in_array($fc_constraint->getConstraint(), $validators)) {
                    $this->del_groups[] = $fc_constraint->getConstraint() .'_'. $fc_field->getId();
                }
            }
        }
    }
}