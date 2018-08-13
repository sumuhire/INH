<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_edit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $origin_ip;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="emergency")
     * @ORM\JoinColumn(nullable=false)
     */
    private $target_department;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emergency;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flag;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="question")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDatetimeCreation(): ?\DateTimeInterface
    {
        return $this->datetime_creation;
    }

    public function setDatetimeCreation(\DateTimeInterface $datetime_creation): self
    {
        $this->datetime_creation = $datetime_creation;

        return $this;
    }

    public function getDatetimeEdit(): ?\DateTimeInterface
    {
        return $this->datetime_edit;
    }

    public function setDatetimeEdit(\DateTimeInterface $datetime_edit): self
    {
        $this->datetime_edit = $datetime_edit;

        return $this;
    }

    public function getOriginIp(): ?string
    {
        return $this->origin_ip;
    }

    public function setOriginIp(string $origin_ip): self
    {
        $this->origin_ip = $origin_ip;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTargetDepartment(): ?Department
    {
        return $this->target_department;
    }

    public function setTargetDepartment(?Department $target_department): self
    {
        $this->target_department = $target_department;

        return $this;
    }

    public function getEmergency(): ?bool
    {
        return $this->emergency;
    }

    public function setEmergency(bool $emergency): self
    {
        $this->emergency = $emergency;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setQuestion($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getQuestion() === $this) {
                $comment->setQuestion(null);
            }
        }

        return $this;
    }
}
