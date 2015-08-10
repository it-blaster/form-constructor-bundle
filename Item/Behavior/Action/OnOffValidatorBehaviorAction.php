<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Action;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OnOffValidatorBehaviorAction extends OnOffValidationBehaviorAction
{
    protected $constraint_chain;

    function __construct(ConstraintChain $constraint_chain)
    {
        $this->constraint_chain = $constraint_chain;
    }

    public function getName()
    {
        return 'fc.label.behavior.actions.on_off_validator';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldField($builder);
        $this->buildFormActionField($builder);

        $builder->add('validator', 'choice', array(
            'label'       => 'fc.label.behavior.action_validator',
            'required'    => true,
            'choices'     => $this->getValidatorChoices(),
            'multiple'    => true,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    protected function getValidatorChoices()
    {
        $choices = array();

        foreach ($this->constraint_chain->getConstraints() as $alias => $constraint) {
            $choices[$alias] = $constraint->getName();
        }

        return $choices;
    }
}