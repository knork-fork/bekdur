<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InboxMembershipRepository")
 */
class InboxMembership
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="inboxMemberships")
     */
    private $inboxUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserInbox", inversedBy="inboxMemberships")
     */
    private $userInbox;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInboxUser(): ?User
    {
        return $this->inboxUser;
    }

    public function setInboxUser(?User $inboxUser): self
    {
        $this->inboxUser = $inboxUser;

        return $this;
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
