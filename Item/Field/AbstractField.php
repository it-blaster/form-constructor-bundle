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
        $builder->add(
            $this->buildFieldName($fc_field),
            $this->buildFieldType($fc_field),
            $this->buildFieldOptions($constraint_chain, $fc_field)
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

    protected function buildFieldOptions(ConstraintChain $constraint_chain, FcField $fc_field)
    {
        $options = array(
            'label'       => $fc_field->getLabel(),
            'required'    => false,
            'constraints' => array()
        );

        foreach ($fc_field->getConstraints() as $fc_constraint) {
            $this->buildFieldConstraint($options, $fc_constraint, $constraint_chain);
        }

        return $options;
    }

    protected function buildFieldConstraint(&$options, FcFieldConstraint $fc_constraint, ConstraintChain $constraint_chain)
    {
        $constraint = $constraint_chain->getConstraint($fc_constraint->getConstraint());
        $constraint->buildConstraint($options, $fc_constraint);
    }
}