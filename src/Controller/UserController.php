<?php

namespace Sharlottte\Itpelag\Controller;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Common\UserSession;
use Sharlottte\Itpelag\Model\User;
use Twig\Environment;

class UserController
{
    public function __construct(
        private EntityManager $em,
        private Environment $twig,
        private UserSession $userSession,
    ) {
    }

    public function registration()
    {
        $this->twig->load('registration.html.twig')->display();
    }
    public function register()
    {
        $user = new User($_POST['name'], $_POST['password']);

        $userName = $this->em->getRepository(User::class)->findOneBy(['name' => $_POST['name']]);
        if($userName!=null){
            header('Location: /registration');
            //Сообщение об ошибке
            return;
        }


        if(mb_strlen($_POST['name'])<8||mb_strlen($_POST['password'])<8){
            header('Location: /registration');
            //Сообщение об ошибке
            return;
        }

        $this->em->persist($user);
        $this->em->flush();

        $this->userSession->saveSession($user);
        header('Location: /');
    }

    public function logout()
    {
        $this->userSession->destroy();
        header('Location: /');
    }


    public function enter()
    {
        $this->twig->load('enter.html.twig')->display();
    }
    public function entering()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['name' => $_POST['name']]);
        if (!$user) {
            header('Location: /login');
            //Сообщение об ошибке
            //echo "Неа, врешь";
            return;
        }

        if (!$user->verifyPassword($_POST['password'])) {
            header('Location: /login');
            //Сообщение об ошибке
            //echo "обманываешь";
            return;
        }
        $this->userSession->saveSession($user);


        header('Location: /');
    }

    private function saveSession(User $user)
    {
        $_SESSION['id'] = $user->getId();
    }
}
