<?php
class Order {
    private $id;
    private $user_id;
    private $order_date;
    private $status;
    private $total_amount;
    private $username;
    private $items = [];

    public function __construct($id, $user_id, $order_date, $status, $total_amount) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->order_date = $order_date;
        $this->status = $status;
        $this->total_amount = $total_amount;
    }

    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getOrderDate() { return $this->order_date; }
    public function getStatus() { return $this->status; }
    public function getTotalAmount() { return $this->total_amount; }
    public function getUsername() { return $this->username; }
    public function getItems() { return $this->items; }
    public function setItems($items) { $this->items = $items; }
    public function setUsername($username) {$this->username = $username;}
} 