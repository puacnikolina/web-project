<?php
class OrderItem {
    private $id;
    private $order_id;
    private $artwork_id;
    private $quantity;
    private $price_per_item;

    public function __construct($id, $order_id, $artwork_id, $quantity, $price_per_item) {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->artwork_id = $artwork_id;
        $this->quantity = $quantity;
        $this->price_per_item = $price_per_item;
    }

    public function getId() { return $this->id; }
    public function getOrderId() { return $this->order_id; }
    public function getArtworkId() { return $this->artwork_id; }
    public function getQuantity() { return $this->quantity; }
    public function getPricePerItem() { return $this->price_per_item; }
} 