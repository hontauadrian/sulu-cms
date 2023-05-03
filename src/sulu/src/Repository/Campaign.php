<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BaseEntity;
use App\Entity\Campaign as CampaignEntity;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Bundle\ActivityBundle\Application\Collector\DomainEventCollectorInterface;

class Campaign extends BaseRepository
{
    public function __construct(
        ManagerRegistry $registry,
        DomainEventCollectorInterface $domainEventCollector,
    ) {
        parent::__construct($registry, CampaignEntity::class, $domainEventCollector);
    }

    /**
     * @throws \Exception
     */
    protected function setEntityData(CampaignEntity|BaseEntity $entity, array $data): void
    {
        if (!empty($data['id'])) {
            $entity->setId($data['id']);
        }
        $entity->setTitle($data['title']);
        $entity->setIsActive($data['isActive']);
        $entity->setIsCloseable($data['isCloseable']);
        $entity->setFileKey($data['fileKey']);
        $entity->setIosMinVersion($data['iosMinVersion']);
        $entity->setAndroidMinVersion($data['androidMinVersion']);
        $entity->setSection($data['section']);
        $entity->setCustomCssClass($data['customCssClass']);
        $entity->setNotificationType($data['notificationType']);
        $entity->setCampaignId($data['campaignId']);
        $entity->setParams($data['params']);
        $entity->setExternalId($data['externalId']);
        $entity->setStateData($data['stateData']);
        $entity->setServiceId($data['serviceId']);
        $entity->setIsDependentOnExternalProvider($data['isDependentOnExternalProvider']);

        if ($data['startDate']) {
            $entity->setStartDate(new \DateTimeImmutable($data['startDate']));
        }
        if ($data['endDate']) {
            $entity->setEndDate(new \DateTimeImmutable($data['endDate']));
        }
    }
}
