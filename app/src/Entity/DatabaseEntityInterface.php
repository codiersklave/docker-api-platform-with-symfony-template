<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

/**
 * Interface for (Doctrine) database entities.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
interface DatabaseEntityInterface
{
    /**
     * Returns the creation date of the entity or NULL if none has been set. This property is set automatically by the
     * entity lifecycle listener.
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime;

    /**
     * Set the creation date of the entity. This method should not be called manually. The entity lifecycle listener
     * will call it automatically.
     * @param DateTime|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTime $createdAt): self;

    /**
     * Returns the ID.
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Returns the date of the last update or NULL if none has been set. This property is usually set automatically by
     * the entity lifecycle listener. If the entity has not yet been updated, this property will contain the creation
     * date.
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime;

    /**
     * Set the date the entity has been last updated. This method should not be called manually. The entity lifecycle
     * listener will call it automatically. Please note that on update, the entity lifcecycle listener will overwrite
     * any values set manually!
     * @param DateTime|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?DateTime $updatedAt): self;
}
