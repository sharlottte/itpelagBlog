<?php

namespace Sharlottte\Itpelag\Common;

use Twig\Environment;
use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Model\User;

class UserSession
{
    private ?User $user = null;

    public function __construct(
        private EntityManager $em,
        private Environment $twig,
    ) {
    }

    public function load()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            $this->twig->addGlobal('user', null);
            return;
        }
        $this->user = $this->em->find(User::class, $_SESSION['id']);
        $this->twig->addGlobal('user', $this->user?->toArray());
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function destroy()
    {
        unset($_SESSION['id']);
    }

    public function saveSession(User $user)
    {
        $_SESSION['id'] = $user->getId();
    }
}
