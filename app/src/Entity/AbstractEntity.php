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
 */
#[ORM\MappedSuperclass]
abstract class AbstractEntity implements DatabaseEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "`id`", type: "integer", nullable: true)]
    protected ?int $id = null;

    #[ORM\Column(name: "`created_at`", type: "datetime", nullable: true)]
    #[Assert\Type("DateTime")]
    protected ?DateTime $createdAt = null;

    #[ORM\Column(name: "`updated_at`", type: "datetime", nullable: true)]
    #[Assert\Type("DateTime")]
    protected ?DateTime $updatedAt = null;
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param DateTime|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * @param DateTime|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
