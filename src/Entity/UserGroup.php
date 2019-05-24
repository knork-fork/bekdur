<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 */
class UserGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="userGroup")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupMembership", mappedBy="userGroup")
     */
    private $groupMemberships;

    private $notificationNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupPost", mappedBy="userGroup")
     */
    private $groupPosts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $theme;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->groupMemberships = new ArrayCollection();
        $this->groupPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUserGroup($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUserGroup() === $this) {
                $notification->setUserGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupMembership[]
     */
    public function getGroupMemberships(): Collection
    {
        return $this->groupMemberships;
    }

    public function addGroupMembership(GroupMembership $groupMembership): self
    {
        if (!$this->groupMemberships->contains($groupMembership)) {
            $this->groupMemberships[] = $groupMembership;
            $groupMembership->setUserGroup($this);
        }

        return $this;
    }

    public function removeGroupMembership(GroupMembership $groupMembership): self
    {
        if ($this->groupMemberships->contains($groupMembership)) {
            $this->groupMemberships->removeElement($groupMembership);
            // set the owning side to null (unless already changed)
            if ($groupMembership->getUserGroup() === $this) {
                $groupMembership->setUserGroup(null);
            }
        }

        return $this;
    }

    public function getNotificationNumber(): ?int
    {
        return $this->notificationNumber;
    }

    public function setNotificationNumber(int $notificationNumber): self
    {
        $this->notificationNumber = $notificationNumber;

        return $this;
    }

    /**
     * @return Collection|GroupPost[]
     */
    public function getGroupPosts(): Collection
    {
        return $this->groupPosts;
    }

    public function addGroupPost(GroupPost $groupPost): self
    {
        if (!$this->groupPosts->contains($groupPost)) {
            $this->groupPosts[] = $groupPost;
            $groupPost->setUserGroup($this);
        }

        return $this;
    }

    public function removeGroupPost(GroupPost $groupPost): self
    {
        if ($this->groupPosts->contains($groupPost)) {
            $this->groupPosts->removeElement($groupPost);
            // set the owning side to null (unless already changed)
            if ($groupPost->getUserGroup() === $this) {
                $groupPost->setUserGroup(null);
            }
        }

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}
