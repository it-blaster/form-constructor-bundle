<?php

namespace Fenrizbes\FormConstructorBundle\Item;

use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParamsBuilder extends AbstractType
{
    /**
     * @var ItemInterface
     */
    protected $item;

    public function __construct(ItemInterface $item, FcForm $fc_form)
    {
        $item->setFcForm($fc_form);

        $this->item = $item;
    }

    public function getName()
    {
        return 'params';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'FenrizbesFormConstructorBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->item->buildForm($builder, $options);
    }
}