<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\PersonRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="`person`")
 */
#[ApiResource(
    collectionOperations: [
        "get" => ["normalization_context" => ["groups" => "people_listing:read-list"]],
        "post",
    ],
    itemOperations: ["get", "put", "delete"],
    attributes: [
        "pagination_items_per_page" => 5,
    ],
    denormalizationContext: ["groups" => ["people_listing:write"]],
    normalizationContext: ["groups" => ["people_listing:read"]]
)]
#[ApiFilter(BooleanFilter::class, "isActive")]
#[ApiFilter(SearchFilter::class, properties: ["familyName" => "partial", "birthName" => "partial"])]
#[ApiFilter(PropertyFilter::class)]
class Person extends AbstractEntity
{
    const GENDERS = ['female', 'male'];

    /**
     * Family name (i.e. the last name of a person).
     *
     * @ORM\Column(name="`family_name`", type="string", length=64)
     */
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write", "people_listing:read-list"])]
    private string $familyName;

    /**
     * Given name (i.e. the first name of a person).
     *
     * @ORM\Column(name="`given_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write", "people_listing:read-list"])]
    private ?string $givenName;

    /**
     * An additional name for a Person, can be used for a middle name.
     *
     * @ORM\Column(name="`additional_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?string $additionalName;

    /**
     * The person's gender. Can be either 'male' or 'female'. Leave empty (null), if none applies.
     *
     * @ORM\Column(name="`gender`", type="string", length=6, nullable=true)
     */
    #[Assert\Choice(choices: Person::GENDERS)]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?string $gender;

    /**
     * An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
     *
     * @ORM\Column(name="`honorific_prefix`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?string $honorificPrefix;

    /**
     * An honorific suffix following a Person's name such as M.D. /PhD/MSCSW.
     *
     * @ORM\Column(name="`honorific_suffix`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?string $honorificSuffix;

    /**
     * Date of birth.
     *
     * @ORM\Column(name="`birth_date`", type="date", nullable=true)
     */
    #[Assert\Type("DateTime")]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?DateTime $birthDate;

    /**
     * The person's name at birth.
     *
     * @ORM\Column(name="`birth_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write"])]
    private ?string $birthName;

    /**
     * Whether or not the person is active.
     *
     * @ORM\Column(name="`is_active`", type="boolean")
     */
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["people_listing:read", "people_listing:write", "people_listing:read-list"])]
    private bool $isActive = true;

    /**
     * @return int|null
     */
    #[Groups(["people_listing:read", "people_listing:read-list"])]
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @param string $familyName
     * @return Person
     */
    public function setFamilyName(string $familyName): Person
    {
        $this->familyName = $familyName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    /**
     * @param string|null $givenName
     * @return Person
     */
    public function setGivenName(?string $givenName): Person
    {
        $this->givenName = $givenName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalName(): ?string
    {
        return $this->additionalName;
    }

    /**
     * @param string|null $additionalName
     * @return Person
     */
    public function setAdditionalName(?string $additionalName): Person
    {
        $this->additionalName = $additionalName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     * @return Person
     */
    public function setGender(?string $gender): Person
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHonorificPrefix(): ?string
    {
        return $this->honorificPrefix;
    }

    /**
     * @param string|null $honorificPrefix
     * @return Person
     */
    public function setHonorificPrefix(?string $honorificPrefix): Person
    {
        $this->honorificPrefix = $honorificPrefix;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHonorificSuffix(): ?string
    {
        return $this->honorificSuffix;
    }

    /**
     * @param string|null $honorificSuffix
     * @return Person
     */
    public function setHonorificSuffix(?string $honorificSuffix): Person
    {
        $this->honorificSuffix = $honorificSuffix;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime|null $birthDate
     * @return Person
     */
    public function setBirthDate(?DateTime $birthDate): Person
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    /**
     * @param string|null $birthName
     * @return Person
     */
    public function setBirthName(?string $birthName): Person
    {
        $this->birthName = $birthName;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return Person
     */
    public function setIsActive(bool $isActive): Person
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    #[Groups(["people_listing:read"])]
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * The time that passed since the entity's creation in a human-readable format.
     *
     * @return string|null
     */
    #[Groups(["people_listing:read"])]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return DateTime|null
     */
    #[Groups(["people_listing:read"])]
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * The time that passed since the entity's last update in a human-readable format.
     *
     * @return string|null
     */
    #[Groups(["people_listing:read"])]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
}
