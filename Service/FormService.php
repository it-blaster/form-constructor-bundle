<?php

namespace Fenrizbes\FormConstructorBundle\Service;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorChain;
use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Chain\ListenerChain;
use Fenrizbes\FormConstructorBundle\Chain\TemplateChain;
use Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Builder\BaseType;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormService
{
    protected $forms = array();

    /**
     * @var FormFactoryInterface
     */
    protected $form_factory;

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
     * @var BehaviorChain
     */
    protected $behavior_chain;

    public function setFormFactory(FormFactoryInterface $form_factory)
    {
        $this->form_factory = $form_factory;
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

    public function setBehaviorChain(BehaviorChain $behavior_chain)
    {
        $this->behavior_chain = $behavior_chain;
    }

    public function buildOptions($options)
    {
        return array_merge(array(
            'is_admin' => false,
            'template' => 'FenrizbesFormConstructorBundle:FcForm:base.html.twig',
            'data'     => null
        ), $options);
    }

    /**
     * @param $fc_form
     * @param array $options
     * @return FormInterface
     * @throws \Exception
     */
    public function create($fc_form, $options = array())
    {
        if ($fc_form instanceof FcForm) {
            $alias = $fc_form->getAlias();
        } else {
            $alias = $fc_form;
        }

        if (!isset($this->forms[$alias])) {
            $options = $this->buildOptions($options);
            $options['data']['_template'] = $options['template'];

            if (!$fc_form instanceof FcForm) {
                $fc_form = $this->findFcForm($alias, (bool) $options['is_admin']);
            }

            if (!$fc_form instanceof FcForm) {
                if ($options['is_admin']) {
                    throw new \Exception('Form "'. $alias .'" not found');
                }

                return null;
            }

            $type = new BaseType($fc_form, $options);
            $type->setRouter($this->router);
            $type->setFieldChain($this->field_chain);
            $type->setConstraintChain($this->constraint_chain);
            $type->setListenerChain($this->listener_chain);
            $type->setTemplateChain($this->template_chain);
            $type->setBehaviorChain($this->behavior_chain);

            $this->forms[$alias] = $this->form_factory->create($type, $options['data']);
        }

        return $this->forms[$alias];
    }

    public function findFcForm($alias, $is_admin = false)
    {
        return FcFormQuery::create()
            ->filterByAlias($alias)
            ->_if(!$is_admin)
                ->filterByIsActive(true)
            ->_endif()
            ->findOne();
    }

    public function guessFcForm(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $fc_forms = FcFormQuery::create()
            ->filterByMethod($method)
            ->find();

        /** @var FcForm $fc_form */
        foreach ($fc_forms as $fc_form) {
            $parameter_bag = ($method == 'POST' ? $request->request : $request->query);

            if ($parameter_bag->has($fc_form->getAlias())) {
                return $fc_form;
            }
        }

        return null;
    }

    public function clear(FcForm $fc_form, $options = array())
    {
        if (isset($this->forms[$fc_form->getAlias()])) {
            unset($this->forms[$fc_form->getAlias()]);
        }

        return $this->create($fc_form, $options);
    }

    public function initData($data = null)
    {
        if (!is_array($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (!preg_match('/^__/', $key)) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}