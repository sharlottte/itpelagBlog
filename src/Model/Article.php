<?php

declare(strict_types=1);

namespace Sharlottte\Itpelag\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class Article
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private User $author;

    #[ORM\Column]
    private \DateTimeImmutable $created;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', )]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'article', )]
    private Collection $likes;

    public function __construct(string $title, string $content, User $author)
    {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->created = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function update(string $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    #[ORM\Column]
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'text' => $this->getContent(),
            'author' => $this->getAuthor()->toArray(),
        ];
    }
}
