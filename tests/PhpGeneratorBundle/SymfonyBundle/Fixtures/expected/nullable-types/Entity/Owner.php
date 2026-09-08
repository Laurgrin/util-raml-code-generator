<?php

namespace Vendor\Test\NullableTypesApiBundle\Entity;

class Owner
{
    private $id;
    private $ownerId;
    private $displayName;

    public function __construct()
    {
            
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }
    /**
     * @param string $ownerId
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        return $this;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

}
