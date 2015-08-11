<?php

namespace Fenrizbes\FormConstructorBundle\Admin\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestQuery;
use Knp\Menu\MenuItem;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FcRequestAdmin extends Admin
{
    protected $baseRouteName    = 'fenrizbes_fc_request';
    protected $baseRoutePattern = '/fenrizbes/fc/request/{form_id}';
    protected $fc_defaults;

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by'    => 'CreatedAt'
    );

    /**
     * @var FcForm
     */
    protected $fc_form;

    public function setFcDefaults($fc_defaults)
    {
        $this->fc_defaults = $fc_defaults;
    }

    protected function getFcForm()
    {
        if (is_null($this->fc_form)) {
            $form_id = $this->getRequest()->get('form_id');

            $this->fc_form = FcFormQuery::create()->findPk((int) $form_id);
            if (!$this->fc_form instanceof FcForm) {
                throw new NotFoundHttpException('Form #'. $form_id .' does not exist');
            }
        }

        return $this->fc_form;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('edit')
        ;
    }

    public function createQuery($context = 'list')
    {
        /** @var FcRequestQuery $query */
        $query = parent::createQuery($context);

        $query->filterByFcForm($this->getFcForm());

        return $query;
    }

    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        $parameters = array_merge(array(
            'form_id' => ($this->request && $this->getRequest()->get('form_id') ? $this->getFcForm()->getId() : 0)
        ), $parameters);

        return parent::generateUrl($name, $parameters, $absolute);
    }

    public function getBreadcrumbs($action)
    {
        $breadcrumbs = parent::getBreadcrumbs($action);

        $menu = $this->menuFactory->createItem('root');
        $menu = $menu->addChild(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel('fc_request_list', 'breadcrumb', 'link')),
            array('uri' => $this->routeGenerator->generate('fenrizbes_fc_request_list', array('form_id' => 0)))
        );


        if ($this->getRequest()->get('form_id')) {
            array_splice($breadcrumbs, 1, 0, array($menu));

            /** @var MenuItem $banner_list_menu */
            $banner_list_menu = $breadcrumbs[2];
            $banner_list_menu->setName($this->getFcForm()->getTitle());
        }

        return $breadcrumbs;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('Ip', null, array(
                'label' => 'fc.label.admin.ip_address'
            ))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Id', 'text', array(
                'label' => '#'
            ))
            ->add('Ip', null, array(
                'label' => 'fc.label.admin.ip_address'
            ))
            ->add('CreatedAt', null, array(
                'label'  => 'fc.label.admin.created_at',
                'format' => $this->fc_defaults['datetime_format']
            ))
            ->add('_action', 'actions', array(
                'label'    => 'fc.label.admin.actions',
                'sortable' => false,
                'actions'  => array(
                    'show'   => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('Ip', null, array(
                'label' => 'fc.label.admin.ip_address'
            ))
            ->add('CreatedAt', null, array(
                'label'  => 'fc.label.admin.created_at',
                'format' => $this->fc_defaults['datetime_format']
            ))
            ->add('Data', null, array(
                'label'    => 'fc.label.admin.request_data',
                'template' => 'FenrizbesFormConstructorBundle:SonataAdmin\FcRequest:show_data.html.twig'
            ))
        ;
    }
}
