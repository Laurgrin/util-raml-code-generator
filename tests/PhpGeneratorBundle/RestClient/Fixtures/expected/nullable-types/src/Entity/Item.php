<?php

namespace Paysera\Test\NullableTypesClient\Entity;

use Paysera\Component\RestClientCommon\Entity\Entity;

class Item extends Entity
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->get('id');
    }
    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->set('id', $id);
        return $this;
    }
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }
    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->set('label', $label);
        return $this;
    }
    /**
     * @return string|null
     */
    public function getNote()
    {
        return $this->get('note');
    }
    /**
     * @param string $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->set('note', $note);
        return $this;
    }
    /**
     * @return boolean|null
     */
    public function isEnabled()
    {
        return $this->get('enabled');
    }
    /**
     * @param boolean $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->set('enabled', $enabled);
        return $this;
    }
    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt()
    {
        if ($this->get('updated_at') === null) {
            return null;
        }
        return \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:sP', $this->get('updated_at'));
    }
    /**
     * @param \DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->set('updated_at', $updatedAt->format('Y-m-d\TH:i:sP'));
        return $this;
    }
    /**
     * @return Owner|null
     */
    public function getOwner()
    {
        if ($this->get('owner') === null) {
            return null;
        }
        return (new Owner())->setDataByReference($this->getByReference('owner'));
    }
    /**
     * @param Owner $owner
     * @return $this
     */
    public function setOwner(Owner $owner)
    {
        $this->setByReference('owner', $owner->getDataByReference());
        return $this;
    }
}
