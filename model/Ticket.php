<?php

class Ticket
{
    private $id;
    private $userId;
    private $exhibitionId;
    private $categoryId;
    private $quantity;
    private $totalPrice;
    private $reservationDate;

    public function __construct($userId, $exhibitionId, $categoryId, $quantity, $totalPrice, $reservationDate = null)
    {
        $this->userId = $userId;
        $this->exhibitionId = $exhibitionId;
        $this->categoryId = $categoryId;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
        $this->reservationDate = $reservationDate ?? date('Y-m-d H:i:s');
    }


    public function getUserId() { return $this->userId; }
    public function getExhibitionId() { return $this->exhibitionId; }
    public function getCategoryId() { return $this->categoryId; }
    public function getQuantity() { return $this->quantity; }
    public function getTotalPrice() { return $this->totalPrice; }
    public function getReservationDate() { return $this->reservationDate; }
}
