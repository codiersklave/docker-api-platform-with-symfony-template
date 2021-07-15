<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\CheeseListingRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CheeseListingRepository::class)]
#[ORM\Table(name: "`cheese_listing`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "patch"],
    denormalizationContext: ["groups" => ["cheese_listing:write"]],
    normalizationContext: ["groups" => ["cheese_listing:read"]]
)]
#[ApiFilter(BooleanFilter::class, properties: ["isPublished"])]
#[ApiFilter(SearchFilter::class, properties: ["title" => "partial"])]
#[ApiFilter(RangeFilter::class, properties: ["price"])]
#[ApiFilter(PropertyFilter::class)]
class CheeseListing extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "cheeseListings")]
    #[ORM\JoinColumn(name: "`owner_id`", referencedColumnName: "id", nullable: false)]
    #[Groups(["cheese_listing:read", "cheese_listing:write"])]
    private User $owner;

    #[ORM\Column(name: "`title`", type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["cheese_listing:read", "cheese_listing:write"])]
    private string $title;

    #[ORM\Column(name: "`description`", type: "text", length: 255)]
    #[Assert\NotBlank]
    #[Groups(["cheese_listing:read", "cheese_listing:write"])]
    private string $description;

    #[ORM\Column(name: "`price`", type: "integer")]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(["cheese_listing:read", "cheese_listing:write"])]
    private int $price;

    #[ORM\Column(name: "`is_published`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["cheese_listing:read", "cheese_listing:write"])]
    private bool $published = false;

    /**
     * We override this method simply to be able to add it to a group.
     * @TODO: Find out if there is a better to do this...
     */
    #[Groups(["cheese_listing:read"])]
    public function getId(): ?int
    {
        return parent::getId();
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return CheeseListing
     */
    public function setOwner(User $owner): CheeseListing
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CheeseListing
     */
    public function setTitle(string $title): CheeseListing
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CheeseListing
     */
    public function setDescription(string $description): CheeseListing
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return CheeseListing
     */
    public function setPrice(int $price): CheeseListing
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     * @return CheeseListing
     */
    public function setPublished(bool $published): CheeseListing
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return string|null
     */
    #[Groups(["cheese_listing:read"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["cheese_listing:read"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
