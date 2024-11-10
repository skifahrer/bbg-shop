<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    private function createSearchQueryBuilder(string $search = '', string $locale = 'en'): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        if ($search) {
            // Map locale to entity field name
            $titleField = match ($locale) {
                'sk' => 'title_sk',
                'sl' => 'title_sl',
                'hu' => 'title_hu',
                'hr' => 'title_hr',
                'ro' => 'title_ro',
                'bg' => 'title_bg',
                default => 'title_en',
            };

            $qb->where("LOWER(p.{$titleField}) LIKE LOWER(:search)")
                ->orWhere('LOWER(p.item) LIKE LOWER(:search)')
                ->setParameter('search', '%'.strtolower($search).'%');
        }

        return $qb;
    }

    public function findBySearchPaginated(string $search, int $page, int $limit, string $locale = 'en'): array
    {
        $qb = $this->createSearchQueryBuilder($search, $locale)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb->getQuery());

        return $paginator->getIterator()->getArrayCopy();
    }

    public function countBySearch(string $search, string $locale = 'en'): int
    {
        $qb = $this->createSearchQueryBuilder($search, $locale)
            ->select('COUNT(p.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findAllPaginated(int $page, int $limit): array
    {
        return $this->findBySearchPaginated('', $page, $limit);
    }

    public function countAll(): int
    {
        return $this->countBySearch('');
    }

    public function getProductData(Product $product): array
    {
        return [
            'id' => $product->getId()->toString(),
            'item' => $product->getItem(),
            'oxprice' => $product->getOxprice(),
            'weight' => $product->getWeight(),
            'stock' => $product->getStock(),
            'length' => $product->getLength(),
            'width' => $product->getWidth(),
            'height' => $product->getHeight(),
            'title_en' => $product->getTitleEn(),
            'title_sk' => $product->getTitleSk(),
            'title_sl' => $product->getTitleSl(),
            'title_hu' => $product->getTitleHu(),
            'title_hr' => $product->getTitleHr(),
            'title_ro' => $product->getTitleRo(),
            'title_bg' => $product->getTitleBg(),
            'features_json' => $product->getFeaturesJson(),
        ];
    }
}
