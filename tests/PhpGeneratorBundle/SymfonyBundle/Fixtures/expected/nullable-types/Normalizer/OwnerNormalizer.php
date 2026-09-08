<?php

namespace Vendor\Test\NullableTypesApiBundle\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Vendor\Test\NullableTypesApiBundle\Entity\Owner;

class OwnerNormalizer implements NormalizerInterface, DenormalizerInterface
{
    
    /**
     * @param array $data
     *
     * @return Owner
     */
    public function mapToEntity($data)
    {
        $entity = new Owner();

        if (isset($data['owner_id'])) {
            $entity->setOwnerId($data['owner_id']);
        }
        if (isset($data['display_name'])) {
            $entity->setDisplayName($data['display_name']);
        }
        
        return $entity;
    }

    /**
     * @param Owner $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        return [
            'id' => $entity->getId(),
            'owner_id' => $entity->getOwnerId(),
            'display_name' => $entity->getDisplayName(),
            
        ];
    }
}
