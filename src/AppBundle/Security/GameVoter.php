<?php
/**
 * Created by PhpStorm.
 * User: Luk
 * Date: 15/05/2017
 * Time: 13:53
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use AppBundle\Game\Game;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GameVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return 'PLAY' === $attribute;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        if(!$user instanceof User || $user == null) {
            return false;
        }

        return $token->getUser()->getAge() > 17;
    }
}