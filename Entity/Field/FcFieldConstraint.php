<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:23
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Field;

/**
 * FieldConstraint
 *
 * @ORM\Table(name="fc_field_constraint")
 * @ORM\Entity
 */
class FcFieldConstraint
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
     * @var FcField
     *
     * @ORM\ManyToOne(targetEntity="FcField")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    protected $field;

    /**
     * @var string
     *
     * @ORM\Column(name="constraint", type="string", length=255)
     */
    protected $constraint;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    protected $message;

    /**
     * @ORM\Column(name="params", type="object", nullable=true)
     *
     * @var array
     */
    protected $params;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FcField
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param FcField $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * @param string $constraint
     */
    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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