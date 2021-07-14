<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Enum\Gender;
use App\Repository\PersonRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: "`person`")]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["people_listing:read"]
            ]
        ],
        "post" => [
            "normalization_context" => [
                "groups" => ["people_listing:write"]
            ]
        ],
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["people_details:read"]
            ]
        ],
        "put" => [
            "normalization_context" => [
                "groups" => ["people_details:write"]
            ]
        ],
        "delete",
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ["active"])]
#[ApiFilter(SearchFilter::class, properties: ["familyName" => "partial", "gender" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["birthDate"])]
class Person extends AbstractEntity
{
    #[ORM\Column(name: "`family_name`", type: "string", length: 64)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write", "people_details:read", "people_details:write"])]
    private string $familyName;
    
    #[ORM\Column(name: "`given_name`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:read", "people_listing:write", "people_details:read", "people_details:write"])]
    private ?string $givenName;
    
    #[ORM\Column(name: "`additional_name`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:write", "people_details:read", "people_details:write"])]
    private ?string $additionalName;
    
    #[ORM\Column(name: "`honorific_prefix`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:write", "people_details:read", "people_details:write"])]
    private ?string $honorificPrefix;
    
    #[ORM\Column(name: "`honorific_suffix`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["people_listing:write", "people_details:read", "people_details:write"])]
    private ?string $honorificSuffix;
    
    #[ORM\Column(name: "`gender`", type: "string", length: 16, nullable: true)]
    #[Assert\Length(min: 1, max: 16)]
    #[Assert\Choice(callback: [Gender::class, "getEnumMap"])]
    #[Groups(["people_listing:read", "people_listing:write", "people_details:read", "people_details:write"])]
    private ?string $gender;
    
    #[ORM\Column(name: "`birth_date`", type: "date", nullable: true)]
    #[Assert\Type("DateTime")]
    #[Groups(["people_listing:write", "people_details:read", "people_details:write"])]
    private ?DateTime $birthDate;
    
    #[ORM\Column(name: "`is_active`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["people_listing:read", "people_listing:write", "people_details:read", "people_details:write"])]
    private bool $active = true;
    
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
    
    /**
     * @param bool $active
     * @return Person
     */
    public function setActive(bool $active): Person
    {
        $this->active = $active;
        return $this;
    }
}
