<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WidgetType extends AbstractType
{
    protected $types;
    protected $action;
    protected $fc_form_id;

    public function __construct(array $types, $action = null, $fc_form_id = null)
    {
        $this->types      = $types;
        $this->action     = $action;
        $this->fc_form_id = $fc_form_id;
    }

    public function getName()
    {
        return 'fc_field_widget';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'label'       => 'fc.label.admin.field.type',
                'choices'     => $this->buildTypeChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildTypeChoices()
    {
        $inbuilt_types = array();

        foreach ($this->types as $name => $type) {
            $inbuilt_types[$name] = $type['label'];
        }

        $custom_widgets = array();

        $fc_forms = FcFormQuery::create()
            ->filterByIsWidget(true)
            ->orderByTitle()
            ->find();

        /** @var FcForm $fc_form */
        foreach ($fc_forms as $fc_form) {
            if ($fc_form->getId() != $this->fc_form_id) {
                $custom_widgets[$fc_form->getAlias()] = $fc_form->getTitle();
            }
        }

        if ($custom_widgets) {
            return array(
                'fc.label.admin.field.widget.inbuilt' => $inbuilt_types,
                'fc.label.admin.field.widget.custom'  => $custom_widgets
            );
        } else {
            return $inbuilt_types;
        }
    }
}