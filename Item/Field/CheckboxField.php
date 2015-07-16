<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;

class CheckboxField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.checkbox';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_checked', 'checkbox', array(
                'label'    => 'fc.label.admin.field.is_checked',
                'required' => false
            ))
        ;
    }

    protected function buildFieldOptions(ConstraintChain $constraint_chain, FcField $fc_field)
    {
        $options = parent::buildFieldOptions($constraint_chain, $fc_field);
        $params  = $fc_field->getParams();

        if ($params['is_checked']) {
            if (!isset($options['attr'])) {
                $options['attr'] = array();
            }

            $options['attr']['checked'] = 'checked';
        }

        return $options;
    }
}