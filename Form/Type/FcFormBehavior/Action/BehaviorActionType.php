<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormBehavior\Action;

use Fenrizbes\FormConstructorBundle\Chain\BehaviorActionChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BehaviorActionType extends AbstractType
{
    /**
     * @var BehaviorActionChain
     */
    protected $action_chain;

    protected $action;

    public function __construct(BehaviorActionChain $action_chain, $action = null)
    {
        $this->action_chain = $action_chain;
        $this->action       = $action;
    }

    public function getName()
    {
        return 'fc_field_action';
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
            ->add('action', 'choice', array(
                'label'       => 'fc.label.admin.behavior.action',
                'choices'     => $this->buildTemplateChoices(),
                'empty_value' => '',
                'attr'        => array(
                    'class' => 'fc_type_choice'
                )
            ))

            ->setAction($this->action)
        ;
    }

    protected function buildTemplateChoices()
    {
        $actions = array();

        foreach ($this->action_chain->getActions() as $alias => $action) {
            $actions[$alias] = $action->getName();
        }

        return $actions;
    }
}