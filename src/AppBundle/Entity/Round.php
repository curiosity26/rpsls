<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/30/16
 * Time: 6:50 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The choice made by the Human Player
     *
     * @var int $player_choice
     * @ORM\Column(type="integer", options={unsigned=true})
     */
    private $player_choice;

    /**
     * The choice made by the Computer Player
     *
     * @var int $computer_choice
     * @ORM\Column(type="integer", options={unsigned=true})
     */
    private $computer_choice;

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
     * @var \DateTime $created_at
     * @ORM\Column(type="datetimetz")
     */
    private $created_at;

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
        return $this->player_choice;
    }

    /**
     * @param mixed $player_choice
     * @return Round
     */
    public function setPlayerChoice(int $player_choice)
    {
        $this->player_choice = $player_choice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComputerChoice()
    {
        return $this->computer_choice;
    }

    /**
     * @param mixed $computer_choice
     * @return Round
     */
    public function setComputerChoice(int $computer_choice)
    {
        $this->computer_choice = $computer_choice;

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
        return $this->created_at;
    }

    /**
     * PerPersist lifecyle event
     *
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();

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