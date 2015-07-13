<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;

class TextField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.text';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeholder', 'text', array(
                'label'    => 'fc.label.admin.field.placeholder',
                'required' => false
            ))
        ;
    }

    protected function buildFieldOptions(ConstraintChain $constraint_chain, FcField $fc_field)
    {
        $options = parent::buildFieldOptions($constraint_chain, $fc_field);
        $params  = $fc_field->getParams();

        if ($params['placeholder']) {
            if (!isset($options['attr'])) {
                $options['attr'] = array();
            }

            $options['attr']['placeholder'] = $params['placeholder'];
        }

        return $options;
    }
}