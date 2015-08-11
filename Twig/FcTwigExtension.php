<?php

namespace Fenrizbes\FormConstructorBundle\Twig;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorActionChain;
use Fenrizbes\FormConstructorBundle\Chain\BehaviorConditionChain;
use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Chain\ListenerChain;
use Fenrizbes\FormConstructorBundle\Chain\TemplateChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Service\FormService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormInterface;

class FcTwigExtension extends \Twig_Extension
{
    /**
     * @var FormService
     */
    protected $form_service;

    /**
     * @var FieldChain
     */
    protected $field_chain;

    /**
     * @var ConstraintChain
     */
    protected $constraint_chain;

    /**
     * @var ListenerChain
     */
    protected $listener_chain;

    /**
     * @var TemplateChain
     */
    protected $template_chain;

    /**
     * @var BehaviorConditionChain
     */
    protected $condition_chain;

    /**
     * @var BehaviorActionChain
     */
    protected $action_chain;

    public function getName()
    {
        return 'FcTwigExtension';
    }

    public function setFormService(FormService $form_service)
    {
        $this->form_service = $form_service;
    }

    public function setFieldChain(FieldChain $field_chain)
    {
        $this->field_chain = $field_chain;
    }

    public function setConstraintChain(ConstraintChain $constraint_chain)
    {
        $this->constraint_chain = $constraint_chain;
    }

    public function setListenerChain(ListenerChain $listener_chain)
    {
        $this->listener_chain = $listener_chain;
    }

    public function setTemplateChain(TemplateChain $template_chain)
    {
        $this->template_chain = $template_chain;
    }

    public function setBehaviorConditionChain(BehaviorConditionChain $behavior_condition_chain)
    {
        $this->condition_chain = $behavior_condition_chain;
    }

    public function setBehaviorActionChain(BehaviorActionChain $behavior_action_chain)
    {
        $this->action_chain = $behavior_action_chain;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fc_field',      array($this, 'getFcField')),
            new \Twig_SimpleFilter('fc_constraint', array($this, 'getFcConstraint')),
            new \Twig_SimpleFilter('fc_listener',   array($this, 'getFcListener')),
            new \Twig_SimpleFilter('fc_template',   array($this, 'getFcTemplate')),
            new \Twig_SimpleFilter('fc_condition',  array($this, 'getFcBehaviorCondition')),
            new \Twig_SimpleFilter('fc_action',     array($this, 'getFcBehaviorAction')),
            new \Twig_SimpleFilter('fc_value',      array($this, 'getFcValue'))
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fc_item', array($this, 'getFcItem')),
            new \Twig_SimpleFunction('fc_view', array($this, 'getFcFormView')),
            new \Twig_SimpleFunction('fc_form', array($this, 'renderFcForm'), array(
                'needs_environment' => TRUE,
                'is_safe'           => array('html')
            ))
        );
    }

    public function getFcField($alias)
    {
        return $this->field_chain->getField($alias)->getName();
    }

    public function getFcConstraint($alias)
    {
        return $this->constraint_chain->getConstraint($alias)->getName();
    }

    public function getFcListener($alias)
    {
        return $this->listener_chain->getListener($alias)->getName();
    }

    public function getFcTemplate($alias)
    {
        return $this->template_chain->getTemplate($alias)->getName();
    }

    public function getFcBehaviorCondition($alias)
    {
        return $this->condition_chain->getCondition($alias)->getName();
    }

    public function getFcBehaviorAction($alias)
    {
        return $this->action_chain->getAction($alias)->getName();
    }

    public function getFcValue($value, FcField $fc_field)
    {
        if (null === $value || '' === $value) {
            return $value;
        }

        return $this->field_chain
            ->getField($fc_field->getType())
            ->verboseValue($value, $fc_field)
        ;
    }

    public function getFcItem($type, $alias)
    {
        $chain = $type .'_chain';
        if (!property_exists($this, $chain)) {
            throw new \Exception('Type "'. $type .'" does not exist');
        }

        $method = 'get'. Container::camelize($type);

        return $this->$chain->$method($alias);
    }

    public function getFcFormView($alias, $options = array())
    {
        /** @var FormInterface $form */
        $form = $this->form_service->create($alias, $options);
        if (is_null($form)) {
            return null;
        }

        return $form->createView();
    }

    public function renderFcForm(\Twig_Environment $environment, $alias, $options = array())
    {
        $options = $this->form_service->buildOptions($options);

        if (!is_string($options['template'])) {
            throw new \Exception('Invalid template\'s name');
        }

        return $environment->render($options['template'], array(
            'form' => $this->getFcFormView($alias, $options)
        ));
    }
}
