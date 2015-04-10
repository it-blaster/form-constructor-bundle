<?php

namespace Fenrizbes\FormConstructorBundle\Listener;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Service\FormService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Router;

class RequestListener
{
    /**
     * @var FormService
     */
    protected $form_service;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    public function setFormService(FormService $form_service)
    {
        $this->form_service = $form_service;
    }

    public function setSessionService(Session $session)
    {
        $this->session = $session;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();
        $fc_form = $this->form_service->guessFcForm($request);

        if (!$fc_form instanceof FcForm) {
            return;
        }

        if ($fc_form->getAction()) {
            return;
        }

        /** @var FormInterface $form */
        $form = $this->form_service->create($fc_form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->form_service->clear($fc_form);

            if ($request->isXmlHttpRequest()) {
                return;
            }

            $this->session->getFlashBag()->add('fc_form.success', 'fc.massage.form.is_valid');

            $response = new RedirectResponse($this->router->generate(
                $request->get('_route'),
                $request->get('_route_params')
            ));

            $event->setResponse($response);
        }
    }
}