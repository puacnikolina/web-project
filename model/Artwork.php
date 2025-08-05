<?php

class Artwork
{
    private $id;
    private $title;
    private $description;
    private $price;
    private $image;
    private $sold;

    public function __construct(
        $id,
        $title,
        $description,
        $price,
        $image,
        $sold = 0
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->sold = $sold;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getSold(): int
    {
        return $this->sold;
    }

}
