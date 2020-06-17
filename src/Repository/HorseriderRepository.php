<?php

namespace App\Repository;

use App\Entity\Horserider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Horserider|null find($id, $lockMode = null, $lockVersion = null)
 * @method Horserider|null findOneBy(array $criteria, array $orderBy = null)
 * @method Horserider[]    findAll()
 * @method Horserider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorseriderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Horserider::class);
    }

    public function findAllByHorseRiders($startNumber)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('h')
            ->from($this->_entityName, 'h')
            ->where('h.user = :user')
            ->orderBy('h.start_number', 'ASC')
            ->setParameter('user', $startNumber)
        ;
        return $qb->getQuery()->getArrayResult();
    }

    public function findByIdEvent($eventId)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT h, e
            FROM App\Entity\Horserider h
            INNER JOIN h.event e
            WHERE e.id = :id
            ORDER BY h.start_number ASC'
        )->setParameter('id', $eventId);
        ;
        return $query->getResult();
    }

    // /**
    //  * @return Horserider[] Returns an array of Horserider objects
    //  */
    public function findByStartNumber($number)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.start_number = :number')
            ->setParameter('number', $number)
            ->orderBy('h.start_number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Horserider
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
