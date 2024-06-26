<?php

declare(strict_types=1);

namespace Sharlottte\Itpelag\Common;

use Doctrine\ORM\EntityManager;
use Sharlottte\Itpelag\Model\User;
use Twig\Environment;

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
        $this->twig->addGlobal('messages', $_SESSION['messages'] ?? []);
        $_SESSION['messages'] = [];
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

    public function flashMessage(string $level, string $message)
    {
        $_SESSION['messages'][] = ['level' => $level, 'message' => $message];
    }
}
