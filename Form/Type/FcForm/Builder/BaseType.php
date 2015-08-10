<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Builder;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorActionChain;
use Fenrizbes\FormConstructorBundle\Chain\BehaviorConditionChain;
use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Chain\ListenerChain;
use Fenrizbes\FormConstructorBundle\Chain\TemplateChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class BaseType extends AbstractType
{
    /**
     * @var FcForm
     */
    protected $fc_form;

    /**
     * @var Router
     */
    protected $router;

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
    protected $behavior_condition_chain;

    /**
     * @var BehaviorActionChain
     */
    protected $behavior_action_chain;

    protected $options;

    public function __construct(FcForm $fc_form, $options)
    {
        $this->fc_form = $fc_form;
        $this->options = $options;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
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
        $this->behavior_condition_chain = $behavior_condition_chain;
    }

    public function setBehaviorActionChain(BehaviorActionChain $behavior_action_chain)
    {
        $this->behavior_action_chain = $behavior_action_chain;
    }

    public function getName()
    {
        return $this->fc_form->getAlias();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'attr'               => array(
                'data-async' => $this->fc_form->getIsAjax()
            )
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->fc_form->getFieldsRecursively() as $fc_field) {
            $this->addField($builder, $fc_field);
        }

        $action = $this->fc_form->getAction();
        if ($this->router->getRouteCollection()->get($action) !== null) {
            $action = $this->router->generate($action);
        }

        if ($this->fc_form->getIsAjax()) {
            if (empty($action)) {
                $action = $this->router->generate('fc_from_ajax_handler', array(
                    'alias' => $this->fc_form->getAlias()
                ));
            }
        }

        $builder
            ->add('submit', 'submit', array(
                'label' => ($this->fc_form->getButton() ? $this->fc_form->getButton() : 'fc.label.button')
            ))
            ->add('_template', 'hidden')
            ->setMethod($this->fc_form->getMethod())
            ->setAction($action)
        ;

        foreach ($this->fc_form->getListeners() as $fc_listener) {
            $this->addListener($builder, $fc_listener);
        }
    }

    protected function addField(FormBuilderInterface $builder, FcField $fc_field)
    {
        $field = $this->field_chain->getField($fc_field->getType());
        $field->buildField($this->constraint_chain, $builder, $fc_field);
    }

    protected function addListener(FormBuilderInterface $builder, FcFormEventListener $fc_listener)
    {
        $listener = $this->listener_chain->getListener($fc_listener->getListener());
        $listener->buildListener($builder, $fc_listener);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($this->options, $view->vars);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $this->injectFcForm($view);
    }

    protected function injectFcForm(FormView $view)
    {
        $view->vars['fc_form'] = $this->fc_form;

        foreach ($view->children as $child) {
            $this->injectFcForm($child);
        }
    }
}