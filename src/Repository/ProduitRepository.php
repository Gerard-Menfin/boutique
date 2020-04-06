<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByTitre($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.titre LIKE :val')
            ->setParameter('val', "%" . $value . "%")
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByTitreCategorieDescription($recherche){
        // SELECT * FROM `produit` 
        // WHERE categorie LIKE "%pull%" 
        //      OR titre LIKE "%pull%" 
        //      OR description LIKE "%pull%"  
        
        // version avec EntityManager
        $entityManager = $this->getEntityManager();
        $requete = $entityManager->createQuery("SELECT p FROM App\Entity\Produit p WHERE p.categorie LIKE '%$recherche%' OR p.titre LIKE '%$recherche%' OR p.description LIKE '%$recherche%'");
        return $requete->getResult();

        // version avec CreateQueryBuilder
        return $this->createQueryBuilder('p')
            ->andWhere('p.titre LIKE :val OR  p.categorie LIKE :val OR p.description LIKE :val')
            ->setParameter('val', "%" . $recherche . "%")
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;

    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
