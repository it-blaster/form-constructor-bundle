<?php

namespace Fenrizbes\FormConstructorBundle\Item\Field;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DateTimeField extends TextField
{
    protected $date_choices = array(
        'd.m.Y' => 'fc.label.datetime_format_d.m.Y',
        'Y-m-d' => 'fc.label.datetime_format_Y-m-d',
        'Y/m/d' => 'fc.label.datetime_format_Y/m/d',
        'm/d/Y' => 'fc.label.datetime_format_m/d/Y'
    );

    protected $time_choices = array(
        'H:i'   => 'fc.label.datetime_format_H:i',
        'g:i A' => 'fc.label.datetime_format_g:i A'
    );

    public function getName()
    {
        return 'fc.label.datetime';
    }

    protected function buildFieldType(FcField $fc_field)
    {
        return 'fc_'. parent::buildFieldType($fc_field);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormPlaceholderField($builder);
        $this->buildFormDateFormatField($builder);
        $this->buildFormTimeFormatField($builder);
    }

    public function buildFormDateFormatField(FormBuilderInterface $builder)
    {
        $builder->add('date_format', 'choice', array(
            'label'       => 'fc.label.date_format',
            'required'    => true,
            'choices'     => $this->date_choices,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    public function buildFormTimeFormatField(FormBuilderInterface $builder)
    {
        $builder->add('time_format', 'choice', array(
            'label'       => 'fc.label.time_format',
            'required'    => true,
            'choices'     => $this->time_choices,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    protected function buildFieldOptions(FcField $fc_field)
    {
        $options = parent::buildFieldOptions($fc_field);
        $options['format'] = $this->buildFormatOption($fc_field);

        if (!isset($options['attr'])) {
            $options['attr'] = array();
        }
        $options['attr']['data-format'] = $options['format'];

        return $options;
    }

    protected function buildFormatOption(FcField $fc_field)
    {
        $params = $fc_field->getParams();
        $format = array();

        if (isset($params['date_format']) && !empty($params['date_format'])) {
            $format[] = $params['date_format'];
        }

        if (isset($params['time_format']) && !empty($params['time_format'])) {
            $format[] = $params['time_format'];
        }

        return implode(' ', $format);
    }
}