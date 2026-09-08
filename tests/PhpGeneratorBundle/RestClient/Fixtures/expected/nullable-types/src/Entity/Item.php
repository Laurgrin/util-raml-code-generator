<?php

namespace Paysera\Test\NullableTypesClient\Entity;

use Paysera\Component\RestClientCommon\Entity\Entity;

class Item extends Entity
{
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';

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
    /**
     * @return boolean|null
     */
    public function isArchived()
    {
        return $this->get('archived');
    }
    /**
     * @param boolean $archived
     * @return $this
     */
    public function setArchived($archived)
    {
        $this->set('archived', $archived);
        return $this;
    }
    /**
     * @return integer|null
     */
    public function getScore()
    {
        return $this->get('score');
    }
    /**
     * @param integer $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->set('score', $score);
        return $this;
    }
    /**
     * @return string|null
     */
    public function getNickname()
    {
        return $this->get('nickname');
    }
    /**
     * @param string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->set('nickname', $nickname);
        return $this;
    }
    /**
     * @return string[]|null
     */
    public function getTags()
    {
        return $this->get('tags');
    }
    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->set('tags', $tags);
        return $this;
    }
    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->get('status');
    }
    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->set('status', $status);
        return $this;
    }
}
