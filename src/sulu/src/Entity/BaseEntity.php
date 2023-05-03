<?php

declare(strict_types=1);

namespace App\Entity;

use Sulu\Component\Persistence\Model\AuditableInterface;

interface BaseEntity extends AuditableInterface
{
    public function getReadableIdentifier(): string;

    public function getId(): ?int;
}
