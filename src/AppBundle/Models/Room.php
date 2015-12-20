<?php
namespace AppBundle\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Room
 *
 * @package AppBundle\Models
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 * @ORM\HasLifecycleCallbacks
 */
class Room implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="room_number", type="string")
     * @var string
     */
    protected $roomNumber;

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
     * @ORM\ManyToOne(targetEntity="RoomType", inversedBy="rooms")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     * @var RoomType
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="devices")
     * @var Device[]
     */
    protected $devices;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rooms")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * Room constructor.
     */
    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * @return RoomType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $roomNumber
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;
    }

    /**
     * @param RoomType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return Device[]
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @param $editedBy
     */
    public function setEditedBy($editedBy)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdDate = new \DateTime();
        $this->editedDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->editedDate = new \DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'roomNumber' => $this->roomNumber,
            'type'       => $this->type,
            'user'       => $this->user,
            'createdBy'   => $this->createdBy != null ? $this->createdBy->getUsername() : '?',
            'editedBy'    => $this->editedBy != null ? $this->editedBy->getUsername() : '?',
            'createdDate' => $this->createdDate->format('d.m.Y-H:i:s'),
            'editedDate'  => $this->editedDate->format('d.m.Y-H:i:s'),
        ];
    }
}
