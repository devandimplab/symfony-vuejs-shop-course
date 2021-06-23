<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OrderManager extends AbstractBaseManager
{
    /**
     * @var CartManager
     */
    private $cartManager;

    public function __construct(EntityManagerInterface $entityManager, CartManager $cartManager)
    {
        parent::__construct($entityManager);
        $this->cartManager = $cartManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    /**
     * @param string $sessionId
     * @param User $user
     */
    public function createOrderFromCartBySessionId(string $sessionId, User $user)
    {
        $cart = $this->cartManager->getRepository()->findOneBy(['sessionId' => $sessionId]);
        if ($cart) {
            $this->createOrderFromCart($cart, $user);
        }
    }

    public function addOrderProductsFromCart(Order $order, int $cartId)
    {
        /** @var Cart $cart */
        $cart = $this->cartManager->getRepository()->find($cartId);

        if ($cart) {
            foreach ($cart->getCartProducts()->getValues() as $cartProduct) {
                $product = $cartProduct->getProduct();

                $orderProduct = new OrderProduct();
                $orderProduct->setAppOrder($order);
                $orderProduct->setQuantity($cartProduct->getQuantity());
                $orderProduct->setPricePerOne($product->getPrice());
                $orderProduct->setProduct($product);

                $order->addOrderProduct($orderProduct);
                $this->entityManager->persist($orderProduct);
            }
        }
    }

    public function createOrderFromCart(Cart $cart, User $user)
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);

        $this->addOrderProductsFromCart($order, $cart->getId());
        $this->recalculateOrderTotalPrice($order);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->cartManager->remove($cart);
    }

    /**
     * @param Order $order
     */
    public function recalculateOrderTotalPrice(Order $order)
    {
        $orderTotalPrice = 0;

        /** @var OrderProduct $orderProduct */
        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
            $orderTotalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $order->setTotalPrice($orderTotalPrice);
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Order $order
     */
    public function remove(object $order)
    {
        $order->setIsDeleted(true);
        $this->save($order);
    }
}
