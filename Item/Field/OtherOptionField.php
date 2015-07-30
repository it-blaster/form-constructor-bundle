<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OtherOptionField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.other_option';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('linked_field', 'choice', array(
            'label'       => 'fc.label.admin.field.linked_field',
            'required'    => true,
            'choices'     => $this->getChoices(),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    protected function getChoices()
    {
        $choices = array();

        foreach ($this->getFcForm()->getFields() as $field) {
            $choices[$field->getName()] = (string) $field;
        }

        return $choices;
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $options = parent::buildFieldOptions($fc_field);
        $params  = $fc_field->getParams();

        $options['linked_field'] = FcFieldQuery::create()->findOneByName($params['linked_field']);

        return $options;
    }
}