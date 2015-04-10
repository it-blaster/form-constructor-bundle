<?php

namespace Fenrizbes\FormConstructorBundle\Twig;

use Fenrizbes\FormConstructorBundle\Service\FormService;
use Symfony\Component\Form\FormInterface;

class FcTwigExtension extends \Twig_Extension
{
    protected $types;
    protected $constraints;
    protected $listeners;

    /**
     * @var FormService
     */
    protected $form_service;

    public function __construct($types, $constraints, $listeners)
    {
        $this->types       = $types;
        $this->constraints = $constraints;
        $this->listeners   = $listeners;
    }

    public function getName()
    {
        return 'FcTwigExtension';
    }

    public function setFormService(FormService $form_service)
    {
        $this->form_service = $form_service;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fc_type',       array($this, 'getFcType')),
            new \Twig_SimpleFilter('fc_constraint', array($this, 'getFcConstraint')),
            new \Twig_SimpleFilter('fc_listener',   array($this, 'getFcListener'))
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

    public function getFcType($name)
    {
        return $this->types[$name]['label'];
    }

    public function getFcConstraint($name)
    {
        return $this->constraints[$name]['label'];
    }

    public function getFcListener($name)
    {
        return $this->listeners[$name]['label'];
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

        return $environment->render($options['template'], array(
            'form' => $this->getFcFormView($alias, $options)
        ));
    }
}
