<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcForm\Handler;

use Fenrizbes\FormConstructorBundle\Chain\FieldChain;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequest;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SaveRequestHandler
{
    /**
     * @var FieldChain
     */
    protected $field_chain;

    /**
     * @var FcForm
     */
    protected $fc_form;

    /**
     * @var FormEvent
     */
    protected $event;

    public function __construct(FieldChain $field_chain, FcForm $fc_form)
    {
        $this->field_chain = $field_chain;
        $this->fc_form     = $fc_form;
    }

    public function handle(FormEvent $event)
    {
        if (!$event->getForm()->isValid()) {
            return;
        }

        $this->event = $event;

        $this->proceed();
    }

    protected function proceed()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $fc_request = new FcRequest();
        $fc_request->setFcForm($this->fc_form);
        $fc_request->setTitle($this->fc_form->getTitle());
        $fc_request->setIp($ip);
        $fc_request->setData($this->makeData());
        $fc_request->save();
    }

    protected function makeData()
    {
        $result = array();
        $data   = $this->event->getData();

        foreach ($data as $key => $value) {
            if (preg_match('/^_[^_]/', $key)) {
                unset($data[$key]);
            }
        }

        foreach ($this->fc_form->getFieldsRecursively() as $fc_field) {
            if (!isset($data[ $fc_field->getName() ]) || empty($data[ $fc_field->getName() ])) {
                continue;
            }

            $result[] = array(
                'name'      => $fc_field->getName(),
                'type'      => $fc_field->getType(),
                'label'     => $fc_field->getLabel(),
                'value'     => $this->makeValue($fc_field, $data),
                'templates' => $this->fc_form->getFieldTemplates($fc_field->getName())
            );
        }

        return $result;
    }

    protected function makeValue(FcField $fc_field, &$data)
    {
        $value = $data[ $fc_field->getName() ];

        if ($value instanceof UploadedFile) {
            return $this->uploadFile($value);
        }

        return $this->field_chain
            ->getField($fc_field->getType())
            ->verboseValue($value, $fc_field)
        ;
    }

    protected function uploadFile(UploadedFile $file)
    {
        $web_path  = '/uploads/fc_request/'. date('Y/m/d');
        $directory = rtrim(__DIR__, '/') .'/../../../../../../../../../web'. $web_path;
        $name      = $file->getClientOriginalName() .'.'. $file->getClientOriginalExtension();

        @mkdir($directory, 0777, true);

        if (!@copy($file->getPathname(), $directory .'/'. $name)) {
            return null;
        }

        return $web_path .'/'. $name;
    }
}