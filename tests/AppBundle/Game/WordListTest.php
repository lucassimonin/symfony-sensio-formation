<?php

namespace AppBundle\Tests\Game;

use AppBundle\Game\WordList;

class WordListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testLoadDictionariesWithNoLoader()
    {
        $wordlist = new WordList();
        $wordlist->loadDictionaries(array('/path/to/fake/dictionary.txt'));
    }

    public function testLoadDictionaries()
    {
        $loader = $this->getMock('AppBundle\Game\Loader\TextFileLoader');

        $loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array('php')))
        ;

        $wordlist = new WordList();
        $wordlist->addLoader('txt', $loader);

        $wordlist->loadDictionaries(array('/path/to/fake/dictionary.txt'));

        $reflection = new \ReflectionProperty(WordList::class, 'words');
        $reflection->setAccessible(true);

        $this->assertContains('php', $reflection->getValue($wordlist)[3]);
    }
}
