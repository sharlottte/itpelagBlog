<?php

namespace Sharlottte\Itpelag\Controller;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\Article;
use Sharlottte\Itpelag\Model\Like;
use Twig\Environment;

class LikeController
{
    public function __construct(
        private EntityManager $em,
        private Environment $twig,
        private UserSession $userSession,
    ) {
    }


    public function like($id)
    {
        $article = $this->em->find(Article::class, $id);
        $like = new Like($this->userSession->getUser(), $article);

        $likee = $this->em->find(Like::class, ['author' => $this->userSession->getUser(), 'article' => $article]);
        if ($likee) {
            $this->em->remove($likee);
            $this->em->flush();
        } else {
            $this->em->persist($like);
            $this->em->flush();
        }
        header('Location: /articles/' . $id);
    }

}

