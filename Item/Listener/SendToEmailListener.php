<?php

namespace Fenrizbes\FormConstructorBundle\Item\Listener;

use Fenrizbes\FormConstructorBundle\Item\Listener\Handler\SendToEmailHandler;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendToEmailListener extends AbstractListener
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var DelegatingEngine
     */
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, DelegatingEngine $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }

    public function getName()
    {
        return 'fc.label.listeners.send_to_email';
    }

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

    protected function buildEventHandler(FcFormEventListener $fc_listener)
    {
        $handler = new SendToEmailHandler($this->mailer, $this->templating, $fc_listener);

        return array($handler, 'handle');
    }
}