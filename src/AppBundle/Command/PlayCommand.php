<?php
/**
 * Created by PhpStorm.
 * User: Luk
 * Date: 15/05/2017
 * Time: 10:45
 */

namespace AppBundle\Command;


use AppBundle\Game\Game;
use AppBundle\Game\WordList;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayCommand extends ContainerAwareCommand
{
    const COLOR_MAP = array('blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'green', 'green', 'green', 'red', 'black;bg=red;options=bold', 'black;bg=red;options=bold');

    function configure()
    {
        $this->setName('hangman:play')
            ->setDescription('Play !');
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $container = $this->getContainer();
        /** @var WordList $wordList */
        $wordList = $container->get('app.wordlist');
        $word = $wordList->getRandomWord(8);
        /** @var Game $game */
        $game = new Game($word);

        while(!$game->isOver()) {
            $text = '';
            $color = self::COLOR_MAP[$game->getAttempts()];
            foreach($game->getWordLetters() as $l) {
                if($game->isLetterFound($l)) {
                    $text .= ' ' . strtoupper($l) . ' ';
                } else {
                    $text .= ' _ ';
                }
            }
            $text .= sprintf('<fg=%s>Number of attempts : %d/%d [%s]</>', $color, $game->getAttempts(), $game::MAX_ATTEMPTS, strtoupper(implode('-', array_diff($game->getTriedLetters(), $game->getFoundLetters()))));
            $io->ask("What is the next Letter ? $text", null, function($letter) use ($game) {
                $game->tryLetter($letter);
            });
        }
        if($game->isWon()) {
            $io->writeln('You win !!' . $word );
        } else {
            $io->writeln('You loose ! The word is : ' . $word );
        }
    }
}