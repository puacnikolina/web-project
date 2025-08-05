<?php
require_once __DIR__ . '/../model/Artist.php';


class ArtistService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Artist(
                $row['id'],
                $row['name'],
                $row['birth_year'],
                $row['death_year'],
                $row['nationality'],
                $row['biography'],
                $row['profile_image']
            );
        }

        return null;
    }

    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM artists ORDER BY name ASC");
        $artists = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $artists[] = new Artist(
                $row['id'],
                $row['name'],
                $row['birth_year'],
                $row['death_year'],
                $row['nationality'],
                $row['biography'],
                $row['profile_image']
            );
        }

        return $artists;
    }

    
    public function save(Artist $artist) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO artists (name, birth_year, death_year, nationality, biography, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $artist->getName(),
            $artist->getBirthYear(),
            $artist->getDeathYear(),
            $artist->getNationality(),
            $artist->getBiography(),
            $artist->getProfileImage()
        ]);
    }

    
    public function update(Artist $artist) {
        $stmt = $this->pdo->prepare(
            "UPDATE artists SET name = ?, birth_year = ?, death_year = ?, nationality = ?, biography = ?, profile_image = ? 
            WHERE id = ?"
        );

        return $stmt->execute([
            $artist->getName(),
            $artist->getBirthYear(),
            $artist->getDeathYear(),
            $artist->getNationality(),
            $artist->getBiography(),
            $artist->getProfileImage(),
            $artist->getId()
        ]);
    }



    public function getCount() {
    $stmt = $this->pdo->query("SELECT COUNT(*) FROM artists");
    return $stmt->fetchColumn();
    }

    public function deleteExhibitionsByArtistId(int $artistId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM exhibitions WHERE artist_id = ?");
        return $stmt->execute([$artistId]);
    }

    public function deleteArtist($artistId) {
    $artist = $this->getById($artistId);

    if ($artist && $artist->getProfileImage()) {
        $imagePath = __DIR__ . '/../../' . $artist->getProfileImage();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $this->deleteExhibitionsByArtistId($artistId);

    $stmt = $this->pdo->prepare("DELETE FROM artists WHERE id = ?");
    return $stmt->execute([$artistId]);
    }


}
