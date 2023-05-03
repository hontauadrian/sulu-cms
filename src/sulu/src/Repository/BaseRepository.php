<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Event\BaseEntityCreatedEvent;
use App\Domain\Event\BaseEntityModifiedEvent;
use App\Domain\Event\BaseEntityRemovedEvent;
use App\Entity\BaseEntity as Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Bundle\ActivityBundle\Application\Collector\DomainEventCollectorInterface;

abstract class BaseRepository extends ServiceEntityRepository implements CrudRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass, private readonly DomainEventCollectorInterface $domainEventCollector)
    {
        parent::__construct($registry, $entityClass);
    }

    abstract protected function setEntityData(Entity $entity, array $data): void;

    /**
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id)
    {
        $entityAliasName = $this->getEntityAliasName();

        return $this->createQueryBuilder($entityAliasName)
            ->where("$entityAliasName.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getArrayCollectionFromIds(?array $entityIds = null): ArrayCollection
    {
        $entityAliasName = $this->getEntityAliasName();
        $result = $this->createQueryBuilder($entityAliasName)
            ->where("$entityAliasName.id IN (:entityIds)")
            ->setParameter('entityIds', $entityIds)
            ->getQuery()
            ->execute();

        return new ArrayCollection($result);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function update(int $id, array $data): Entity
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findOneById($id);
        $this->setEntityData($entity, $data);
        $entityManager->flush();

        $this->domainEventCollector->collect(
            new BaseEntityModifiedEvent($entity, $data),
        );
        $this->domainEventCollector->dispatch();

        return $entity;
    }

    public function create(array $data, bool $flush = true, bool $explicitSetId = false): Entity
    {
        $entity = new ($this->getEntityName());
        $this->setEntityData($entity, $data);
        $entityManager = $this->getEntityManager();
        if ($explicitSetId) {
            $metadata = $entityManager->getClassMetaData(\get_class($entity));
            $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());
        }
        $entityManager->persist($entity);

        if ($flush) {
            $entityManager->flush();
        }

        $this->domainEventCollector->collect(
            new BaseEntityCreatedEvent($entity, $data),
        );
        $this->domainEventCollector->dispatch();

        return $entity;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function duplicate(int $id): Entity
    {
        $entity = $this->findOneById($id);
        $data = $entity->toArray();

        if (!empty($data['name'])) {
            $data['name'] = $data['name'] . ' - DUPLICATED';
        }
        if (!empty($data['headline'])) {
            $data['headline'] = $data['headline'] . ' - DUPLICATED';
        }

        if (!empty($data['title'])) {
            $data['title'] = $data['title'] . ' - DUPLICATED';
        }

        return $this->create($data);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function delete(int $id): void
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findOneById($id);

        $this->domainEventCollector->collect(
            new BaseEntityRemovedEvent($entity, []),
        );
        $this->domainEventCollector->dispatch();

        $entityManager->remove($entity);
        $entityManager->flush();
    }

    private function getEntityAliasName(): string
    {
        return \sprintf('%s_alias', $this->getClassMetadata()?->table['name']);
    }
}
