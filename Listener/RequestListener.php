<?php

namespace Fenrizbes\FormConstructorBundle\Listener;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Service\FormService;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
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

    /**
     * @var Translator
     */
    protected $translator;

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

    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
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
            $data = $form->getData();

            $this->form_service->clear($fc_form, array(
                'template' => $data['_template'],
                'data'     => $this->form_service->initData($data)
            ));

            if ($fc_form->getIsAjax()) {
                return;
            }

            if ($fc_form->getMessage()) {
                $message = $fc_form->getMessage();
            } else {
                $message = $this->translator->trans('fc.message.form.is_valid', array(), 'FenrizbesFormConstructorBundle');
            }

            // TODO: Связывать сообщение с конкретной формой и подчищать старые
            $this->session->getFlashBag()->add('fc_form.success', $message);

            $response = new RedirectResponse($this->router->generate(
                $request->get('_route'),
                $request->get('_route_params')
            ));

            $event->setResponse($response);
        }
    }
}