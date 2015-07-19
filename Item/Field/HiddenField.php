<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;

class HiddenField extends AbstractField
{
    public function getName()
    {
        return 'fc.label.field_type.hidden';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', 'text', array(
                'label'    => 'fc.label.admin.field.value',
                'required' => false
            ))
        ;
    }

    protected function buildFieldOptions(ConstraintChain $constraint_chain, FcField $fc_field)
    {
        $options = parent::buildFieldOptions($constraint_chain, $fc_field);
        $params  = $fc_field->getParams();

        if (!empty($params['value'])) {
            $options['data'] = $params['value'];
        }

        return $options;
    }
}