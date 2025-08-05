<?php
class Exhibition
{
    private $id;
    private $title;
    private $description;
    private $startDate;
    private $endDate;
    private $heroImage;
    private $galleryImage;
    private $isActive;
    private $artistId;
    private $artistName; 

    public function __construct(
        $id,
        $title,
        $description,
        $startDate,
        $endDate,
        $heroImage,
        $galleryImage,
        $isActive,
        $artistId,
        $artistName = null 
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->heroImage = $heroImage;
        $this->galleryImage = $galleryImage;
        $this->isActive = $isActive;
        $this->artistId = $artistId;
        $this->artistName = $artistName;
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getStartDate() { return $this->startDate; }
    public function getEndDate() { return $this->endDate; }
    public function getHeroImage() { return $this->heroImage; }
    public function getGalleryImage() { return $this->galleryImage; }
    public function getIsActive() { return $this->isActive; }
    public function getArtistId() { return $this->artistId; }
    public function getArtistName() { return $this->artistName; }

    
}

