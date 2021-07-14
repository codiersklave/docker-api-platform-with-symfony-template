<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
#[ApiResource(
    denormalizationContext: ["groups" => ["user:write"]],
    normalizationContext: ["groups" => ["user:read"]]
)]
class User extends AbstractEntity implements UserInterface
{
    #[ORM\Column(name: "`email`", type: "string", length: 255, unique: true)]
    #[Assert\NotNull]
    #[Assert\Email]
    #[Groups(["user:read", "user:write"])]
    private string $email;

    #[ORM\Column(name: "`username`", type: "string", length: 255, unique: true)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["user:read", "user:write"])]
    private string $username;

    #[ORM\Column(name: "`roles`", type: "json")]
    #[Assert\NotNull]
    #[Assert\Type("array")]
    private array $roles = [];

    #[ORM\Column(name: "`password`", type: "string", length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $password;

    private ?string $plainPassword;

    #[ORM\OneToMany(targetEntity: CheeseListing::class, mappedBy: "owner")]
    private iterable $cheeseListings;

    public function __construct()
    {
        $this->cheeseListings = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    #[Groups(["user:write"])]
    #[SerializedName("password")]
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return ArrayCollection|iterable
     */
    public function getCheeseListings(): iterable|ArrayCollection
    {
        return $this->cheeseListings;
    }

    /**
     * @param ArrayCollection|iterable $cheeseListings
     * @return $this
     */
    public function setCheeseListings(iterable|ArrayCollection $cheeseListings): self
    {
        $this->cheeseListings = $cheeseListings;
        return $this;
    }

    /**
     * @param CheeseListing $cheeseListing
     * @return $this
     */
    public function addCheeseListing(CheeseListing $cheeseListing): self
    {
        if (!$this->cheeseListings->contains($cheeseListing)) {
            $this->cheeseListings[] = $cheeseListing;
            $cheeseListing->setOwner($this);
        }

        return $this;
    }

    /**
     * @param CheeseListing $cheeseListing
     * @return $this
     */
    public function removeCheeseListing(CheeseListing $cheeseListing): self
    {
        if ($this->cheeseListings->contains($cheeseListing)) {
            $this->cheeseListings->removeElement($cheeseListing);
            if ($cheeseListing->getOwner() === $this) {
                $cheeseListing->setOwner(null);
            }
        }

        return $this;
    }
}
