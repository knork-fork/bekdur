<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserInbox", inversedBy="messages")
     */
    private $userInbox;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $seen;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $author_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserInbox(): ?UserInbox
    {
        return $this->userInbox;
    }

    public function setUserInbox(?UserInbox $userInbox): self
    {
        $this->userInbox = $userInbox;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(?bool $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(?int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }
}
