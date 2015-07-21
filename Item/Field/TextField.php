<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

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
        $this->buildFormPlaceholderField($builder);
    }

    protected function buildFormPlaceholderField(FormBuilderInterface $builder)
    {
        $builder->add('placeholder', 'text', array(
            'label'    => 'fc.label.admin.field.placeholder',
            'required' => false
        ));
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $options = parent::buildFieldOptions($fc_field);
        $params  = $fc_field->getParams();

        if (!empty($params['placeholder'])) {
            if (!isset($options['attr'])) {
                $options['attr'] = array();
            }

            $options['attr']['placeholder'] = $params['placeholder'];
        }

        return $options;
    }
}