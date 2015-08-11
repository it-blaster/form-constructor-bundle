<?php

namespace Fenrizbes\FormConstructorBundle\Controller\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Sonata\AdminBundle\Controller\CRUDController;

class FcRequestAdminController extends CRUDController
{
    public function listAction()
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
}
