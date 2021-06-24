<?php

namespace App\Form\DTO;

use App\Entity\Order;
use App\Entity\User;

class EditOrderModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var User
     */
    public $owner;

    /**
     * @var int
     */
    public $status;

    /**
     * @var string
     */
    public $totalPrice;

    /**
     * @var \DateTime
     */
    public $createdAt;

    public static function makeFromOrder(?Order $order): self
    {
        $model = new self();
        if (!$order) {
            return $model;
        }

        $model->id = $order->getId();
        $model->owner = $order->getOwner();
        $model->status = $order->getStatus();
        $model->totalPrice = $order->getTotalPrice();
        $model->createdAt = $order->getCreatedAt();
    }
}