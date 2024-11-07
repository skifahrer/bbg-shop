<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllPaginated(int $page, int $limit): array
    {
        $query = $this->createQueryBuilder('p')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        return $paginator->getIterator()->getArrayCopy();
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
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
            'features_json' => $product->getFeaturesJson()
        ];
    }
}
