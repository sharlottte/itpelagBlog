<?php

namespace Sharlottte\Itpelag\Controller;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\Article;
use Sharlottte\Itpelag\Model\Comment;
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
        $kolvo = 5;
        $page = $_GET['page'] ?? 1;
        if ($page < 1) {
            header('Location: /1');

            return;
        }
        $repository = $this->em->getRepository(Article::class);
        $articlesCount = $repository->count();
        $maxPage = max(1, ceil($articlesCount / $kolvo));
        if ($page > $maxPage) {
            header('Location: /' . $maxPage);

            return;
        }
        $articles = $repository->createQueryBuilder('article')
            ->setFirstResult(($page - 1) * $kolvo)
            ->setMaxResults($kolvo)
            ->getQuery()
            ->getResult();

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
        $this->twig->load('read.html.twig')->display([
            'article' => $article->toArray(),
            'comments' => $article->getComments(),
            'count' => $article->getComments()->count(),
            'countlikes' => $article->getLikes()->count(),
            'id' => $id,
        ]);
    }




    public function create()
    {
        $this->twig->load('create.html.twig')->display();
    }
    public function creating()
    {
        $article = new Article($_POST['title'], $_POST['content'], $this->userSession->getUser());
        $this->em->persist($article);
        $this->em->flush();

        header('Location: /');
    }



    public function change($id)
    {
        $article = $this->em->find(Article::class, $id);
        $this->twig->load('change.html.twig')->display([
            'article' => $article->toArray(),
            'id' => $id,
        ]);
        print_r($_SESSION);
    }
    public function changing($id)
    {
        $article = $this->em->find(Article::class, $id);
        if ($article->getAuthor() !== $this->userSession->getUser()) {
            header('Location: /articles/' . $id);
            return;
        }
        $article->update($_POST['title'], $_POST['content']);
        $this->em->persist($article);
        $this->em->flush();

        header('Location: /articles/' . $id);
    }
}
