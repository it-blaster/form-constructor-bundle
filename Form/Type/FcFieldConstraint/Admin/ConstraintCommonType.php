<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFieldConstraint\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConstraintCommonType extends AbstractType
{
    protected $action;
    protected $params_class;

    public function __construct($action, $params_class)
    {
        $this->action       = $action;
        $this->params_class = $params_class;
    }

    public function getName()
    {
        return 'fc_field_constraint';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('constraint', 'hidden')

            ->add('message', null, array(
                'label'       => 'fc.label.admin.constraint.message',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('is_active', null, array(
                'label'    => 'fc.label.admin.field.is_active',
                'required' => false
            ))

            ->add('params', new $this->params_class(), array(
                'label' => false
            ))
        ;
    }
}