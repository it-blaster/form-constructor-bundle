<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OneOfSetConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.one_of_set';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fields', 'choice', array(
                'label'       => 'fc.label.admin.constraint.fields',
                'required'    => true,
                'choices'     => $this->getFieldsChoices(),
                'multiple'    => true,
                'constraints' => array(
                    new NotBlank()
                )
            ))
        ;
    }

    protected function getFieldsChoices()
    {
        $choices = array();

        foreach ($this->getFcForm()->getFields() as $field) {
            if (!$field->isCustom()) {
                $choices[$field->getName()] = (string) $field;
            }
        }

        return $choices;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $callback = function($object, ExecutionContextInterface $context) use ($fc_constraint) {
            $is_valid = false;
            $params   = $fc_constraint->getParams();
            $data     = $context->getRoot()->getData();

            foreach ($params['fields'] as $field) {
                if (isset($data[$field]) && !empty($data[$field])) {
                    $is_valid = true;

                    break;
                }
            }

            if (!$is_valid) {
                $context->buildViolation($fc_constraint->getMessage())->addViolation();
            }
        };

        $options['constraints'][] = new Callback($callback);
    }
}