<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/30/16
 * Time: 9:37 PM
 */

namespace AppBundle\Game;

/**
 * Class ComputerPlayer
 * @package AppBundle\Game
 *
 * A computer player to play against
 */
class ComputerPlayer implements PlayerInterface
{
    /**
     * takeTurn()
     * Get the computer's choice for the game
     * @return int
     */
    public function takeTurn() {
        return (int) rand(1, 6);
    }
}