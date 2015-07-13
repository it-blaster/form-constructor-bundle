<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint;

use Fenrizbes\FormConstructorBundle\Chain\ConstraintChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConstraintType extends AbstractType
{
    /**
     * @var ConstraintChain
     */
    protected $constraint_chain;

    protected $action;

    public function __construct(ConstraintChain $constraint_chain, $action = null)
    {
        $this->constraint_chain = $constraint_chain;
        $this->action           = $action;
    }

    public function getName()
    {
        return 'fc_field_constraint';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('constraint', 'choice', array(
                'label'       => 'fc.label.admin.constraint',
                'choices'     => $this->buildConstraintChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildConstraintChoices()
    {
        $constraints = array();

        foreach ($this->constraint_chain->getConstraints() as $alias => $constraint) {
            $constraints[$alias] = $constraint->getName();
        }

        return $constraints;
    }
}