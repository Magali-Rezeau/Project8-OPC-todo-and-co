<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['DELETE', 'EDIT'])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case 'DELETE':
                if ($subject->getAuthor() === $user) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN') && $subject->getAuthor()->getRoles() === ["ROLE_ANONYMOUS"]) {
                    return true;
                }
                return false;
            break;
            case 'EDIT':
                if ($subject->getAuthor() === $user) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN') && $subject->getAuthor()->getRoles() === ["ROLE_ANONYMOUS"]) {
                    return true;
                }
                return false;
            break;
        }

        return false;
    }
}
