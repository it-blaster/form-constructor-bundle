<?php

namespace Fenrizbes\FormConstructorBundle\Item\Behavior\Condition;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StateBehaviorCondition extends AbstractBehaviorCondition
{
    protected $check_choices = array(
        'checked'   => 'fc.label.behavior.condition_check.checked',
        'unchecked' => 'fc.label.behavior.condition_check.unchecked'
    );

    public function getName()
    {
        return 'fc.label.behavior.conditions.state';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('check', 'choice', array(
            'label'       => 'fc.label.behavior.condition_check',
            'required'    => true,
            'choices'     => $this->check_choices,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    public function getCheck($key)
    {
        return $this->check_choices[$key];
    }
}