<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:21
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FormBehavior
 *
 * @ORM\Table(name="fc_form_behavior_action")
 * @ORM\Entity
 */
class FcFormBehaviorAction
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
     * @var FcFormBehavior
     *
     * @ORM\ManyToOne(targetEntity="FcFormBehavior")
     * @ORM\JoinColumn(name="behavior_id", referencedColumnName="id")
     */
    protected $behavior;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    protected $action;

    /**
     * @ORM\Column(name="params", type="object", nullable=true)
     *
     * @var array
     */
    protected $params;

    /**
     * @var int
     *
     * @ORM\Column(name="check", type="boolean")
     */
    protected $check;

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
     * @return FcFormBehavior
     */
    public function getBehavior()
    {
        return $this->behavior;
    }

    /**
     * @param FcFormBehavior $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
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
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param int $check
     */
    public function setCheck($check)
    {
        $this->check = $check;
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