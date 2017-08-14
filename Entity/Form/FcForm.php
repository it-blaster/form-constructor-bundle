<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:24
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Form;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Form
 *
 * @ORM\Table(name="fc_form")
 * @ORM\Entity
 */
class FcForm
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, unique=true)
     */
    protected $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=15)
     */
    protected $method;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255, nullable=true)
     */
    protected $action;

    /**
     * @var string
     *
     * @ORM\Column(name="button", type="string", length=255, nullable=true)
     */
    protected $button;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    protected $message;


    /**
     * @var int
     *
     * @ORM\Column(name="is_ajax", type="boolean")
     */
    protected $isAjax;

    /**
     * @var int
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @var int
     *
     * @ORM\Column(name="is_widget", type="boolean")
     */
    protected $isWidget;

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
     * @var FcFormEventListener
     */
    protected $events;

    /**
     * @var FcFormTemplate[]
     */
    protected $templates;

    /**
     * @var \Fenrizbes\FormConstructorBundle\Entity\Field\FcField[]
     */
    protected $fields;

    /**
     * @var \Fenrizbes\FormConstructorBundle\Entity\Request\FcRequest[]
     */
    protected $requests;

    /**
     * @var \Fenrizbes\FormConstructorBundle\Entity\Request\FcRequestSetting[]
     */
    protected $requestsSettings;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
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
     * @return string
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * @param string $button
     */
    public function setButton($button)
    {
        $this->button = $button;
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
     * @return int
     */
    public function getIsAjax()
    {
        return $this->isAjax;
    }

    /**
     * @param int $isAjax
     */
    public function setIsAjax($isAjax)
    {
        $this->isAjax = $isAjax;
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
     * @return int
     */
    public function getIsWidget()
    {
        return $this->isWidget;
    }

    /**
     * @param int $isWidget
     */
    public function setIsWidget($isWidget)
    {
        $this->isWidget = $isWidget;
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