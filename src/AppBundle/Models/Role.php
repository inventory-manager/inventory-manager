<?php
namespace AppBundle\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Role
 *
 * @package AppBundle\Models
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role implements \JsonSerializable, RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="role_name", type="string", length=20)
     * @Assert\Length(max=20)
     * @Assert\Regex(pattern="/[A-Z]+/")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string", length=120)
     * @Assert\Length(max=120)
     * @var string
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles", cascade={"persist"})
     * @var User[]
     */
    protected $users;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    public function jsonSerialize()
    {
        return [
            'name'        => $this->name,
            'description' => $this->description
        ];
    }

    public function getRole()
    {
        return $this->name;
    }
}
