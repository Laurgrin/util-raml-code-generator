<?php

namespace Vendor\Test\NullableTypesApiBundle\Voter;

use Vendor\Test\NullableTypesApiBundle\ItemPermissions;
use Paysera\Bundle\SecurityBundle\Security\ContextAwareScopeVoter;
use Paysera\Bundle\SecurityBundle\Entity\AccessedBy;

class ItemScopeVoter extends ContextAwareScopeVoter
{
    public function getPermissionScopeMap()
    {
        return [
            ItemPermissions::GET_ITEM => [
                // TODO: generated_code
            ],
            ItemPermissions::UPDATE_ITEM => [
                // TODO: generated_code
            ],
        ];
    }

    public function checkAccessRights(AccessedBy $accessedBy, $permission, $subject)
    {
        // TODO: generated_code
    }
}
