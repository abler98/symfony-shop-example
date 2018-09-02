<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param string $slug
     * @return Category|null
     */
    public function findBySlug(string $slug): ?Category
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @return Category[]
     */
    public function findAllWithLastProducts()
    {
        $builder = $this->createQueryBuilder('c');

        $builder->leftJoin('c.products', 'p')->addSelect('p');
        $builder->leftJoin('p.images', 'i')->addSelect('i');

        return $builder->getQuery()->execute();
    }
}
