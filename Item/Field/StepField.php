<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;

class StepField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.step';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prev', 'text', array(
                'label'    => 'fc.label.admin.field.step.prev',
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'fc.label.admin.field.step.prev_default'
                )
            ))
            ->add('next', 'text', array(
                'label'    => 'fc.label.admin.field.step.next',
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'fc.label.admin.field.step.next_default'
                )
            ))
        ;
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $options = parent::buildFieldOptions($fc_field);
        $params  = $fc_field->getParams();

        $options['button_prev'] = $params['prev'];
        $options['button_next'] = $params['next'];

        return $options;
    }
}