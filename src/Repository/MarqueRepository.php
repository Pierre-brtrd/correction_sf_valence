<?php

namespace App\Repository;

use App\Entity\Marque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Marque>
 */
class MarqueRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $pagination
    ) {
        parent::__construct($registry, Marque::class);
    }

    public function findPaginateOrderByDate(int $maxPerPage, int $page, ?string $search = null): PaginationInterface
    {
        $query = $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC');

        if ($search) {
            $query->andWhere('m.name LIKE :search')
                ->setParameter('search', "%$search%");
        }

        return $this->pagination->paginate(
            $query->getQuery(),
            $page,
            $maxPerPage
        );
    }

    //    /**
    //     * @return Marque[] Returns an array of Marque objects
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

    //    public function findOneBySomeField($value): ?Marque
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
