<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PersonRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="`person`")
 */
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put", "delete"],
    normalizationContext: ["groups" => ["people_listing:read"]]
)]
class Person extends AbstractEntity
{
    const GENDERS = ['female', 'male'];

    /**
     * @ORM\Column(name="`family_name`", type="string", length=64)
     */
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private string $familyName;

    /**
     * @ORM\Column(name="`given_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private ?string $givenName;

    /**
     * @ORM\Column(name="`additional_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private ?string $additionalName;

    /**
     * @ORM\Column(name="`gender`", type="string", length=6, nullable=true)
     */
    #[Assert\Choice(choices: Person::GENDERS)]
    #[Groups(["people_listing:read"])]
    private ?string $gender;

    /**
     * @ORM\Column(name="`honorific_prefix`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private ?string $honorificPrefix;

    /**
     * @ORM\Column(name="`honorific_suffix`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private ?string $honorificSuffix;

    /**
     * @ORM\Column(name="`birth_date`", type="date", nullable=true)
     */
    #[Assert\Type("DateTime")]
    #[Groups(["people_listing:read"])]
    private ?DateTime $birthDate;

    /**
     * @ORM\Column(name="`birth_name`", type="string", length=64, nullable=true)
     */
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read"])]
    private ?string $birthName;

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
}
