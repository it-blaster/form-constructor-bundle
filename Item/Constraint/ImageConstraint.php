<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ImageConstraint extends FileConstraint
{
    protected $mime_types = array(
        'image/gif'                => 'image/gif',
        'image/png'                => 'image/png',
        'image/jpg'                => 'image/jpg',
        'image/jpeg'               => 'image/jpeg',
        'image/pjpeg'              => 'image/pjpeg',
        'image/svg+xml'            => 'image/svg+xml',
        'image/tiff'               => 'image/tiff',
        'image/vnd.microsoft.icon' => 'image/vnd.microsoft.icon',
        'image/vnd.wap.wbmp'       => 'image/vnd.wap.wbmp'
    );

    public function getName()
    {
        return 'fc.label.constraints.image';
    }

    public function buildFormFieldMimeTypes(FormBuilderInterface $builder)
    {
        $builder->add('mime_types', 'choice', array(
            'label'       => 'fc.label.admin.constraint.mime_types',
            'required'    => true,
            'choices'     => $this->mime_types,
            'multiple'    => true,
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'fc.constraint.admin.blank'
                ))
            )
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('min_width', 'text', array(
                'label'       => 'fc.label.admin.constraint.min_width',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('max_width', 'text', array(
                'label'       => 'fc.label.admin.constraint.max_width',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('min_height', 'text', array(
                'label'       => 'fc.label.admin.constraint.min_height',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('max_height', 'text', array(
                'label'       => 'fc.label.admin.constraint.max_height',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^\d+$/',
                        'message' => 'fc.constraint.admin.not_integer'
                    ))
                )
            ))
            ->add('ratio', 'text', array(
                'label'       => 'fc.label.admin.constraint.ratio',
                'required'    => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^(\d+\.)?\d+$/',
                        'message' => 'fc.constraint.admin.not_float'
                    ))
                )
            ))
        ;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $options['constraints'][] = new Image(
            $this->buildConstraintOptions($fc_constraint)
        );
    }

    protected function buildConstraintOptions(FcFieldConstraint $fc_constraint)
    {
        $constraint_options = parent::buildConstraintOptions($fc_constraint);

        $constraint_options['minWidthMessage']  = $fc_constraint->getMessage();
        $constraint_options['maxWidthMessage']  = $fc_constraint->getMessage();
        $constraint_options['minHeightMessage'] = $fc_constraint->getMessage();
        $constraint_options['maxHeightMessage'] = $fc_constraint->getMessage();
        $constraint_options['minRatioMessage']  = $fc_constraint->getMessage();
        $constraint_options['maxRatioMessage']  = $fc_constraint->getMessage();

        $params = $fc_constraint->getParams();

        if (!empty($params['min_width'])) {
            $constraint_options['minWidth'] = $params['min_width'];
        }

        if (!empty($params['max_width'])) {
            $constraint_options['maxWidth'] = $params['max_width'];
        }

        if (!empty($params['min_height'])) {
            $constraint_options['minHeight'] = $params['min_height'];
        }

        if (!empty($params['max_height'])) {
            $constraint_options['maxHeight'] = $params['max_height'];
        }

        if (!empty($params['ratio'])) {
            $constraint_options['minRatio'] = $params['ratio'];
            $constraint_options['maxRatio'] = $params['ratio'];
        }

        return $constraint_options;
    }
}