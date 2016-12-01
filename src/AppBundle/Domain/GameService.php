<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/30/16
 * Time: 7:07 PM
 */

namespace AppBundle\Domain;

use AppBundle\Entity\Round;
use Doctrine\ORM\EntityManager;

/**
 * Class GameService
 * @package AppBundle\Domain
 *
 * A service to determine a winner in a round of Rock, Paper, Scissors, Lizard, Spock
 */
class GameService
{
    const ROCK = 1;
    const PAPER = 2;
    const SCISSORS = 3;
    const LIZARD = 4;
    const SPOCK = 5;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * GameService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * isHumanWinner()
     * Determines whether or not the human player won the round.
     * Updates the Round to reflect this or sets the tie flag when a tie occurs.
     *
     * @param Round $round
     * @return bool
     */
    public function isHumanWinner(Round $round)
    {
        $player = $round->getPlayerChoice();
        $computer = $round->getComputerChoice();

        // If the player did not make a move in time, they lose
        if (0 === $player) {
            // Save the round
            $this->recordRound($round);

            return false;
        }

        // If it's a tie, update the Round and return false (there's no winner)
        if ($player === $computer) {
            $round->setTie(true);
            // Save the round
            $this->recordRound($round);

            return false;
        }

        switch ($player) {
            case self::ROCK:
                // Rock crushes Scissors and Lizard
                $round->setWin(self::SCISSORS === $computer || self::LIZARD === $computer);
                break;
            case self::PAPER:
                // Paper covers Rock, disproves Spock
                $round->setWin(self::ROCK === $computer || self::SPOCK === $computer);
                break;
            case self::SCISSORS:
                // Scissors cuts Paper, decapitates Lizard
                $round->setWin(self::PAPER === $computer || self::LIZARD === $computer);
                break;
            case self::SPOCK:
                // Spock smashes scissors, vaporizes Rock
                $round->setWin(self::SCISSORS === $computer || self::ROCK === $computer);
                break;
            case self::LIZARD:
                // Lizard poisons Spock, eats Paper
                $round->setWin(self::SPOCK === $computer || self::PAPER === $computer);
                break;
        }

        // Save the round
        $this->recordRound($round);

        return $round->isWin();
    }

    /**
     * recordRound()
     * Persists the round to the database
     *
     * @param Round $round
     */
    private function recordRound(Round $round)
    {
        // Record the outcome of the round
        $this->em->persist($round);
        $this->em->flush();
    }
}