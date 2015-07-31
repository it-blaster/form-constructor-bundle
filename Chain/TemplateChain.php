<?php

namespace Fenrizbes\FormConstructorBundle\Chain;

use Fenrizbes\FormConstructorBundle\Item\Template\AbstractTemplate;
use Fenrizbes\FormConstructorBundle\Item\ParamsBuilder;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

class TemplateChain
{
    /**
     * @var AbstractTemplate[]
     */
    private $templates = array();

    /**
     * @param AbstractTemplate $template
     * @param string $alias
     */
    public function addTemplate(AbstractTemplate $template, $alias)
    {
        $this->templates[$alias] = $template;
    }

    /**
     * @param string $alias
     * @return AbstractTemplate
     * @throws \Exception
     */
    public function getTemplate($alias)
    {
        if (!isset($this->templates[$alias])) {
            throw new \Exception('Template "'. $alias .'" not found');
        }

        return $this->templates[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasTemplate($alias)
    {
        return isset($this->templates[$alias]);
    }

    /**
     * @return AbstractTemplate[]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param string $alias
     * @param FcForm $fc_form
     * @return ParamsBuilder
     */
    public function getParamsBuilder($alias, FcForm $fc_form)
    {
        return new ParamsBuilder(
            $this->getTemplate($alias),
            $fc_form
        );
    }
}