<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BaseEntity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

interface CrudRepositoryInterface
{
    public function findOneById(int $id);

    public function getArrayCollectionFromIds(?array $entityIds = null): ArrayCollection;

    public function update(int $id, array $data): Entity;

    public function create(array $data): Entity;

    public function delete(int $id): void;
}
