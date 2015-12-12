<?php
namespace AppBundle\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @package AppBundle\Models
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User implements \JsonSerializable, UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="username", type="string", length=32, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(name="first_name", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     * @var string
     */
    protected $lastName;

    /**
     * @ORM\Column(name="email", type="string", length=40, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email
     * @Assert\Length(max=40)
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(name="password", type="string", length=60, nullable=false))
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=60)
     * @var string
     */
    protected $password;

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
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(
     *   name="users_roles",
     *   joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="role_name", referencedColumnName="role_name")}
     * )
     * @var Role[]
     */
    protected $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * User-factory
     *
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     *
     * @return User
     */
    public static function createUser($username, $firstName, $lastName, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->password = $password;
        return $user;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * @param User $editedBy
     */
    public function setEditedBy($editedBy)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     */
    public function addToRoles(Role $role)
    {
        $this->roles[] = $role;
    }

    /**
     * @param Role $role
     */
    public function removeFromRoles(Role $role)
    {
        $this->roles->removeElement($role);
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
            'id'          => $this->id,
            'username'    => $this->username,
            'firstName'   => $this->firstName,
            'lastName'    => $this->lastName,
            'email'       => $this->email,
            'createdBy'   => $this->createdBy,
            'editedBy'    => $this->editedBy,
            'roles'       => $this->roles->toArray()
        ];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
