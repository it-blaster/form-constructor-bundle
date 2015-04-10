<?php

namespace Fenrizbes\FormConstructorBundle\Builder\FcFormListener\SendToEmail;

use Fenrizbes\FormConstructorBundle\Builder\FcFormListener\BaseBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormEventListener;
use Symfony\Component\Templating\DelegatingEngine;

class Builder extends BaseBuilder
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

    public function getHandler(FcFormEventListener $fc_listener)
    {
        $handler = new Handler($this->mailer, $this->templating, $fc_listener);

        return array($handler, 'handle');
    }
}