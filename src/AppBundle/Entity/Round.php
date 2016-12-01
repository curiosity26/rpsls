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
        $this->player_choice = 0;
        $this->computer_choice = 0;
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
     * @param bool $win
     * @return Round
     */
    public function setWin(bool $win)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTie() {
        return $this->tie;
    }

    /**
     * @param bool $tie
     * @return $this
     */
    public function setTie(bool $tie) {
        $this->tie = $tie;

        return $this;
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
    }


}