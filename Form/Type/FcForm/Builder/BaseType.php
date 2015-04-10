<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Builder;

use Fenrizbes\FormConstructorBundle\Builder\FcField\FieldBuilderInterface;
use Fenrizbes\FormConstructorBundle\Builder\FcFormListener\ListenerBuilderInterface;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

class BaseType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var FcForm
     */
    protected $fc_form;

    public function __construct(ContainerInterface $container, FcForm $fc_form)
    {
        $this->container = $container;
        $this->fc_form   = $fc_form;
    }

    public function getName()
    {
        return $this->fc_form->getAlias();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'FenrizbesFormConstructorBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->fc_form->getFields() as $fc_field) {
            $this->addField($builder, $fc_field);
        }

        $action = $this->fc_form->getAction();
        if ($this->container->get('router')->getRouteCollection()->get($action) !== null) {
            $action = $this->container->get('router')->generate($action);
        }

        $builder
            ->add('submit', 'submit', array(
                'label' => ($this->fc_form->getButton() ? $this->fc_form->getButton() : 'fc.label.button')
            ))
            ->setMethod($this->fc_form->getMethod())
            ->setAction($action);

        foreach ($this->fc_form->getListeners() as $fc_listener) {
            $this->addListener($builder, $fc_listener);
        }
    }

    protected function addField(FormBuilderInterface $builder, FcField $fc_field)
    {
        if ($fc_field->isCustom()) {
            foreach ($fc_field->getCustomWidget()->getFields() as $field) {
                $this->addField($builder, $field);
            }

            return;
        }

        $types = $this->container->getParameter('fc.fields_types');

        if (!isset($types[ $fc_field->getType() ]) || !isset($types[ $fc_field->getType() ]['builder'])) {
            throw new \Exception('Builder for the "'. $fc_field->getType() .'" field not found');
        }

        $field_builder = new $types[ $fc_field->getType() ]['builder']($this->container->getParameter('fc.constraints'), $fc_field);
        if (!$field_builder instanceof FieldBuilderInterface) {
            throw new \Exception('Builder for the "'. $fc_field->getType() .'" field must implements FieldBuilderInterface');
        }

        $builder->add(
            $field_builder->getField(),
            $field_builder->getType(),
            $field_builder->getOptions()
        );
    }

    protected function addListener(FormBuilderInterface $builder, FcFormEventListener $fc_listener)
    {
        if (!$this->container->has('fc.listeners_builders.'. $fc_listener->getListener())) {
            throw new \Exception('Builder for the "'. $fc_listener->getListener() .'" listener not found');
        }

        $listener_builder = $this->container->get('fc.listeners_builders.'. $fc_listener->getListener());
        if (!$listener_builder instanceof ListenerBuilderInterface) {
            throw new \Exception('Builder for the "'. $fc_listener->getListener() .'" listener must implements ListenerBuilderInterface');
        }

        $builder->addEventListener(
            $listener_builder->getEvent(),
            $listener_builder->getHandler($fc_listener),
            $listener_builder->getPriority()
        );
    }
}