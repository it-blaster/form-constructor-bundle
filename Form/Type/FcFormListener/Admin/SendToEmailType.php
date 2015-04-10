<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcFormListener\Admin;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendToEmailType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'text', array(
                'label'       => 'fc.label.admin.listener.email_subject',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('email_from', 'text', array(
                'label'       => 'fc.label.admin.listener.email_from',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    )),
                    new Email(array(
                        'message' => 'fc.constraint.admin.invalid_email'
                    ))
                )
            ))
            ->add('email_to', 'text', array(
                'label'       => 'fc.label.admin.listener.email_to',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    )),
                    new Email(array(
                        'message' => 'fc.constraint.admin.invalid_email'
                    ))
                )
            ))
            ->add('header', 'textarea', array(
                'label'    => 'fc.label.admin.listener.email_header',
                'required' => false
            ))
            ->add('footer', 'textarea', array(
                'label'    => 'fc.label.admin.listener.email_footer',
                'required' => false
            ))
        ;
    }
}