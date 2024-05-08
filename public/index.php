<?php


$container = require __DIR__ . '/../src/bootstrap.php';

$router = new \Bramus\Router\Router();

$router->before('GET|POST', '/.*', fn () => $container->get('Sharlottte\Itpelag\Common\UserSession')->load());

$router->get('/', fn () => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->index());

$router->get('/login', fn () => $container->get('Sharlottte\Itpelag\Controller\UserController')->enter());

$router->post('/login', fn () => $container->get('Sharlottte\Itpelag\Controller\UserController')->entering());

$router->get('/registration', fn () => $container->get('Sharlottte\Itpelag\Controller\UserController')->registration());

$router->post('/registration', fn () => $container->get('Sharlottte\Itpelag\Controller\UserController')->register());

$router->get('/logout', fn () => $container->get('Sharlottte\Itpelag\Controller\UserController')->logout());

$router->get('/articles/(\d+)', fn ($id) => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->show($id));

$router->get('/articles/(\d+)/edit', fn ($id) => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->change($id));

$router->post('/articles/(\d+)/edit', fn ($id) => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->changing($id));

$router->get('/create', fn () => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->create());

$router->post('/create', fn () => $container->get('Sharlottte\Itpelag\Controller\ArticleController')->creating());


$router->post('/articles/(\d+)', fn ($id) => $container->get('Sharlottte\Itpelag\Controller\CommentController')->comment($id));

$router->post('/articles/(\d+)/like', fn ($id) => $container->get('Sharlottte\Itpelag\Controller\LikeController')->like($id));


$router->run();
