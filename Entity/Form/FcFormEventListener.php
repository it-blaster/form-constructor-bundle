<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:25
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Form;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FormEventListener
 *
 * @ORM\Table(name="fc_form_event_listener")
 * @ORM\Entity
 */
class FcFormEventListener
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
     * @ORM\ManyToOne(targetEntity="FcForm")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     */
    protected $form;

    /**
     * @var string
     *
     * @ORM\Column(name="listener", type="string", length=255)
     */
    protected $listener;

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
     * @return mixed
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * @param mixed $listener
     */
    public function setListener($listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
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