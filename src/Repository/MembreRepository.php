<?php

namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Membre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membre[]    findAll()
 * @method Membre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    public function getEmail() {
        $queryBld = $this -> createQueryBuilder('m');
        $queryBld
            ->andWhere("m.password = 'hello'");
        $query = $queryBld -> getQuery();
        return $query ->getResult();
    }

    public function getLastSuscbribers () {
        $queryBld = $this->createQueryBuilder('m');
        $queryBld
            ->addOrderBy('m.dateAdded', 'DESC');
        $queryBld ->setMaxResults(20);
        $query = $queryBld->getQuery();
        return $query->getResult();
    }
}
