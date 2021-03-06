<?php

namespace Fenrizbes\FormConstructorBundle\Controller;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FcFormController extends Controller
{
    public function handleAjaxAction($alias)
    {
        // TODO: Проверять, из админки запрос или из фронта
        $fc_form = $this->get('fc.form')->findFcForm($alias);

        if (!$fc_form instanceof FcForm || !$fc_form->getIsAjax()) {
            throw $this->createNotFoundException();
        }

        $form = $this->get('fc.form')->create($fc_form);
        $data = $form->getData();

        if ($fc_form->getMessage()) {
            $message = $fc_form->getMessage();
        } else {
            $message = $this->get('translator')->trans('fc.message.form.is_valid', array(), 'FenrizbesFormConstructorBundle');
        }

        return new JsonResponse(array(
            'success' => !$form->isSubmitted() || $form->isValid(),
            'message' => $message,
            'form'    => $this->renderView($data['_template'], array(
                'form' => $form->createView()
            ))
        ), 200, array(
            'Content-type' => 'text/html'
        ));
    }
}
