<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type\FcField\Admin;

use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class FieldCommonType extends AbstractType
{
    protected $action;

    /**
     * @var ParamsBuilder
     */
    protected $params_builder;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct($action, TranslatorInterface $translator, ParamsBuilder $params_builder = null)
    {
        $this->action         = $action;
        $this->translator     = $translator;
        $this->params_builder = $params_builder;
    }

    public function getName()
    {
        return 'fc_field';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'    => false,
            'translation_domain' => 'FenrizbesFormConstructorBundle',
            'data_class'         => 'Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcField'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->action)

            ->add('type', 'hidden')

            ->add('label', null, array(
                'label' => 'fc.label.admin.field.label'
            ))
            ->add('name', null, array(
                'label'       => 'fc.label.admin.field.name',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    )),
                    new Regex(array(
                        'pattern' => '/^[\w\-]+$/',
                        'message' => 'fc.constraint.admin.not_alphanumeric'
                    ))
                )
            ))
            ->add('hint', null, array(
                'label' => 'fc.label.admin.field.hint'
            ))
            ->add('is_active', null, array(
                'label'    => 'fc.label.admin.field.is_active',
                'required' => false
            ))

            ->add('params', $this->params_builder, array(
                'label' => false
            ))

            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'validate'))
        ;
    }

    public function validate(FormEvent $event)
    {
        try {
            $this->checkDuplicates($event->getForm()->getData());
        } catch (\Exception $e) {
            $this->addDuplicatesError($event, $e);
        }
    }

    protected function checkDuplicates(FcField $fc_field)
    {
        $roots = array();

        $this->findRoots($fc_field, $roots);

        foreach ($roots as $root) {
            $fields = array();

            $this->findDuplicates($root, $fields);
        }
    }

    protected function findRoots(FcField $fc_field, &$roots)
    {
        $fc_form = $fc_field->getFcForm();

        if ($fc_form->isUsedAsWidget()) {
            foreach ($fc_form->getEntrances() as $entrance) {
                $this->findRoots($entrance, $roots);
            }
        } else {
            $roots[] = $fc_form;
        }
    }

    protected function findDuplicates(FcForm $fc_form, &$fields)
    {
        foreach ($fc_form->getFcFields() as $fc_field) {
            if ($fc_field->isCustom()) {
                $this->findDuplicates($fc_field->getCustomWidget(), $fields);
            } else {
                if (!in_array($fc_field->getName(), $fields)) {
                    $fields[] = $fc_field->getName();
                } else {
                    throw new \Exception(
                        $this->translator->trans('fc.constraint.admin.not_unique_field_name', array(), 'validators')
                    );
                }
            }
        }
    }

    protected function addDuplicatesError(FormEvent $event, \Exception $e)
    {
        $form  = $event->getForm();
        $error = new FormError($e->getMessage());

        if ($form->has('name')) {
            $form->get('name')->addError($error);
        } else {
            $form->addError($error);
        }
    }
}