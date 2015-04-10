<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConstraintType extends AbstractType
{
    protected $constraints;
    protected $action;

    public function __construct(array $constraints, $action = null)
    {
        $this->constraints = $constraints;
        $this->action      = $action;
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

        foreach ($this->constraints as $name => $item) {
            $constraints[$name] = $item['label'];
        }

        return $constraints;
    }
}