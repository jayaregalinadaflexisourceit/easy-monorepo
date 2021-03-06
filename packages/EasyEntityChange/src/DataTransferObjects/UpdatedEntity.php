<?php

declare(strict_types=1);

namespace EonX\EasyEntityChange\DataTransferObjects;

/**
 * This DTO represents a single entity which has been updated and includes an array
 * of properties that have been detected to have changed by Doctrine.
 */
final class UpdatedEntity extends ChangedEntity
{
    /**
     * @var string[]
     */
    private $changedProperties;

    /**
     * @param string[] $changedProperties
     * @param mixed[] $ids
     *
     * @phpstan-param class-string $class
     */
    public function __construct(array $changedProperties, string $class, array $ids)
    {
        parent::__construct($class, $ids);

        $this->changedProperties = $changedProperties;
    }

    /**
     * @return string[]
     */
    public function getChangedProperties(): array
    {
        return $this->changedProperties;
    }
}
