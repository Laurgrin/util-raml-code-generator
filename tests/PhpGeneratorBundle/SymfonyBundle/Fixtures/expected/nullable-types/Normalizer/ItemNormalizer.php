<?php

namespace Vendor\Test\NullableTypesApiBundle\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Vendor\Test\NullableTypesApiBundle\Entity\Item;

class ItemNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $ownerNormalizer;
    
    public function __construct(
        OwnerNormalizer $ownerNormalizer
    ) {
        $this->ownerNormalizer = $ownerNormalizer;
    }
    
    /**
     * @param array $data
     *
     * @return Item
     */
    public function mapToEntity($data)
    {
        $entity = new Item();

        if (isset($data['label'])) {
            $entity->setLabel($data['label']);
        }
        if (isset($data['note'])) {
            $entity->setNote($data['note']);
        }
        if (isset($data['enabled'])) {
            $entity->setEnabled($data['enabled']);
        }
        if (isset($data['updated_at'])) {
            $entity->setUpdatedAt(\DateTime::createFromFormat('Y-m-d\TH:i:sP', $data['updated_at']));
        }
        if (isset($data['owner'])) {
            $entity->setOwner($this->ownerNormalizer->mapToEntity($data['owner']));
        }
        if (isset($data['archived'])) {
            $entity->setArchived($data['archived']);
        }
        if (isset($data['score'])) {
            $entity->setScore($data['score']);
        }
        if (isset($data['nickname'])) {
            $entity->setNickname($data['nickname']);
        }
        if (isset($data['tags'])) {
            $entity->setTags($data['tags']);
        }
        if (isset($data['status'])) {
            $entity->setStatus($data['status']);
        }
        
        return $entity;
    }

    /**
     * @param Item $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        return [
            'id' => $entity->getId(),
            'label' => $entity->getLabel(),
            'note' => $entity->getNote(),
            'enabled' => $entity->isEnabled(),
            'updated_at' => $entity->getUpdatedAt() !== null ? $entity->getUpdatedAt()->format('Y-m-d\TH:i:sP') : null,
            'owner' => $entity->getOwner() !== null ? $this->ownerNormalizer->mapFromEntity($entity->getOwner()) : null,
            'archived' => $entity->isArchived(),
            'score' => $entity->getScore(),
            'nickname' => $entity->getNickname(),
            'tags' => $entity->getTags(),
            'status' => $entity->getStatus(),
            
        ];
    }
}
