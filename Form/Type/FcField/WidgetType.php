<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField;

use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WidgetType extends AbstractType
{
    /**
     * @var FieldChain
     */
    protected $field_chain;

    protected $action;
    protected $fc_form_id;

    public function __construct(FieldChain $field_chain, $action = null, $fc_form_id = null)
    {
        $this->field_chain = $field_chain;
        $this->action      = $action;
        $this->fc_form_id  = $fc_form_id;
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
        $inbuilt = array();
        $custom  = array();

        foreach ($this->field_chain->getFields() as $alias => $field) {
            $inbuilt[$alias] = $field->getName();
        }

        /** @var FcForm[] $fc_forms */
        $fc_forms = FcFormQuery::create()
            ->filterByIsWidget(true)
            ->orderByTitle()
            ->find()
        ;

        foreach ($fc_forms as $fc_form) {
            if ($fc_form->getId() != $this->fc_form_id) {
                $custom[$fc_form->getAlias()] = $fc_form->getTitle();
            }
        }

        if (!empty($custom)) {
            return array(
                'fc.label.admin.field.widget.inbuilt' => $inbuilt,
                'fc.label.admin.field.widget.custom'  => $custom
            );
        } else {
            return $inbuilt;
        }
    }
}