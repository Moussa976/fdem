<?php

namespace App\Repository;

use App\Entity\Matche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matche>
 *
 * @method Matche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matche[]    findAll()
 * @method Matche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatcheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matche::class);
    }

    public function add(Matche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Matche $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMatchsByEquipe($equipeId)
    {
        return $this->createQueryBuilder('m')
            ->where('m.equipe1 = :equipeId')
            ->orWhere('m.equipe2 = :equipeId')
            ->setParameter('equipeId', $equipeId)
            ->orderBy('m.ladate', 'DESC') // Trier par date, du plus récent au plus ancien
            ->getQuery()
            ->getResult();
    }

    public function findAllMatchs()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.ladate', 'DESC') // Trier par date, du plus récent au plus ancien
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Matche[] Returns an array of Matche objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matche
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
