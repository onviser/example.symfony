<?php

namespace App\Repository;

use App\Entity\PropertyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyType[]    findAll()
 * @method PropertyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyType::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PropertyType $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PropertyType $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
