<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 12/1/16
 * Time: 6:28 PM
 */

namespace AppBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class RoundRepository extends EntityRepository
{
    /**
     * totalPlayerWins()
     * Returns a count of the total times the player has won
     *
     * @return mixed|int
     */
    public function totalPlayerWins()
    {
        $qb = $this->createQueryBuilder('r')
          ->select('COUNT(r)')
          ->where('r.win = true')
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * totalComputerWins()
     * Returns a count of the total times the computer has won
     *
     * @return mixed
     */
    public function totalComputerWins()
    {
        $qb = $this->createQueryBuilder('r')
          ->select('COUNT(r)')
          ->where('r.win = false AND r.tie = false')
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * totalTimesPlayerPlayed()
     * Get the total number of times a player used a choice
     *
     * @param int $choice
     * @return mixed
     */
    public function totalTimesPlayerPlayed(int $choice)
    {
        $qb = $this->createQueryBuilder('r')
          ->select('COUNT(r)')
          ->where('r.playerChoice = :choice')
          ->setParameter('choice', $choice)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * totalTimesComputerPlayed()
     * Get the total number of times the computer used a choice
     *
     * @param int $choice
     * @return mixed
     */
    public function totalTimesComputerPlayed(int $choice)
    {
        $qb = $this->createQueryBuilder('r')
          ->select('COUNT(r)')
          ->where('r.computerChoice = :choice')
          ->setParameter('choice', $choice)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}