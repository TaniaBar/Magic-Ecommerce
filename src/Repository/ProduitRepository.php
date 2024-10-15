<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // method to retrieve data in a crescent order of price
    public function findAllByPriceAsc()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // method to filtre product category
    public function findByCategory($categorie)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categorie', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->getQuery()
            ->getResult();
    }
}
