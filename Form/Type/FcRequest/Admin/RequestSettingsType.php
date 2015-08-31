<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcRequest\Admin;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestSettingsType extends AbstractType
{
    /**
     * @var FcForm
     */
    protected $fc_form;

    public function __construct($fc_form)
    {
        $this->fc_form = $fc_form;
    }

    public function getName()
    {
        return 'fc_request_settings';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('columns', 'choice', array(
                'label'    => 'fc.label.admin.request_settings.columns',
                'required' => false,
                'multiple' => true,
                'choices'  => $this->getColumnsChoices()
            ))
            ->add('dummy', 'hidden', array(
                'mapped' => false
            ))
        ;
    }

    protected function getColumnsChoices()
    {
        $choices = array();

        foreach ($this->fc_form->getFieldsRecursively(true) as $fc_field) {
            $choices[ $fc_field->getId() ] = (string) $fc_field;
        }

        return $choices;
    }
}