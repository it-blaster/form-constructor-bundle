<?php

namespace Fenrizbes\FormConstructorBundle\Item\Listener\Handler;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Templating\DelegatingEngine;

class SendToEmailHandler
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

    /**
     * @var FormEvent
     */
    protected $event;

    public function __construct(\Swift_Mailer $mailer, DelegatingEngine $templating, FcFormEventListener $fc_listener)
    {
        $this->mailer      = $mailer;
        $this->templating  = $templating;
        $this->fc_listener = $fc_listener;
    }

    public function handle(FormEvent $event)
    {
        if (!$event->getForm()->isValid()) {
            return;
        }

        $this->event = $event;

        $this->mailer->send($this->makeMessage());
    }

    protected function makeMessage()
    {
        $data   = $this->handleData($this->event->getData());
        $params = $this->handleParams($this->fc_listener->getParams());

        if (!is_array($params['email_to'])) {
            if (strpos($params['email_to'], ',') === false) {
                $params['email_to'] = array($params['email_to']);
            } else {
                $params['email_to'] = explode(',', $params['email_to']);
            }
        }

        $message = $this->mailer
            ->createMessage()
            ->setSubject($params['subject'])
            ->setFrom($params['email_from'])
            ->setTo($params['email_to'])
        ;

        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $message->attach(
                    \Swift_Attachment::fromPath($value->getPathname())
                        ->setFilename($value->getClientOriginalName())
                );

                unset($data[$key]);
            }
        }

        $message->setBody($this->makeBody($data), 'text/html');

        return $message;
    }

    protected function handleData($data)
    {
        foreach ($data as $key => $value) {
            if (preg_match('/^_[^_]/', $key)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function handleParams($params)
    {
        return $params;
    }

    protected function makeBody($data)
    {
        return $this->templating->render('FenrizbesFormConstructorBundle:FcListener/SendToEmail:send_to_mail.html.twig', array(
            'listener' => $this->fc_listener,
            'data'     => $data
        ));
    }
}