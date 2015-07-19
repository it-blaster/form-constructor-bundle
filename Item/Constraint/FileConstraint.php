<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;

class FileConstraint extends AbstractConstraint
{
    public function getName()
    {
        return 'fc.label.constraints.file';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormFieldMaxSize($builder);
        $this->buildFormFieldMimeTypes($builder);
    }

    public function buildFormFieldMaxSize(FormBuilderInterface $builder)
    {
        $builder->add('max_size', 'text', array(
            'label'       => 'fc.label.admin.constraint.max_size',
            'required'    => false,
            'constraints' => array(
                new Regex(array(
                    'pattern' => '/^\d+$/',
                    'message' => 'fc.constraint.admin.not_integer'
                ))
            )
        ));
    }

    public function buildFormFieldMimeTypes(FormBuilderInterface $builder)
    {
        $builder->add('mime_types', 'fc_collection', array(
            'label'    => 'fc.label.admin.constraint.mime_types',
            'required' => false,
            'type'     => 'text'
        ));
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new File(
            $this->buildConstraintOptions($fc_constraint)
        );
    }

    protected function buildConstraintOptions(FcFieldConstraint $fc_constraint)
    {
        $constraint_options = array(
            'maxSizeMessage'   => $fc_constraint->getMessage(),
            'mimeTypesMessage' => $fc_constraint->getMessage()
        );

        $params = $fc_constraint->getParams();

        if (!empty($params['max_size'])) {
            $constraint_options['maxSize'] = $params['max_size'];
        }

        if (!empty($params['mime_types']) && is_array($params['mime_types'])) {
            $constraint_options['mimeTypes'] = array();

            foreach ($params['mime_types'] as $mime_type) {
                $constraint_options['mimeTypes'][] = $mime_type;
            }
        }

        return $constraint_options;
    }
}