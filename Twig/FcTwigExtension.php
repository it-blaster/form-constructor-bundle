<?php

namespace Fenrizbes\FormConstructorBundle\Twig;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Chain\ListenerChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Service\FormService;
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

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fc_field',      array($this, 'getFcField')),
            new \Twig_SimpleFilter('fc_constraint', array($this, 'getFcConstraint')),
            new \Twig_SimpleFilter('fc_listener',   array($this, 'getFcListener')),
            new \Twig_SimpleFilter('fc_value',      array($this, 'getFcValue'))
        );
    }

    public function getFunctions()
    {
        return array(
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

    public function getFcValue($value, FcField $fc_field)
    {
        if (is_null($value) || empty($value)) {
            return $value;
        }

        return $this->field_chain
            ->getField($fc_field->getType())
            ->verboseValue($value, $fc_field)
        ;
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
