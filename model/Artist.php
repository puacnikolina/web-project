<?php
class Artist {

    private $id;
    private $name;
    private $birth_year;
    private $death_year;
    private $nationality;
    private $biography;
    private $profile_image;

    public function __construct($id = null, $name = null, $birth_year = null, $death_year = null, $nationality = null, $biography = null, $profile_image = null) {
        $this->id = $id;
        $this->name = $name;
        $this->birth_year = $birth_year;
        $this->death_year = $death_year;
        $this->nationality = $nationality;
        $this->biography = $biography;
        $this->profile_image = $profile_image;
    }


    public function getId() { return $this->id; }

    public function getName() { return $this->name;}

    public function getBirthYear() { return $this->birth_year;}

    public function getDeathYear() { return $this->death_year;}

    public function getNationality() { return $this->nationality;}

    public function getBiography() { return $this->biography;}

    public function getProfileImage() { return $this->profile_image;}

}
