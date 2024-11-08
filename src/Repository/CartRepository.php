<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;


#[ORM\Entity(repositoryClass: CartRepository::class)]
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findActiveCartByUser(User $user): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.checkout', 'ch')
            ->where('c.user = :user')
            ->andWhere('ch.order IS NULL')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Cart $cart, bool $flush = false): void
    {
        $this->getEntityManager()->persist($cart);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
