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
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="`id`", type="integer", nullable=true)
     */
    protected ?int $id = null;

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
    public function getId(): ?int
    {
        return $this->id;
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
