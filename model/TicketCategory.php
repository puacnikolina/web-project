<?php

class TicketCategory
{
    private int $id;
    private string $category_name;
    private float $price;

    public function __construct(int $id, string $category_name, float $price)
    {
        $this->id = $id;
        $this->category_name = $category_name;
        $this->price = $price;
    }

    public function getCategoryName(){ return $this->category_name;}

    public function getPrice(){ return $this->price;}

    public function getId(){ return $this->id;}
}
