<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use AppBundle\Entity\User;

class UserManager
{
    /** @var EntityManager */
    private $em;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(EntityManager $em, UserPasswordEncoder $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function save(User $user)
    {
        $user = $this->manageCredentials($user);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function manageCredentials(User $user)
    {
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        return $user;
    }
}
