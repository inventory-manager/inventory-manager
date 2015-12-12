<?php
namespace AppBundle\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Article
 *
 * @package AppBundle\Models
 * @ORM\Entity
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks
 */
class Article implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="article_number", type="string", length=40)
     * @Assert\Length(max=40)
     * @var string
     */
    protected $articleNumber;

    /**
     * @ORM\Column(name="description", type="string", length=120)
     * @Assert\Length(max=120)
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     * @Assert\Length(max=60)
     * @Assert\NotBlank()
     * @var string
     */
    protected $name;

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
     * @ORM\ManyToOne(targetEntity="ArticleCategory", inversedBy="articles")
     * @ORM\JoinColumn(name="article_category", referencedColumnName="id")
     * @var ArticleCategory
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="articles")
     * @var Device[]
     */
    protected $devices;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getArticleNumber()
    {
        return $this->articleNumber;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return ArticleCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return Device[]
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param string $articleNumber
     */
    public function setArticleNumber($articleNumber)
    {
        $this->articleNumber = $articleNumber;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @param string $comment
     */

    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    /**
     * @param ArticleCategory $category
     */

    public function setCategory($category)
    {
        $this->category = $category;
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
            'articleNumber' => $this->articleNumber,
            'description'   => $this->description,
            'name'          => $this->name,
            'comment'       => $this->comment,
            'category'      => $this->category,
        ];
    }
}
