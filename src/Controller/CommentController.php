<?php

namespace Sharlottte\Itpelag\Controller;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\Article;
use Sharlottte\Itpelag\Model\Comment;
use Twig\Environment;

class CommentController
{
    public function __construct(
        private EntityManager $em,
        private Environment $twig,
        private UserSession $userSession,
    ) {
    }

    public function comment($id)
    {
        $article = $this->em->find(Article::class, $id);
        $comment = new Comment($this->userSession->getUser(), $article, $_POST['content'],);
        $this->em->persist($comment);
        $this->em->flush();

        header('Location: /articles/' . $id);
    }
}
