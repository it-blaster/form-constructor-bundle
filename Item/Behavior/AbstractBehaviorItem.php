<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior;

use Fenrizbes\FormConstructorBundle\Item\AbstractItem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractBehaviorItem extends AbstractItem
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldField($builder);
    }

    public function buildFormFieldField(FormBuilderInterface $builder, $multiple = false)
    {
        $builder->add('field', 'choice', array(
            'label'       => 'fc.label.behavior.condition_field',
            'required'    => true,
            'choices'     => $this->getFieldsChoices(),
            'multiple'    => $multiple,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    protected function getFieldsChoices()
    {
        $choices = array();

        foreach ($this->getFcForm()->getFieldsRecursively(true) as $field) {
            $choices[$field->getName()] = (string) $field;
        }

        return $choices;
    }
}