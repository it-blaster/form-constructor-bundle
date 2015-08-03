<?php

namespace Fenrizbes\FormConstructorBundle\Item\Template;

use Fenrizbes\FormConstructorBundle\Item\AbstractItem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractTemplate extends AbstractItem
{
    protected $fields_choices;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldsField($builder);
    }

    protected function buildFormFieldsField(FormBuilderInterface $builder, array $options = array())
    {
        $builder->add('fields', 'choice', array_merge(array(
            'label'       => 'fc.label.admin.template.fields',
            'required'    => true,
            'multiple'    => true,
            'choices'     => $this->getFieldsChoices(),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ), $options));
    }

    protected function getFieldsChoices()
    {
        if (is_null($this->fields_choices)) {
            $choices = array();

            foreach ($this->getFcForm()->getFieldsRecursively() as $fc_field) {
                $choices[$fc_field->getName()] = (string) $fc_field;
            }

            $this->fields_choices = $choices;
        }

        return $this->fields_choices;
    }
}