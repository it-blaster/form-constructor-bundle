<?php

namespace Fenrizbes\FormConstructorBundle\Controller\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Form\Type\FcRequest\Admin\RequestCommonType;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestSetting;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestSettingQuery;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FcRequestAdminController extends CRUDController
{
    public function listAction(Request $request = null)
    {
        if (!$this->admin->getRequest()->get('form_id')) {
            return $this->formsListAction();
        }

        return parent::listAction();
    }

    public function formsListAction()
    {
        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcRequest:list.html.twig', array(
            'action' => 'list',
            'forms'  => FcFormQuery::create()
                ->filterByIsWidget(false)
                ->orderByTitle()
                ->find()
        ));
    }

    public function configureAction(Request $request, $form_id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $fc_form = FcFormQuery::create()->findPk((int) $form_id);
        if (!$fc_form instanceof FcForm) {
            throw $this->createNotFoundException('Form not found');
        }

        $form_action = $this->admin->generateUrl('do_configure', array(
            'form_id' => $fc_form->getId()
        ));

        $form = $this->createForm(
            new RequestCommonType($form_action, $fc_form),
            FcRequestSettingQuery::create()->filterByFcForm($fc_form)->findOneOrCreate()
        );

        return $this->render('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function doConfigureAction(Request $request, $form_id)
    {
        try {
            if (!$request->isXmlHttpRequest()) {
                throw $this->createNotFoundException();
            }

            $fc_form = FcFormQuery::create()->findPk((int) $form_id);
            if (!$fc_form instanceof FcForm) {
                throw $this->createNotFoundException('Form not found');
            }

            $form_action = $this->admin->generateUrl('do_configure', array(
                'form_id' => $fc_form->getId()
            ));

            $form = $this->createForm(
                new RequestCommonType($form_action, $fc_form),
                FcRequestSettingQuery::create()->filterByFcForm($fc_form)->findOneOrCreate()
            );

            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var FcRequestSetting $setting */
                $setting = $form->getData();
                $setting->save();

                return new JsonResponse(array(
                    'success' => true
                ));
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'view'    => 'Error '. $e->getCode() .': '. $e->getMessage()
            ));
        }

        return new JsonResponse(array(
            'success' => false,
            'view'    => $this->renderView('FenrizbesFormConstructorBundle:SonataAdmin/FcForm:form.html.twig', array(
                'form' => $form->createView()
            ))
        ));
    }
}
