<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;


/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @return Client[]
     */
    public function findAllByCriteria(ClientRecherche $clientRecherche): Query
    {
        // le "p" est un alias utilisé dans la requête
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC');

        if ($clientRecherche->getNom()) {
            $qb->andWhere('p.nom LIKE :nom')
                ->setParameter('nom', $clientRecherche->getNom().'%');
        }

        if ($clientRecherche->getPrenom()) {
            $qb->andWhere('p.prenom LIKE :prenom')
                ->setParameter('prenom', $clientRecherche->getPrenom().'%');
        }

        if ($clientRecherche->getAdresse()) {
            $qb->andWhere('p.adresse LIKE :adresse')
                ->setParameter('adresse', $clientRecherche->getAdresse().'%');

        }

        if ($clientRecherche->getMail()) {
            $qb->andWhere('p.mail LIKE :mail')
                ->setParameter('mail', $clientRecherche->getNom().'%');

        }

        if ($clientRecherche->getTelephone()) {
            $qb->andWhere('p.telephone LIKE :telephone')
                ->setParameter('telephone', $clientRecherche->getTelephone().'%');
        }

        if ($clientRecherche->getDate()) {
        $qb->andWhere('p.date LIKE :date')
            ->setParameter('date', $clientRecherche->getDate().'%');
    }

        return $qb->getQuery();
    }


    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
