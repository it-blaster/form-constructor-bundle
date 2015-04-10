<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ListenerCommonType extends AbstractType
{
    protected $action;
    protected $params_class;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct($action, TranslatorInterface $translator, $params_class = null)
    {
        $this->action       = $action;
        $this->params_class = $params_class;
        $this->translator   = $translator;
    }

    public function getName()
    {
        return 'fc_form_listener';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('listener', 'hidden')

            ->add('is_active', null, array(
                'label'    => 'fc.label.admin.is_active',
                'required' => false
            ))

            ->add('params', new $this->params_class(), array(
                'label' => false
            ))
        ;
    }
}