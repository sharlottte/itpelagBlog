<?php

namespace Sharlottte\Itpelag\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $author;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'article_id', referencedColumnName: 'id')]
    private Article $article;


    #[ORM\Column]
    private \DateTimeImmutable $created;

    public function __construct(User $author, Article $article, string $content)
    {
        $this->author = $author;
        $this->article = $article;
        $this->content = $content;
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): Article
    {
        return $this->article;
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



    #[ORM\Column]
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'article' => $this->getArticle()->toArray(),
            'author' => $this->getAuthor()->toArray(),
            'content' => $this->getContent(),

        ];
    }
}
