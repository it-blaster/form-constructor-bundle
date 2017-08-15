<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:23
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Fenrizbes\FormConstructorBundle\Entity\Form\FcForm;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Field
 *
 * @ORM\Table(name="fc_field")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class FcField
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var FcForm
     *
     * @ORM\ManyToOne(targetEntity="\Fenrizbes\FormConstructorBundle\Entity\Form\FcForm")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id", unique=true)
     */
    protected $form;

    /**
     * @var string
     *
     * @ORM\Column(name="$ype", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", length=1000, nullable=true)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", nullable=true)
     */
    protected $hint;

    /**
     * @ORM\Column(name="params", type="object", nullable=true)
     *
     * @var array
     */
    protected $params;

    /**
     * @var int
     *
     * @Gedmo\Sortable(groups={"form"})
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @var int
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $cteatedAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

//====== Back assets ======//

    /**
     * @var FcFieldConstraint[]
     *
     * @ORM\OneToMany(targetEntity="FcFieldConstraint", mappedBy="field", fetch="EAGER")
     */
    protected $constraints;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FcForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FcForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * @param string $hint
     */
    public function setHint($hint)
    {
        $this->hint = $hint;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param int $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return \DateTime
     */
    public function getCteatedAt()
    {
        return $this->cteatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}