<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Form\Type\ChoiceOptionType;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;

class ChoiceField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.choice';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choices', 'fc_collection', array(
                'label'       => 'fc.label.admin.field.choices',
                'required'    => true,
                'type'        => new ChoiceOptionType(),
                'constraints' => array(
                    new Count(array(
                        'min'        => 1,
                        'minMessage' => 'fc.constraint.admin.choices_min'
                    ))
                )
            ))
            ->add('multiple', 'checkbox', array(
                'label'    => 'fc.label.admin.field.multiple',
                'required' => false
            ))
            ->add('collapsed', 'checkbox', array(
                'label'    => 'fc.label.admin.field.collapsed',
                'required' => false
            ))
        ;
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $options = parent::buildFieldOptions($fc_field);
        $params  = $fc_field->getParams();

        $options['choices']  = array();
        $options['multiple'] = (bool) $params['multiple'];
        $options['expanded'] = !$params['collapsed'];
        $options['data']     = ($options['multiple'] ? array() : null);

        if ($options['expanded'] && !$options['multiple']) {
            $options['empty_value'] = false;
        }

        foreach ($params['choices'] as $choice) {
            $options['choices'][ $choice['value'] ] = $choice['label'];

            if ((bool) $choice['is_selected']) {
                if ($options['multiple']) {
                    $options['data'][] = $choice['value'];
                } else {
                    $options['data'] = $choice['value'];
                }
            }
        }

        return $options;
    }
}