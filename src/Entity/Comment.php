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
     * @ORM\Column(type="integer")
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
    private $datetime_creation;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $origin_ip;

    /**
     * @ORM\Column(type="boolean")
     */
    private $best_comment;

    public function getId(): ?int
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

    public function getDatetimeCreation(): ?\DateTimeInterface
    {
        return $this->datetime_creation;
    }

    public function setDatetimeCreation(\DateTimeInterface $datetime_creation): self
    {
        $this->datetime_creation = $datetime_creation;

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

    public function getBestComment(): ?bool
    {
        return $this->best_comment;
    }

    public function setBestComment(bool $best_comment): self
    {
        $this->best_comment = $best_comment;

        return $this;
    }
}
