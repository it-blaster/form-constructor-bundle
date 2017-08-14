<?php
/**
 * Created by PhpStorm.
 * User: dmyasnikov
 * Date: 14.08.17
 * Time: 14:41
 */

namespace Fenrizbes\FormConstructorBundle\Entity\Request;

use Doctrine\ORM\Mapping as ORM;
use Fenrizbes\FormConstructorBundle\Entity\Form\FcForm;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Request
 *
 * @ORM\Table(name="fc_request_setting")
 * @ORM\Entity
 */
class FcRequestSetting
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
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     */
    protected $form;

    /**
     * @ORM\Column(name="settings", type="object", nullable=true)
     *
     * @var array
     */
    protected $settings;

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
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
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