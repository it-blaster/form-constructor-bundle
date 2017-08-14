<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:40
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;
use Fenrizbes\FormConstructorBundle\Entity\Form\FcForm;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Request
 *
 * @ORM\Table(name="fc_request")
 * @ORM\Entity
 */
class FcRequest
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=16)
     */
    protected $ip;

    /**
     * @var FcForm
     *
     * @ORM\ManyToOne(targetEntity="\Fenrizbes\FormConstructorBundle\Entity\Form\FcForm")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     */
    protected $form;

    /**
     * @ORM\Column(name="data", type="object", nullable=true)
     *
     * @var array
     */
    protected $data;

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
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
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
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
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