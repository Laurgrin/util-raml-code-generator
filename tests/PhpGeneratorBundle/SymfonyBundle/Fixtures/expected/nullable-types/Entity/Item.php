<?php

namespace Vendor\Test\NullableTypesApiBundle\Entity;

class Item
{
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';

    private $id;
    private $label;
    private $note;
    private $enabled;
    private $updatedAt;
    private $owner;
    private $archived;
    private $score;
    private $nickname;
    private $tags;
    private $status;
    private $payload;
    private $attachment;

    public function __construct()
    {
                                            
        $this->tags = [];            
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
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }
    /**
     * @param string $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }
    /**
     * @return boolean|null
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    /**
     * @param boolean $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    /**
     * @return Owner|null
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * @param Owner $owner
     * @return $this
     */
    public function setOwner(Owner $owner)
    {
        $this->owner = $owner;
        return $this;
    }
    /**
     * @return boolean|null
     */
    public function isArchived()
    {
        return $this->archived;
    }
    /**
     * @param boolean $archived
     * @return $this
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }
    /**
     * @return integer|null
     */
    public function getScore()
    {
        return $this->score;
    }
    /**
     * @param integer $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getNickname()
    {
        return $this->nickname;
    }
    /**
     * @param string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }
    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }
    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return object|null
     */
    public function getPayload()
    {
        return $this->payload;
    }
    /**
     * @param object $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
    /**
     * @return file|null
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
    /**
     * @param file $attachment
     * @return $this
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

}
