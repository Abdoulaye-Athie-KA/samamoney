<?php
namespace App\Security;


use Exception;
use App\Entity\Partenaire as AppPartenaire;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if ($user instanceof AppUser || $user instanceof AppPartenaire) {

            if (!$user->getIsActive()) {
                throw new Exception("votre compte est inactif");
            }
            else{
                return;
        } 
        }

        // user is deleted, show a generic Account Not Found message.
        // if ($user->isDeleted()) {
        //     throw new AccountDeletedException();
        // }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        // if ($user->isExpired()) {
        //     throw new AccountExpiredException('...');
        //}
    }
}