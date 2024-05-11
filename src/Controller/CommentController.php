<?php

namespace Sharlottte\Itpelag\Controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\Article;
use Sharlottte\Itpelag\Model\Comment;
use Sharlottte\Itpelag\Model\User;
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
        $lastComment = $this->getLastComment($article);
        $lastAuthor = $this->getLastThreeCommentsAuthor($article);



        if ($_POST['content'] == '') {
            header('Location: /articles/' . $id);
            $this->userSession->flashMessage('error', 'Комментарий не может быть пустым');
            return;
        }

        if ($lastComment != null) {
            $lastTime = $lastComment->getCreated()->getTimestamp();
            if ((!$lastComment || time() - $lastTime > 60) && $lastAuthor != $this->userSession->getUser()) {
                $comment = new Comment($this->userSession->getUser(), $article, $_POST['content'],);
                $this->em->persist($comment);
                $this->em->flush();
            } else {
                $this->userSession->flashMessage('error', 'Нельзя оставлять больше одного комментария в минуту и осталять более 2 комментариев подряд');
            }
        } else {
            $comment = new Comment($this->userSession->getUser(), $article, $_POST['content'],);
            $this->em->persist($comment);
            $this->em->flush();
        }



        header('Location: /articles/' . $id);
    }

    private function getLastComment(Article $article): ?Comment
    {
        $repository = $this->em->getRepository(Comment::class);

        try {
            $lastComment = $repository->createQueryBuilder('comment')
                ->andWhere('comment.article = :article')
                ->andWhere('comment.author = :user')
                ->setParameter('article', $article)
                ->setParameter('user', $this->userSession->getUser())
                ->orderBy('comment.created', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

            return $lastComment;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    private function getLastThreeCommentsAuthor(Article $article): ?User
    {
        $repository = $this->em->getRepository(Comment::class);


        $lastComments = $repository->createQueryBuilder('comment')
            ->andWhere('comment.article = :article')
            ->setParameter('article', $article)
            ->orderBy('comment.created', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();


        if (
            count($lastComments) === 3 &&
            $lastComments[0]->getAuthor() === $lastComments[1]->getAuthor() &&
            $lastComments[1]->getAuthor() === $lastComments[2]->getAuthor()
        ) {
            return $lastComments[0]->getAuthor();
        }
        return null;
    }
}
