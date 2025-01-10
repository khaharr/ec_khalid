<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LikeRepository;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
class Like
{
    #[ORM\Id, ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: BookRead::class, inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private $bookRead;

    #[ORM\Column(type: "integer")]
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookRead(): ?BookRead
    {
        return $this->bookRead;
    }

    public function setBookRead(?BookRead $bookRead): self
    {
        $this->bookRead = $bookRead;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
} 