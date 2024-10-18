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

    // method to apply discount
    public function applyDiscount() 
    {
        return $this->createQueryBuilder('p')
            ->where('p.remise > 0')
            ->getQuery()
            ->getResult();
    }

    // method to filter product company
    // public function filterByCompany($entreprise)
    // {
    //     return $this->createQueryBuilder('p')
    //         ->leftJoin('p.entreprise', 'e')
    //         ->where('e.nom_entreprise = :entreprise')
    //         ->setParameter('entreprise', $entreprise)
    //         ->getQuery()
    //         ->getResult();
    // }
}
