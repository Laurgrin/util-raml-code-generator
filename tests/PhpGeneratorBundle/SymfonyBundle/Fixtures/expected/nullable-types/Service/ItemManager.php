<?php

namespace Vendor\Test\NullableTypesApiBundle\Service;

use Vendor\Test\NullableTypesApiBundle\Entity as Entities;
use Vendor\Test\NullableTypesApiBundle\Repository\ItemRepository;
use Doctrine\ORM\EntityManager;

class ItemManager
{
    private $itemRepository;
    private $entityManager;

    public function __construct(
        ItemRepository $itemRepository,
        EntityManager $entityManager
    ) {
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $id
     * @return Entities\Item
     */
    public function getItem($id)
    {
        //TODO: generated_code
    }
    /**
     * @param string $id
     * @param Entities\Item $item
     * @return Entities\Item
     */
    public function updateItem($id, Entities\Item $item)
    {
        //TODO: generated_code
    }
}
