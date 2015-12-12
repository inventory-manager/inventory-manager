<?php
namespace AppBundle\Models;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Device
 *
 * @package AppBundle\Models
 * @ORM\Entity
 * @ORM\Table(name="devices")
 * @ORM\HasLifecycleCallbacks
 */
class Device implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="serial_number", type="string", length=40)
     * @Assert\Length(max=40)
     * @var string
     */
    protected $serialNumber;

    /**
     * @ORM\Column(name="inventory_number", type="string", length=40)
     * @Assert\Length(max=40)
     * @var string
     */
    protected $inventoryNumber;

    /**
     * @ORM\Column(name="buy_date", type="datetime")
     * @Assert\DateTime()
     * @var \DateTime
     */
    protected $buyDate;

    /**
     * @ORM\Column(name="due_date", type="datetime")
     * @Assert\DateTime()
     * @var \DateTime
     */
    protected $dueDate;

    /**
     * @ORM\Column(name="in_use", type="boolean")
     * @var boolean
     */
    protected $inUse;

    /**
     * @ORM\Column(name="comment", type="string", length=240)
     * @Assert\Length(max=240)
     * @var string
     */
    protected $comment;

    /**
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @ORM\Column(name="edited_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var \DateTime
     */
    protected $editedDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * @var User
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="edited_by", referencedColumnName="id")
     * @var User
     */
    protected $editedBy;

    /**
     * @ORM\ManyToOne(targetEntity="DeviceState", inversedBy="devices")
     * @ORM\JoinColumn(name="device_state", referencedColumnName="id")
     * @var DeviceState
     */
    protected $deviceState;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="devices")
     * @ORM\JoinColumn(name="article_number", referencedColumnName="article_number")
     * @var Article
     */
    protected $article;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="rooms")
     * @ORM\JoinColumn(name="room", referencedColumnName="room_number")
     * @var Room
     */
    protected $room;

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
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @return string
     */
    public function getInventoryNumber()
    {
        return $this->inventoryNumber;
    }

    /**
     * @return \DateTime
     */
    public function getBuyDate()
    {
        return $this->buyDate;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return boolean
     */
    public function isInUse()
    {
        return $this->inUse;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return DeviceState
     */
    public function getDeviceState()
    {
        return $this->deviceState;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * @param string $inventoryNumber
     */
    public function setInventoryNumber($inventoryNumber)
    {
        $this->inventoryNumber = $inventoryNumber;
    }

    /**
     * @param \DateTime $buyDate
     */
    public function setBuyDate($buyDate)
    {
        $this->buyDate = $buyDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @param boolean $inUse
     */
    public function setInUse($inUse)
    {
        $this->inUse = $inUse;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param DeviceState $deviceState
     */
    public function setDeviceState($deviceState)
    {
        $this->deviceState = $deviceState;
    }

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @param User $editedBy
     */
    public function setEditedBy($editedBy)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getEditedDate()
    {
        return $this->editedDate;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return User
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * @ORM\PrePersist
     */
    protected function prePersist()
    {
        $this->createdDate = new \DateTime();
        $this->editedDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    protected function preUpdate()
    {
        $this->editedDate = new \DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'id'              => $this->id,
            'serialNumber'    => $this->serialNumber,
            'inventoryNumber' => $this->inventoryNumber,
            'buyDate'         => $this->buyDate,
            'dueDate'         => $this->dueDate,
            'inUse'           => $this->inUse,
            'comment'         => $this->comment,
            'deviceState'     => $this->deviceState,
            'article'         => $this->article
        ];
    }
}