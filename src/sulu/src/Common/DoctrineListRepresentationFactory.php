<?php

declare(strict_types=1);

namespace App\Common;

use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\ORM\EntityManagerInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRestHelperInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RestHelperInterface;

class DoctrineListRepresentationFactory
{
    public function __construct(
        private readonly RestHelperInterface $restHelper,
        private readonly ListRestHelperInterface $listRestHelper,
        private readonly DoctrineListBuilderFactoryInterface $listBuilderFactory,
        private readonly FieldDescriptorFactoryInterface $fieldDescriptorFactory,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<string, string> $filters
     * @param array<string, string|int> $parameters
     * @param string[] $includedFields
     */
    public function createDoctrineListRepresentation(
        string $resourceKey,
        array $filters = [],
        array $parameters = [],
        array $includedFields = [],
    ): PaginatedRepresentation {
        /** @var DoctrineFieldDescriptor[] $fieldDescriptors */
        $fieldDescriptors = $this->fieldDescriptorFactory->getFieldDescriptors($resourceKey);
        /** @var DoctrineListBuilder $listBuilder */
        $listBuilder = $this->listBuilderFactory->create($fieldDescriptors['id']?->getEntityName());
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);

        foreach ($parameters as $key => $value) {
            $listBuilder->setParameter($key, $value);
        }

        foreach ($filters as $key => $value) {
            $listBuilder->where($fieldDescriptors[$key], $value);
        }

        foreach ($includedFields as $field) {
            $listBuilder->addSelectField($fieldDescriptors[$field]);
        }

        // dd($listBuilder);
//        try {
        $items = $listBuilder->execute();
        $items = $this->loadTileRelatedToEntities($items);

        // }catch (SyntaxErrorException $exception){
        //    dd($exception->getQuery());
        // }

        // sort the items to reflect the order of the given ids if the list was requested to include specific ids
        $requestedIds = $this->listRestHelper->getIds();
        if (null !== $requestedIds) {
            $idPositions = \array_flip($requestedIds);

            \usort($items, fn ($a, $b) => $idPositions[$a['id']] - $idPositions[$b['id']]);
        }

        return new PaginatedRepresentation(
            $items,
            $resourceKey,
            (int) $listBuilder->getCurrentPage(),
            (int) $listBuilder->getLimit(),
            (int) $listBuilder->count(),
        );
    }

    private function loadTileRelatedToEntities($items)
    {
        foreach ($items as $key => $item) {
            try {
                if (!empty($item['tilesRelated'])) {
                    $entityArr = \explode('|', $item['tilesRelated']);
                    $entityRepo = $this->entityManager->getRepository('App\Entity\\' . $entityArr[1]);
                    $entity = $entityRepo->find($entityArr[0]);

                    if (!$entity) {
                        unset($item['tilesRelated']);
                        $items[$key] = $item;
                        continue;
                    }

                    $relatedTiles = $entity->getTiles()?->map(fn ($tile) => $tile->getName())
                        ->getValues();

                    $item['tilesRelated'] = $relatedTiles ? \implode(', ', $relatedTiles) : '';
                    $items[$key] = $item;
                }
            } catch (\Exception $exception) {
                continue;
            }
        }

        return $items;
    }
}
