<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcRequest\Admin;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestCommonType extends AbstractType
{
    protected $action;

    /**
     * @var FcForm
     */
    protected $fc_form;

    public function __construct($action, $fc_form)
    {
        $this->action  = $action;
        $this->fc_form = $fc_form;
    }

    public function getName()
    {
        return 'fc_request';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestSetting'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $settings_type = new RequestSettingsType($this->fc_form);

        $builder
            ->add('settings', $settings_type, array(
                'label' => false
            ))

            ->setAction($this->action)
        ;
    }
}