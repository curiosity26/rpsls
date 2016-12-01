<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/30/16
 * Time: 6:50 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Round
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="round")
 * @ORM\HasLifecycleCallbacks()
 */
class Round
{
    const FORFEIT = 0;
    const ROCK = 1;
    const PAPER = 2;
    const SCISSORS = 3;
    const LIZARD = 4;
    const SPOCK = 5;

    /**
     * The primary key of the round
     *
     * @var int $id
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The choice made by the Human Player
     *
     * @var int $playerChoice
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Assert\Range(min="0", max="6", minMessage="round.players_choice.min", maxMessage="round.player_choice.max")
     */
    private $playerChoice;

    /**
     * The choice made by the Computer Player
     *
     * @var int $computerChoice
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Assert\Range(min="1", max="6", minMessage="round.computers_choice.min", maxMessage="round.computers_choice.max")
     */
    private $computerChoice;

    /**
     * Did the Human Player win?
     * @var bool $win
     * @ORM\Column(type="boolean")
     */
    private $win;

    /**
     * Did the round end in a tie?
     *
     * @var bool $tie
     * @ORM\Column(type="boolean")
     */
    private $tie;

    /**
     * The date and time the round occurred, for organization
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetimetz")
     */
    private $createdAt;

    /**
     * Round constructor.
     */
    public function __construct()
    {
        $this->player_choice = self::FORFEIT;
        $this->computer_choice = self::FORFEIT;
        $this->win = false;
        $this->tie = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Round
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayerChoice()
    {
        return $this->playerChoice;
    }

    /**
     * @param mixed $player_choice
     * @return Round
     */
    public function setPlayerChoice(int $playerChoice)
    {
        $this->playerChoice = $playerChoice;

        return $this;
    }

    /**
     * @return int
     */
    public function getComputerChoice()
    {
        return $this->computerChoice;
    }

    /**
     * @param mixed $computerChoice
     * @return Round
     */
    public function setComputerChoice(int $computerChoice)
    {
        $this->computerChoice = $computerChoice;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWin()
    {
        return $this->win;
    }

    /**
     * @return bool
     */
    public function isTie() {
        return $this->tie;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * PerPersist lifecyle event
     *
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();

        $player = $this->getPlayerChoice();
        $computer = $this->getComputerChoice();

        if ($player === $computer) {
            // If it's a tie, update the Round and return false (there's no winner)
            $this->tie = true;
        } elseif (self::FORFEIT !== $player) {
            switch ($player) {
                case self::ROCK:
                    // Rock crushes Scissors and Lizard
                    $this->win = (self::SCISSORS === $computer || self::LIZARD === $computer);
                    break;
                case self::PAPER:
                    // Paper covers Rock, disproves Spock
                    $this->win = (self::ROCK === $computer || self::SPOCK === $computer);
                    break;
                case self::SCISSORS:
                    // Scissors cuts Paper, decapitates Lizard
                    $this->win = (self::PAPER === $computer || self::LIZARD === $computer);
                    break;
                case self::SPOCK:
                    // Spock smashes scissors, vaporizes Rock
                    $this->win = (self::SCISSORS === $computer || self::ROCK === $computer);
                    break;
                case self::LIZARD:
                    // Lizard poisons Spock, eats Paper
                    $this->win = (self::SPOCK === $computer || self::PAPER === $computer);
                    break;
            }
        }
        // else: If the player did not make a move in time, they lose
    }


}