<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFormListener\SendToEmail;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Templating\DelegatingEngine;

class Handler
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var DelegatingEngine
     */
    protected $templating;

    /**
     * @var FcFormEventListener
     */
    protected $fc_listener;

    public function __construct(\Swift_Mailer $mailer, DelegatingEngine $templating, FcFormEventListener $fc_listener)
    {
        $this->mailer      = $mailer;
        $this->templating  = $templating;
        $this->fc_listener = $fc_listener;
    }

    public function handle(FormEvent $event)
    {
        // TODO Обработка масивов, дат и, возможно, чего-нибудь еще
        if (!$event->getForm()->isValid()) {
            return;
        }

        $params = $this->fc_listener->getParams();
        $data   = $event->getData();

        $message = $this->mailer
            ->createMessage()
            ->setSubject($params['subject'])
            ->setFrom($params['email_from'])
            ->setTo($params['email_to']);

        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $message->attach(
                    \Swift_Attachment::fromPath($value->getPathname())
                        ->setFilename($value->getClientOriginalName())
                );

                unset($data[$key]);
            }
        }

        $message->setBody(
            $this->templating->render('FenrizbesFormConstructorBundle:FcListener:send_to_mail.html.twig', array(
                'listener' => $this->fc_listener,
                'form'     => $event->getForm(),
                'data'     => $data
            )),
            'text/html'
        );

        $this->mailer->send($message);
    }
}