<?php

namespace Vendor\Test\NullableTypesApiBundle\Controller;

use Vendor\Test\NullableTypesApiBundle\Entity as Entities;
use Vendor\Test\NullableTypesApiBundle\Service\ItemManager;
use Vendor\Test\NullableTypesApiBundle\ItemPermissions;
use Paysera\Bundle\SecurityBundle\Service\AuthorizationChecker;
use Doctrine\ORM\EntityManager;

class ItemApiController
{
    private $authorizationChecker;
    private $entityManager;
    private $itemManager;
    
    public function __construct(
        ItemManager $itemManager,
        AuthorizationChecker $authorizationChecker,
        EntityManager $entityManager
    ) {
        $this->itemManager = $itemManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    /**
     * Get an item
     * GET /items/{id}
     *
     * @param string $id
     * @return Entities\Item
     */
    public function getItem($id)
    {
        $this->authorizationChecker->check(ItemPermissions::GET_ITEM);
        return $this->itemManager->getItem($id);
    }
    /**
     * Update an item
     * PUT /items/{id}
     *
     * @param string $id
     * @param Entities\Item $item
     * @return Entities\Item
     */
    public function updateItem($id, Entities\Item $item)
    {
        $this->authorizationChecker->check(ItemPermissions::UPDATE_ITEM);
        $result = $this->itemManager->updateItem($id, $item);
        $this->entityManager->flush();
        return $result;
    }
}
