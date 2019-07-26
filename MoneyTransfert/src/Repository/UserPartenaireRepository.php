<?php

namespace App\Repository;

use App\Entity\UserPartenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPartenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPartenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPartenaire[]    findAll()
 * @method UserPartenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPartenaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPartenaire::class);
    }

    // /**
    //  * @return UserPartenaire[] Returns an array of UserPartenaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPartenaire
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
