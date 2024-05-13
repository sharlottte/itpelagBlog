<?php

declare(strict_types=1);

namespace Sharlottte\Itpelag\Controller;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\Article;
use Sharlottte\Itpelag\Model\Like;
use Twig\Environment;

class ArticleController
{
    public function __construct(
        private EntityManager $em,
        private Environment $twig,
        private UserSession $userSession,
    ) {
    }

    public function index()
    {
        $articlesData = [];
        $kolvo = 10;
        $page = $_GET['page'] ?? 1;
        if ($page < 1) {
            header('Location: /');

            return;
        }
        $repository = $this->em->getRepository(Article::class);
        $articlesCount = $repository->count();
        $maxPage = max(1, ceil($articlesCount / $kolvo));
        if ($page > $maxPage) {
            header('Location: /?page='.$maxPage);

            return;
        }
        $articles = $repository->createQueryBuilder('article')
            ->setFirstResult(($page - 1) * $kolvo)
            ->setMaxResults($kolvo)
            ->getQuery()
            ->getResult()
        ;

        foreach ($articles as $article) {
            $articlesData[] = $article->toArray();
        }
        $this->twig->load('index.html.twig')->display([
            'articles' => $articlesData,
            'user' => $this->userSession->getUser()?->toArray(),
            'page' => $page,
            'maxPage' => $maxPage,
        ]);
    }

    public function show($id)
    {
        $article = $this->em->find(Article::class, $id);
        $likee = $this->userSession->getUser() ? $this->em->find(Like::class, ['author' => $this->userSession->getUser(), 'article' => $article]) : null;

        $this->twig->load('read.html.twig')->display([
            'article' => $article->toArray(),
            'comments' => $article->getComments(),
            'count' => $article->getComments()->count(),
            'countlikes' => $article->getLikes()->count(),
            'isLiked' => null !== $likee,
            'id' => $id,
        ]);
    }

    public function create()
    {
        $this->twig->load('create.html.twig')->display();
    }

    public function creating()
    {
        if (mb_strlen($_POST['title']) < 8 || mb_strlen($_POST['content']) < 30) {
            header('Location: /create');
            $this->userSession->flashMessage('error', 'Название не может быть короче 8 символов, а контент не может быть меньше 30 символов');

            return;
        }
        $articleName = $this->em->getRepository(Article::class)->findOneBy(['title' => $_POST['title']]);

        if (null === $articleName) {
            $article = new Article($_POST['title'], $_POST['content'], $this->userSession->getUser());
            $this->em->persist($article);
            $this->em->flush();
            header('Location: /');
        } else {
            $this->userSession->flashMessage('error', 'Название статьи должно быть оригинальным');
            header('Location: /create');
        }
    }

    public function change($id)
    {
        $article = $this->em->find(Article::class, $id);
        $this->twig->load('change.html.twig')->display([
            'article' => $article->toArray(),
            'id' => $id,
        ]);
    }

    public function changing($id)
    {
        $article = $this->em->find(Article::class, $id);
        if ($article->getAuthor() !== $this->userSession->getUser()) {
            header('Location: /articles/'.$id);

            return;
        }

        $articleName = $this->em->getRepository(Article::class)->findOneBy(['title' => $_POST['title']]);
        if (null !== $articleName) {
            if ($articleName->getId() !== $article->getId()) {
                header('Location: /articles/'.$id.'/edit');
                $this->userSession->flashMessage('error', 'Название статьи должно быть оригинальным');

                return;
            }
        }

        if (mb_strlen($_POST['title']) < 8 || mb_strlen($_POST['content']) < 30) {
            header('Location: /articles/'.$id.'/edit');
            $this->userSession->flashMessage('error', 'Название не может быть короче 8 символов, а контент не может быть меньше 30 символов');

            return;
        }

        $article->update($_POST['title'], $_POST['content']);

        $this->em->persist($article);
        $this->em->flush();

        header('Location: /articles/'.$id);
    }
}
