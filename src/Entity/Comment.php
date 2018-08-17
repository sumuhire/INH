<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $originIp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bestComment;

    public function __construct()
    {
        $this->creationDate = new \DateTime();

    }


    public function getId(): ?string
    {
        return $this->id;
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
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

    public function getBestComment(): ?bool
    {
        return $this->bestComment;
    }

    public function setBestComment(bool $bestComment): self
    {
        $this->bestComment = $bestComment;

        return $this;
    }
}
