<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Joueur>
 *
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function add(Joueur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Joueur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getClassementJoueurs()
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.buts', 'b')
            ->leftJoin('j.cartons', 'c')
            ->leftJoin('j.equipe', 'e')
            ->addSelect('e')
            ->addSelect('COUNT(DISTINCT b.id) AS nbButs')
            ->addSelect('(SELECT COUNT(cj.id) FROM App\Entity\Carton cj WHERE cj.joueur = j.id AND cj.couleur = \'jaune\') AS nbCartonsJaunes')
            ->addSelect('(SELECT COUNT(cr.id) FROM App\Entity\Carton cr WHERE cr.joueur = j.id AND cr.couleur = \'rouge\') AS nbCartonsRouges')
            ->groupBy('j.id, e.id')
            ->orderBy('nbButs', 'DESC')
            ->addOrderBy('j.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    public function getStatistiquesJoueur(int $joueurId)
{
    return $this->createQueryBuilder('j')
        ->leftJoin('j.buts', 'b')
        ->leftJoin('j.cartons', 'c')
        ->leftJoin('j.equipe', 'e')
        ->addSelect('e')
        ->addSelect('COUNT(DISTINCT b.id) AS nbButs')
        ->addSelect('(SELECT COUNT(cj.id) FROM App\Entity\Carton cj WHERE cj.joueur = j.id AND cj.couleur = \'jaune\') AS nbCartonsJaunes')
        ->addSelect('(SELECT COUNT(cr.id) FROM App\Entity\Carton cr WHERE cr.joueur = j.id AND cr.couleur = \'rouge\') AS nbCartonsRouges')
        ->where('j.id = :joueurId')
        ->setParameter('joueurId', $joueurId)
        ->groupBy('j.id, e.id')
        ->getQuery()
        ->getOneOrNullResult();
}


    //    /**
//     * @return Joueur[] Returns an array of Joueur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Joueur
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
