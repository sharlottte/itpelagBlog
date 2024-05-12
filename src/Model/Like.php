<?php

declare(strict_types=1);

namespace Sharlottte\Itpelag\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'likes')]
class Like
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $author;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(name: 'article_id', referencedColumnName: 'id')]
    private Article $article;

    #[ORM\Column]
    private \DateTimeImmutable $created;

    public function __construct(User $author, Article $article)
    {
        $this->author = $author;
        $this->article = $article;
        $this->created = new \DateTimeImmutable();
    }

    public function getArticle(): Article
    {
        return $this->article;
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
            'author' => $this->getAuthor()->toArray(),
            'article' => $this->getArticle(),
        ];
    }
}
