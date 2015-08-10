<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormBehavior\Condition\Admin;

use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehavior;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class BehaviorConditionCommonType extends AbstractType
{
    protected $action;

    /**
     * @var ParamsBuilder
     */
    protected $params_builder;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var FcFormBehavior
     */
    protected $behavior;

    public function __construct(FcFormBehavior $behavior, $action, TranslatorInterface $translator, ParamsBuilder $params_builder = null)
    {
        $this->behavior       = $behavior;
        $this->action         = $action;
        $this->params_builder = $params_builder;
        $this->translator     = $translator;
    }

    public function getName()
    {
        return 'fc_form_behavior_condition';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('condition', 'hidden')
        ;

        if (!$this->behavior->isFirstCondition($builder->getData())) {
            $builder->add('operator', 'choice', array(
                'label'       => 'fc.label.admin.condition_operator',
                'required'    => true,
                'choices'     => array(
                    1 => 'fc.label.admin.condition_operator.and',
                    0 => 'fc.label.admin.condition_operator.or'
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ));
        }

        $builder
            ->add('is_active', null, array(
                'label'    => 'fc.label.admin.is_active',
                'required' => false
            ))

            ->add('params', $this->params_builder, array(
                'label' => false
            ))
        ;
    }
}