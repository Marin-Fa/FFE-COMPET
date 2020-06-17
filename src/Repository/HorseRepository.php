<?php

namespace App\Repository;

use App\Entity\Horse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Horse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Horse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Horse[]    findAll()
 * @method Horse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorseRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Horse::class);
        $this->security = $security;
    }

    /**
     * @return Horse[]
     */
    public function findAllHorses(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT h
            FROM App\Entity\Horse h
            WHERE h.user = :val'
        )->setParameter('val', $this->security->getUser());

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @param $value
     * @return Horse[]
     */
    public function selectOneHorse($value): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $horse
     * @return array
     */
    public function OneHorse($horse): array
    {
        $qb = $this->createQueryBuilder('h')
            ->where('h.id')
            ->setParameter('horse', $horse);
        $query = $qb->getQuery();
        return $query->execute();
    }
    // /**
    //  * @return Horse[] Returns an array of Horse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Horse
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
