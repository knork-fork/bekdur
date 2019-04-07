<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupMembershipRepository")
 */
class GroupMembership
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groupMemberships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="groupMemberships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userGroup;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupUser(): ?User
    {
        return $this->groupUser;
    }

    public function setGroupUser(?User $groupUser): self
    {
        $this->groupUser = $groupUser;

        return $this;
    }

    public function getUserGroup(): ?UserGroup
    {
        return $this->userGroup;
    }

    public function setUserGroup(?UserGroup $userGroup): self
    {
        $this->userGroup = $userGroup;

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
}
