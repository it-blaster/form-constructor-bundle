<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormTemplate;

use Fenrizbes\FormConstructorBundle\Chain\TemplateChain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateType extends AbstractType
{
    /**
     * @var TemplateChain
     */
    protected $template_chain;

    protected $action;

    public function __construct(TemplateChain $template_chain, $action = null)
    {
        $this->template_chain = $template_chain;
        $this->action         = $action;
    }

    public function getName()
    {
        return 'fc_field_template';
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
            ->add('template', 'choice', array(
                'label'       => 'fc.label.admin.template',
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
        $templates = array();

        foreach ($this->template_chain->getTemplates() as $alias => $template) {
            $templates[$alias] = $template->getName();
        }

        return $templates;
    }
}