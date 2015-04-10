<?php

namespace Fenrizbes\FormConstructorBundle\Service;

use Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Builder\BaseType;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class FormService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $forms = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildOptions($options)
    {
        return array_merge(array(
            'is_admin' => false,
            'template' => 'FenrizbesFormConstructorBundle:FcForm:base.html.twig',
            'data'     => null
        ), $options);
    }

    public function create($fc_form, $options = array())
    {
        $options = $this->buildOptions($options);

        if ($fc_form instanceof FcForm) {
            $alias = $fc_form->getAlias();
        } else {
            $alias = $fc_form;
        }

        if (!isset($this->forms[$alias])) {
            if (!$fc_form instanceof FcForm) {
                $fc_form = $this->findFcForm($alias, $options['is_admin']);
            }

            if (!$fc_form instanceof FcForm) {
                if ($options['is_admin']) {
                    throw new \Exception('Form "'. $alias .'" not found');
                }

                return null;
            }

            $this->forms[$alias] = $this->container->get('form.factory')->create(
                new BaseType($this->container, $fc_form),
                $options['data']
            );
        }

        return $this->forms[$alias];
    }

    protected function findFcForm($alias, $is_admin = false)
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

    public function clear(FcForm $fc_form)
    {
        if (isset($this->forms[$fc_form->getAlias()])) {
            unset($this->forms[$fc_form->getAlias()]);
        }

        $this->create($fc_form);
    }
}