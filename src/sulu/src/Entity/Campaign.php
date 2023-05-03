<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Sulu\Component\Persistence\Model\AuditableTrait;

#[ORM\Entity]
#[ORM\Table(name: 'app_campaigns')]
class Campaign implements BaseEntity
{
    use AuditableTrait;

    public const RESOURCE_KEY = 'campaigns';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActive = true;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isCloseable = true;

    #[ORM\Column(type: Types::STRING)]
    private string $fileKey;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $iosMinVersion = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $androidMinVersion = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $section = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $customCssClass = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $notificationType = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $campaignId = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $params = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $externalId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stateData = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $serviceId = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDependentOnExternalProvider = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endDate = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function isCloseable(): bool
    {
        return $this->isCloseable;
    }

    public function setIsCloseable(bool $isCloseable): void
    {
        $this->isCloseable = $isCloseable;
    }

    public function getFileKey(): string
    {
        return $this->fileKey;
    }

    public function setFileKey(string $fileKey): void
    {
        $this->fileKey = $fileKey;
    }

    public function getIosMinVersion(): ?string
    {
        return $this->iosMinVersion;
    }

    public function setIosMinVersion(?string $iosMinVersion): void
    {
        $this->iosMinVersion = $iosMinVersion;
    }

    public function getAndroidMinVersion(): ?string
    {
        return $this->androidMinVersion;
    }

    public function setAndroidMinVersion(?string $androidMinVersion): void
    {
        $this->androidMinVersion = $androidMinVersion;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): void
    {
        $this->section = $section;
    }

    public function getCustomCssClass(): ?string
    {
        return $this->customCssClass;
    }

    public function setCustomCssClass(?string $customCssClass): void
    {
        $this->customCssClass = $customCssClass;
    }

    public function getNotificationType(): ?string
    {
        return $this->notificationType;
    }

    public function setNotificationType(?string $notificationType): void
    {
        $this->notificationType = $notificationType;
    }

    public function getCampaignId(): ?int
    {
        return $this->campaignId;
    }

    public function setCampaignId(?int $campaignId): void
    {
        $this->campaignId = $campaignId;
    }

    public function getParams(): ?string
    {
        return $this->params;
    }

    public function setParams(?string $params): void
    {
        $this->params = $params;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getStateData(): ?string
    {
        return $this->stateData;
    }

    public function setStateData(?string $stateData): void
    {
        $this->stateData = $stateData;
    }

    public function getServiceId(): ?string
    {
        return $this->serviceId;
    }

    public function setServiceId(?string $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    public function isDependentOnExternalProvider(): bool
    {
        return $this->isDependentOnExternalProvider;
    }

    public function setIsDependentOnExternalProvider(bool $isDependentOnExternalProvider): void
    {
        $this->isDependentOnExternalProvider = $isDependentOnExternalProvider;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'isActive' => $this->isActive(),
            'isCloseable' => $this->isCloseable(),
            'fileKey' => $this->getFileKey(),
            'iosMinVersion' => $this->getIosMinVersion(),
            'androidMinVersion' => $this->getAndroidMinVersion(),
            'section' => $this->getSection(),
            'customCssClass' => $this->getCustomCssClass(),
            'notificationType' => $this->getNotificationType(),
            'campaignId' => $this->getCampaignId(),
            'params' => $this->getParams(),
            'externalId' => $this->getExternalId(),
            'stateData' => $this->getStateData(),
            'serviceId' => $this->getServiceId(),
            'isDependentOnExternalProvider' => $this->isDependentOnExternalProvider(),
            'startDate' => $this->getStartDate()?->format(\DateTimeInterface::ATOM),
            'endDate' => $this->getEndDate()?->format(\DateTimeInterface::ATOM),
        ];
    }

    public function getReadableIdentifier(): string
    {
        return $this->getTitle();
    }

}
