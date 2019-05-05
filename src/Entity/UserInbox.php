<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserInboxRepository")
 */
class UserInbox
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InboxMembership", mappedBy="userInbox")
     */
    private $inboxMemberships;

    public function __construct()
    {
        $this->inboxMemberships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|InboxMembership[]
     */
    public function getInboxMemberships(): Collection
    {
        return $this->inboxMemberships;
    }

    public function addInboxMembership(InboxMembership $inboxMembership): self
    {
        if (!$this->inboxMemberships->contains($inboxMembership)) {
            $this->inboxMemberships[] = $inboxMembership;
            $inboxMembership->setUserInbox($this);
        }

        return $this;
    }

    public function removeInboxMembership(InboxMembership $inboxMembership): self
    {
        if ($this->inboxMemberships->contains($inboxMembership)) {
            $this->inboxMemberships->removeElement($inboxMembership);
            // set the owning side to null (unless already changed)
            if ($inboxMembership->getUserInbox() === $this) {
                $inboxMembership->setUserInbox(null);
            }
        }

        return $this;
    }
}
