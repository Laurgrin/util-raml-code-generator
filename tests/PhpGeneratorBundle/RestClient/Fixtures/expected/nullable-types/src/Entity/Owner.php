<?php

namespace Paysera\Test\NullableTypesClient\Entity;

use Paysera\Component\RestClientCommon\Entity\Entity;

class Owner extends Entity
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->get('owner_id');
    }
    /**
     * @param string $ownerId
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        $this->set('owner_id', $ownerId);
        return $this;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->get('display_name');
    }
    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->set('display_name', $displayName);
        return $this;
    }
}
