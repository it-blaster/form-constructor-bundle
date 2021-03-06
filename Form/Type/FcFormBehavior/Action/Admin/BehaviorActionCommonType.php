<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormBehavior\Action\Admin;

use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class BehaviorActionCommonType extends AbstractType
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

    public function __construct($action, TranslatorInterface $translator, ParamsBuilder $params_builder = null)
    {
        $this->action         = $action;
        $this->params_builder = $params_builder;
        $this->translator     = $translator;
    }

    public function getName()
    {
        return 'fc_form_behavior_action';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorAction'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('action', 'hidden')

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