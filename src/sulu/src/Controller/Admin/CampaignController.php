<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Controller\DTO\SuluException;
use App\Entity\Campaign as CampaignEntity;
use App\Repository\Campaign;
use App\Service\EwiCsc\CRUD\Campaign as CscCampaignCrud;
use Doctrine\ORM\Exception\ORMException;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CampaignController extends AbstractController implements ClassResourceInterface
{
    public function __construct(
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        private readonly Campaign $campaignRepository,
    ) {
    }

    #[Route(path: '/admin/api/campaigns/{id}', name: 'app.get_campaign', methods: ['GET'])]
    public function getAction(int $id): Response
    {
        $entity = $this->campaignRepository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $this->json($entity->toArray());
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/admin/api/campaigns/{id}', name: 'app.put_campaign', methods: ['PUT'])]
    public function putAction(Request $request, int $id): Response
    {
        $data = $request->toArray();
        /* @var CampaignEntity $entity */
        $entity = $this->campaignRepository->update($id, $data);

        return $this->json($entity->toArray());
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/admin/api/campaigns', name: 'app.post_campaign', methods: ['POST'])]
    public function postAction(Request $request): Response
    {
        if ($request->query->get('resourceIdsToClone')) {
            $resourceIdsToClone = \explode(',', $request->query->get('resourceIdsToClone'));
            foreach ($resourceIdsToClone as $resourceIdToClone) {
                /* @var CampaignEntity $duplicatedEntity */
                $duplicatedEntity = $this->campaignRepository->duplicate((int) $resourceIdToClone);
            }

            return $this->json(null, 204);
        }

        $data = $request->toArray();
        /* @var CampaignEntity $entity */
        $entity = $this->campaignRepository->create($data);
        return $this->json($entity->toArray(), 201);
    }

    /**
     * @throws ORMException
     */
    #[Route(path: '/admin/api/campaigns/{id}', name: 'app.delete_campaign', methods: ['DELETE'])]
    public function deleteAction(int $id): Response
    {
        $this->campaignRepository->delete($id);

        return $this->json(null, 204);
    }

    #[Route(path: '/admin/api/campaigns', name: 'app.get_campaigns', methods: ['GET'])]
    public function getListAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            CampaignEntity::RESOURCE_KEY,
        );

        return $this->json($listRepresentation->toArray());
    }
}
