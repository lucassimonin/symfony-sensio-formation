<?php

namespace AppBundle\Tests\Game;

use AppBundle\Game\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{
    public function testTryRightWord()
    {
        $game = new Game('test');

        $this->assertTrue($game->tryWord('test'));
    }

    public function testWrongWord()
    {
        $game = new Game('test');

        $this->assertFalse($game->tryWord('wrong'));
        $this->assertSame(11, $game->getAttempts());
    }

    public function testInvalidLetter()
    {
        $this->setExpectedException('InvalidArgumentException');

        $game = new Game('test');
        $game->tryLetter('%');
    }

    public function testAlreadyTriedLetter()
    {
        $game = new Game('test', 0, array('t', 'e', 's'));

        $this->assertFalse($game->tryLetter('t'));
    }

    public function testRightLetter()
    {
        $game = new Game('test');

        $this->assertTrue($game->tryLetter('t'));
    }

    public function testWrongLetter()
    {
        $game = new Game('test');

        $this->assertFalse($game->tryLetter('p'));
    }
}
