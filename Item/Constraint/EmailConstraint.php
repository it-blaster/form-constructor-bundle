<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.email';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('strict', 'checkbox', array(
                'label'    => 'fc.label.admin.constraint.strict',
                'required' => false
            ))
        ;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $params = $fc_constraint->getParams();

        $options['constraints'][] = new Email(array(
            'message'   => $fc_constraint->getMessage(),
            'strict'    => (bool) $params['strict'],
            'checkMX'   => (bool) $params['strict'],
            'checkHost' => (bool) $params['strict']
        ));
    }
}