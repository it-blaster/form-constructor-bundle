<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcField;

use Fenrizbes\FormConstructorBundle\Builder\FcFieldConstraint\ConstraintBuilderInterface;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;

class BaseBuilder implements FieldBuilderInterface
{
    protected $constraints;

    /**
     * @var FcField
     */
    protected $fc_field;

    public function __construct(array $constraints, FcField $fc_field)
    {
        $this->constraints = $constraints;
        $this->fc_field    = $fc_field;
    }

    public function getField()
    {
        return $this->fc_field->getName();
    }

    public function getType()
    {
        return $this->fc_field->getType();
    }

    public function getOptions()
    {
        $options = array(
            'label'       => $this->fc_field->getLabel(),
            'required'    => false,
            'constraints' => array()
        );

        foreach ($this->fc_field->getConstraints() as $fc_constraint) {
            $this->addConstraint($fc_constraint, $options);
        }

        return $options;
    }

    protected function addConstraint(FcFieldConstraint $fc_constraint, &$options)
    {
        if (
            !isset($this->constraints[$fc_constraint->getConstraint()])
            ||
            !isset($this->constraints[$fc_constraint->getConstraint()]['builder'])
        ) {
            throw new \Exception('Builder for "'. $fc_constraint->getConstraint() .'" constraint not found');
        }

        $constraint = $this->constraints[$fc_constraint->getConstraint()];

        $constraint_builder = new $constraint['builder']($fc_constraint);
        if (!$constraint_builder instanceof ConstraintBuilderInterface) {
            throw new \Exception('Builder for "'. $fc_constraint->getConstraint() .'" constraint must implements ConstraintBuilderInterface');
        }

        $constraint_builder->addConstraint($options);
    }
}