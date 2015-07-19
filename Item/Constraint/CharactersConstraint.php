<?php

namespace Fenrizbes\FormConstructorBundle\Item\Constraint;

use Fenrizbes\FormConstructorBundle\Propel\Model\Field\FcFieldConstraint;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CharactersConstraint extends RegexConstraint
{
    protected $patterns = array(
        'cyrillic'   => 'а-яё',
        'latin'      => 'a-z',
        'digit'      => '\d',
        'space'      => '\s',
        'dash'       => '\-',
        'underscore' => '_'
    );

    public function getName()
    {
        return 'fc.label.constraints.characters';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sets', 'choice', array(
                'label'    => 'fc.label.constraints.characters_sets',
                'required' => true,
                'multiple' => true,
                'choices'  => array(
                    'cyrillic'   => 'fc.label.constraints.characters_sets_cyrillic',
                    'latin'      => 'fc.label.constraints.characters_sets_latin',
                    'digit'      => 'fc.label.constraints.characters_sets_digit',
                    'space'      => 'fc.label.constraints.characters_sets_space',
                    'dash'       => 'fc.label.constraints.characters_sets_dash',
                    'underscore' => 'fc.label.constraints.characters_sets_underscore'
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
            ->add('match', 'choice', array(
                'label'    => 'fc.label.constraints.characters_match',
                'required' => true,
                'choices'  => array(
                    1 => 'fc.label.constraints.characters_match_true',
                    0 => 'fc.label.constraints.characters_match_false',
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'fc.constraint.admin.blank'
                    ))
                )
            ))
        ;
    }

    public function buildConstraint(&$options, FcFieldConstraint $fc_constraint)
    {
        $params = $fc_constraint->getParams();

        $this->match   = (bool) $params['match'];
        $this->pattern = $this->buildPattern($params['sets']);

        parent::buildConstraint($options, $fc_constraint);
    }

    protected function buildPattern(array $sets)
    {
        $pattern = '/'. ($this->match ? '^' : '') .'[';

        foreach ($sets as $set) {
            if (isset($this->patterns[$set])) {
                $pattern .= $this->patterns[$set];
            }
        }

        return $pattern .']'. ($this->match ? '+$' : '') .'/ui';
    }
}