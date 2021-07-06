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
     * Returns the external (i.e. non-sequential, non-numeric) ID, if it has been set. This property is usually set
     * automatically by the entity lifecycle listener.
     * @return string|null
     */
    public function getExternalId(): ?string;
    
    /**
     * Set the external (i.e. non-sequential, non-numeric) ID of the entity. This method should not be called manually,
     * since the entity lifecycle listener calls it automatically.
     * To generate a new external ID for an entity, set it to NULL and save the entity.
     * @param string|null $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self;
    
    /**
     * Returns the internal (i.e. sequential, numeric) ID, if it has been set. This property is usually set
     * automatically when the entity is stored in the database for the first time.
     * @return int|null
     */
    public function getInternalId(): ?int;
    
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
