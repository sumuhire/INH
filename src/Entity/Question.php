<?php

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     * @Groups({"public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"public"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"public"})
     */
    private $editDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $originIp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"public"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"public"})
     */
    private $targetDepartment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"public"})
     */
    private $emergency;

    /**
     * @ORM\Column(type="text")
     * @Groups({"public"})
     * @Assert\Regex(
     *     pattern="/<|>/",
     *     match=false,
     *     message="Your description cannot contain a upper or lower sign"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public"})
     */
    private $flag;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="question")
     * @Groups({"public"})     *
     */
    private $comments;

    public function __construct()
    {
        $this->creationDate = new \DateTime();

        $this->comments = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getCreationDate()
    {
        return $this->creationDate;
    }


    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }

    public function setEditDate(\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;

        return $this;
    }

    public function getOriginIp(): ?string
    {
        return $this->originIp;
    }

    public function setOriginIp(string $originIp): self
    {
        $this->originIp = $originIp;

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
        return $this->targetDepartment;
    }

    public function setTargetDepartment(?Department $targetDepartment): self
    {
        $this->targetDepartment = $targetDepartment;

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
