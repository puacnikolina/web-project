<?php
require_once __DIR__ . '/../model/Exhibition.php';

class ExhibitionService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*, a.name AS artist_name
            FROM exhibitions e
            LEFT JOIN artists a ON e.artist_id = a.id
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Exhibition(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start_date'],
                $row['end_date'],
                $row['hero_image'],
                $row['gallery_image'],
                $row['is_active'],
                $row['artist_id'],
                $row['artist_name']
            );
        }

        return null;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT e.*, a.name AS artist_name
                FROM exhibitions e
                LEFT JOIN artists a ON e.artist_id = a.id
                ORDER BY e.start_date ASC");
        $exhibitions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $exhibitions[] = new Exhibition(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start_date'],
                $row['end_date'],
                $row['hero_image'],
                $row['gallery_image'],
                $row['is_active'],
                $row['artist_id'],
                $row['artist_name'] 
                
            );
        }
        return $exhibitions;
    }


    public function getCurrent() {
        $stmt = $this->pdo->prepare("
            SELECT e.*, a.name AS artist_name
            FROM exhibitions e
            LEFT JOIN artists a ON e.artist_id = a.id
            WHERE e.is_active = 1
            LIMIT 1
        ");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Exhibition(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start_date'],
                $row['end_date'],
                $row['hero_image'],
                $row['gallery_image'],
                $row['is_active'],
                $row['artist_id'],
                $row['artist_name']
            );
        }
        return null;
    }


    public function save(Exhibition $exhibition)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO exhibitions (title, description, start_date, end_date, hero_image, gallery_image, is_active, artist_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $exhibition->getTitle(),
            $exhibition->getDescription(),
            $exhibition->getStartDate(),
            $exhibition->getEndDate(),
            $exhibition->getHeroImage(),
            $exhibition->getGalleryImage(),
            $exhibition->getIsActive(),
            $exhibition->getArtistId()
        ]);
    }

    public function update(Exhibition $exhibition)
    {
        $stmt = $this->pdo->prepare("
            UPDATE exhibitions 
            SET title = ?, description = ?, start_date = ?, end_date = ?, hero_image = ?, gallery_image = ?, is_active = ?, artist_id = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $exhibition->getTitle(),
            $exhibition->getDescription(),
            $exhibition->getStartDate(),
            $exhibition->getEndDate(),
            $exhibition->getHeroImage(),
            $exhibition->getGalleryImage(),
            $exhibition->getIsActive(),
            $exhibition->getArtistId(),
            $exhibition->getId()
        ]);
    }

    
        public function delete($id) {
    
        $exhibition = $this->getById($id);

        if ($exhibition) {
        
            $images = [
                $exhibition->getHeroImage(),
                $exhibition->getGalleryImage()
            ];

            foreach ($images as $imagePath) {
                if ($imagePath) {
                    $fullPath = __DIR__ . '/../../' . $imagePath;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }

    
        $stmt = $this->pdo->prepare("DELETE FROM exhibitions WHERE id = ?");
        return $stmt->execute([$id]);
    }



    public function getCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM exhibitions");
        return $stmt->fetchColumn();
    }

    public function searchByTitle($term) {
        $stmt = $this->pdo->prepare("SELECT e.*, a.name AS artist_name FROM exhibitions e LEFT JOIN artists a ON e.artist_id = a.id WHERE e.title LIKE ? ORDER BY e.start_date DESC");
        $like = '%' . $term . '%';
        $stmt->execute([$like]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Exhibition(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['start_date'],
                $row['end_date'],
                $row['hero_image'],
                $row['gallery_image'],
                $row['is_active'],
                $row['artist_id'],
                $row['artist_name'] ?? null
            );
        }
        return $results;
    }
    
}

