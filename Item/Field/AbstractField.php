<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Fenrizbes\FormConstructorBundle\Item\AbstractItem;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractField extends AbstractItem
{
    public function buildField(ConstraintChain $constraint_chain, FormBuilderInterface $builder, FcField $fc_field)
    {
        $options = $this->buildFieldOptions($fc_field);

        foreach ($fc_field->getConstraints() as $fc_constraint) {
            $this->buildFieldConstraint($options, $fc_constraint, $constraint_chain);
        }

        $builder->add(
            $this->buildFieldName($fc_field),
            $this->buildFieldType($fc_field),
            $options
        );
    }

    protected function buildFieldName(FcField $fc_field)
    {
        return $fc_field->getName();
    }

    protected function buildFieldType(FcField $fc_field)
    {
        return $fc_field->getType();
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $label = $fc_field->getLabel();
        if (empty($label)) {
            $label = false;
        }

        return array(
            'label'       => $label,
            'required'    => false,
            'constraints' => array(),
            'attr'        => array(
                'data-type' => $fc_field->getType()
            )
        );
    }

    protected function buildFieldConstraint(&$options, FcFieldConstraint $fc_constraint, ConstraintChain $constraint_chain)
    {
        $constraint = $constraint_chain->getConstraint($fc_constraint->getConstraint());
        $constraint->buildConstraint($options, $fc_constraint);
    }

    public function verboseValue($value, FcField $fc_field)
    {
        return $value;
    }
}