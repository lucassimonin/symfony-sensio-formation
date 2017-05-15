<?php

namespace AppBundle\Security\Voter;
use AppBundle\Entity\User;
use AppBundle\Security\GameVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Created by PhpStorm.
 * User: Luk
 * Date: 15/05/2017
 * Time: 14:30
 */
class GameVoterTest extends TestCase
{
    /** @var  Voter */
    private $voter;

    protected function setUp()
    {
        $this->voter = new GameVoter();
    }

    public function provideUsers()
    {
        $user = new User();
        $user->setAge(21);
        yield [VoterInterface::ACCESS_GRANTED, $user];

        $user = new User();
        $user->setAge(16);
        yield [VoterInterface::ACCESS_DENIED, $user];
    }

    /**
     * @dataProvider provideUsers
     * @param $expected
     * @param $user
     */
    public function test($expected, $user)
    {
        $token = new UsernamePasswordToken($user, 'credentials', 'main');
        $ret = $this->voter->vote($token, null, ['PLAY']);
        $this->assertEquals($expected, $ret);
    }

}