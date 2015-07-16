<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

use Fenrizbes\FormConstructorBundle\Form\DataTransformer\FcCollectionDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FcCollectionType extends AbstractType
{
    public function getName()
    {
        return 'fc_collection';
    }

    public function getParent()
    {
        return 'collection';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_add'    => true,
            'allow_delete' => false,
            'delete_empty' => false
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(
            new FcCollectionDataTransformer()
        );
    }
}