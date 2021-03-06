<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    private $passwordRepeat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupMembership", mappedBy="groupUser")
     */
    private $groupMemberships;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InboxMembership", mappedBy="inboxUser")
     */
    private $inboxMemberships;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupPost", mappedBy="author")
     */
    private $groupPosts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupComment", mappedBy="author")
     */
    private $groupComments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile;

    public function __construct()
    {
        $this->groupMemberships = new ArrayCollection();
        $this->inboxMemberships = new ArrayCollection();
        $this->groupPosts = new ArrayCollection();
        $this->groupComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordRepeat(): string
    {
        return (string) $this->passwordRepeat;
    }

    public function setPasswordRepeat(string $passwordRepeat): self
    {
        $this->passwordRepeat = $passwordRepeat;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
            $groupMembership->setGroupUser($this);
        }

        return $this;
    }

    public function removeGroupMembership(GroupMembership $groupMembership): self
    {
        if ($this->groupMemberships->contains($groupMembership)) {
            $this->groupMemberships->removeElement($groupMembership);
            // set the owning side to null (unless already changed)
            if ($groupMembership->getGroupUser() === $this) {
                $groupMembership->setGroupUser(null);
            }
        }

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
            $inboxMembership->setInboxUser($this);
        }

        return $this;
    }

    public function removeInboxMembership(InboxMembership $inboxMembership): self
    {
        if ($this->inboxMemberships->contains($inboxMembership)) {
            $this->inboxMemberships->removeElement($inboxMembership);
            // set the owning side to null (unless already changed)
            if ($inboxMembership->getInboxUser() === $this) {
                $inboxMembership->setInboxUser(null);
            }
        }

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
            $groupPost->setAuthor($this);
        }

        return $this;
    }

    public function removeGroupPost(GroupPost $groupPost): self
    {
        if ($this->groupPosts->contains($groupPost)) {
            $this->groupPosts->removeElement($groupPost);
            // set the owning side to null (unless already changed)
            if ($groupPost->getAuthor() === $this) {
                $groupPost->setAuthor(null);
            }
        }

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
            $groupComment->setAuthor($this);
        }

        return $this;
    }

    public function removeGroupComment(GroupComment $groupComment): self
    {
        if ($this->groupComments->contains($groupComment)) {
            $this->groupComments->removeElement($groupComment);
            // set the owning side to null (unless already changed)
            if ($groupComment->getAuthor() === $this) {
                $groupComment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfileSrc(): ?string
    {
        if ($this->profile == null)
            return null;

        $src = json_decode($this->profile);
        $src = strstr($src->filepath, 'uploads');
        return $src;
    }
}
