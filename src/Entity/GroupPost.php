<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupPostRepository")
 */
class GroupPost
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
    private $header;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groupPosts")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="groupPosts")
     */
    private $userGroup;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupComment", mappedBy="parent")
     */
    private $groupComments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $mostRecent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $filePath;

    public function __construct()
    {
        $this->groupComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(?string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|GroupComment[]
     */
    public function getGroupComments(): Collection
    {
        return $this->groupComments;
    }

    public function addGroupComment(GroupComment $groupComment): self
    {
        if (!$this->groupComments->contains($groupComment)) {
            $this->groupComments[] = $groupComment;
            $groupComment->setParent($this);
        }

        return $this;
    }

    public function removeGroupComment(GroupComment $groupComment): self
    {
        if ($this->groupComments->contains($groupComment)) {
            $this->groupComments->removeElement($groupComment);
            // set the owning side to null (unless already changed)
            if ($groupComment->getParent() === $this) {
                $groupComment->setParent(null);
            }
        }

        return $this;
    }

    public function getMostRecent(): ?\DateTimeInterface
    {
        return $this->mostRecent;
    }

    public function setMostRecent(?\DateTimeInterface $mostRecent): self
    {
        $this->mostRecent = $mostRecent;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function getSrc(): ?string
    {
        $src = json_decode($this->filePath);
        $src = strstr($src->filepath, 'uploads');
        return $src;
    }

    public function getOriginal(): ?string
    {
        $org = json_decode($this->filePath);
        return $org->original;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }
}
