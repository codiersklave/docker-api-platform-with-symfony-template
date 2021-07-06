<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract base class for database entities.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractEntity implements DatabaseEntityInterface
{
    /**
     * The internal (i.e. sequential, numeric) ID of the entity in the database. The internal ID should only be used in
     * internal routes (e.g. in admin routes).
     *
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="`internal_id`", type="integer", nullable=true)
     */
    protected ?int $internalId = null;
    
    /**
     * The external (i.e. non-sequential, non-numeric) ID of the entity. The external ID should be used in all public
     * routes.
     *
     * @var string|null
     *
     * @ORM\Column(name="`external_id`", type="string", length=36, nullable=true, unique=true)
     * @Assert\Length(min=1, max=36)
     */
    protected ?string $externalId = null;
    
    /**
     * The creation date of the entity.
     *
     * @var DateTime|null
     *
     * @ORM\Column(name="`created_at`", type="datetime", nullable=true)
     * @Assert\Type(type="DateTime")
     */
    protected ?DateTime $createdAt = null;
    
    /**
     * The date the entity has last been updated.
     *
     * @var DateTime|null
     *
     * @ORM\Column(name="`updated_at`", type="datetime", nullable=true)
     * @Assert\Type(type="Datetime")
     */
    protected ?DateTime $updatedAt = null;
    
    /**
     * @inheritDoc
     */
    public function getInternalId(): ?int
    {
        return $this->internalId;
    }
    
    /**
     * @inheritDoc
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
    
    /**
     * @inheritDoc
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @inheritDoc
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * @inheritDoc
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
