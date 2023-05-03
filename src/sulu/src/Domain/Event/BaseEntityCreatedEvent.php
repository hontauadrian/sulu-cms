<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Entity\BaseEntity;
use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

class BaseEntityCreatedEvent extends DomainEvent
{
    public const EVENT_TYPE = 'created';

    public function __construct(private readonly BaseEntity $entity, private readonly array $payload)
    {
        parent::__construct();
    }

    public function getEventType(): string
    {
        return self::EVENT_TYPE;
    }

    public function getEventPayload(): ?array
    {
        return $this->payload;
    }

    public function getResourceKey(): string
    {
        return $this->getEntity()::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string) $this->entity->getId();
    }

    public function getEntity(): BaseEntity
    {
        return $this->entity;
    }

    public function getResourceTitle(): ?string
    {
        return $this->getEntity()->getReadableIdentifier();
    }
}
