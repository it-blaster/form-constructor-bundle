<?php

namespace Fenrizbes\FormConstructorBundle\Admin\SonataAdmin;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestSetting;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestSettingQuery;
use Fenrizbes\FormConstructorBundle\Model\CustomColumnsSourceIterator;
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
    protected $templating;

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by'    => 'CreatedAt'
    );

    protected $maxPerPage = 9999;

    /**
     * @var FcForm
     */
    protected $fc_form;

    public function setFcDefaults($fc_defaults)
    {
        $this->fc_defaults = $fc_defaults;
    }

    public function setTemplating($templating)
    {
        $this->templating = $templating;
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

            ->add('configure')
            ->add('do_configure')
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
            ->add('Data', null, array(
                'label' => 'fc.label.admin.request.data'
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
                'label'    => '#',
                'template' => 'FenrizbesFormConstructorBundle:SonataAdmin\FcRequest:list_id_column.html.twig'
            ))
            ->add('Ip', null, array(
                'label' => 'fc.label.admin.ip_address'
            ))
            ->add('CreatedAt', null, array(
                'label'  => 'fc.label.admin.created_at',
                'format' => $this->fc_defaults['datetime_format']
            ));

        $this->addCustomColumns($listMapper);

        $listMapper->add('_action', 'actions', array(
            'label'    => 'fc.label.admin.actions',
            'sortable' => false,
            'actions'  => array(
                'show'   => array(),
                'delete' => array()
            )
        ));
    }

    protected function addCustomColumns(ListMapper $listMapper)
    {
        $columns = $this->getCustomColumns();

        if(count($columns)){
            foreach ($columns as $fc_field_name => $fc_field_label) {
                $template = 'FenrizbesFormConstructorBundle:SonataAdmin\FcRequest:list_custom_column.html.twig';

                if($this->templating->exists('FenrizbesFormConstructorBundle:SonataAdmin\FcRequest:list_'.$fc_field_label->getType().'_custom_column.html.twig')){
                    $template ='FenrizbesFormConstructorBundle:SonataAdmin\FcRequest:list_'.$fc_field_label->getType().'_custom_column.html.twig';
                }

                $listMapper->add($fc_field_name, null, array(
                    'label'    => (string) $fc_field_label,
                    'sortable' => false,
                    'template' => $template
                ));
            }
        }
    }

    protected function getCustomColumns()
    {
        $out_columns = array();

        $setting = FcRequestSettingQuery::create()->findOneByFormId($this->getFcForm()->getId());
        if (!$setting instanceof FcRequestSetting) {
            return;
        }

        $settings = $setting->getSettings();
        if (!is_array($settings) || !isset($settings['columns']) || !is_array($settings['columns'])) {
            return;
        }

        foreach ($settings['columns'] as $fc_field_id) {
            $fc_field = FcFieldQuery::create()->findPk($fc_field_id);
            if (!$fc_field instanceof FcField) {
                continue;
            }

            $out_columns['Data_'. $fc_field->getName()] = $fc_field;
        }

        return $out_columns;
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

    /**
     * Modified getDataSourceIterator for
     * correctly JSON form data export
     */
    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        return $this->exportDataRestruct($this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields()));
    }

    /**
     * Method for restructure Data
     * with JSON values
     */
    private function exportDataRestruct($results)
    {
        $cuctom_columns = $this->getCustomColumns();
        $new_results    = array();

        foreach($results AS $result){
            unset($result['Data']);

            if(count($cuctom_columns)){
                $fcrequest     = FcRequestQuery::create()->findPk($result['Id']);
                $custom_result = $this->getCustomResult($fcrequest, $cuctom_columns);
                $new_results[] = array_merge($result,$custom_result);
            }else{
                $new_results[] = $result;
            }
        }

        return new CustomColumnsSourceIterator($new_results);
    }

    private function getCustomResult($fcrequest, $cuctom_columns)
    {
        $custom_results = array();

        foreach($cuctom_columns AS $cid => $label){
            $saved_data  = $fcrequest->getData();
            $print_value = '';

            foreach($saved_data AS $key => $data) {
                if ('Data_'.$saved_data[$key]['name'] == $cid) {
                    $print_value = $fcrequest->{$cid};
                }
            }

            $print_value = $this->rewriteExportValue($label, $print_value);

            $custom_results[(string) $label] = $print_value;
        }

        return $custom_results;
    }

    private function rewriteExportValue($field, $value)
    {
        //Yes/No temlating
        if($this->templating->exists('FenrizbesFormConstructorBundle:SonataAdmin\FcRequest\Export:list_'.$field->getType().'_custom_column.html.twig')){
            $value = $this->templating->render('FenrizbesFormConstructorBundle:SonataAdmin\FcRequest\Export:list_'.$field->getType().'_custom_column.html.twig', array('value'=>$value));
        }

        return $value;
    }

}
