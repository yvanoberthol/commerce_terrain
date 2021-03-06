<?php

namespace App\Repository;

use App\Entity\Optione;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param PropertySearch $search
     * @return Query
     */
    public function findAllVisibleQuery(PropertySearch $search):Query{
        $query =  $this->findVisibleQuery();
        if ($search->getMaxprice()){
            $query = $query
                ->where('p.price < :maxprice')
                ->setParameter('maxprice',$search->getMaxprice());
        }
        if ($search->getMinsurface()){
            $query = $query
                ->andWhere('p.surface > :minsurface')
                ->setParameter('minsurface',$search->getMinsurface());
        }

        if ($search->getOptiones()->count() > 0){

            foreach ($search->getOptiones() as $k => $option){
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.optiones")
                    ->setParameter("option$k",$option);
            }

        }

        return $query->getQuery();
    }

    /**
     * @return Property[]
     */
    public function findLasted():array{
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }


    private function findVisibleQuery():QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
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
    public function findOneBySomeField($value): ?Property
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
