<?php
require_once __DIR__ . '/../model/Artwork.php';

class ArtworkService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM artwork WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Artwork(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['image'],
                isset($row['sold']) ? $row['sold'] : 0
            );
        }

        return null;
    }
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM artwork ORDER BY title ASC");
        $artworks = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $artworks[] = new Artwork(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['image'],
                isset($row['sold']) ? $row['sold'] : 0
            );
        }

        return $artworks;
    }

        public function save(Artwork $artwork) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO artwork (title, description, price, image) 
            VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $artwork->getTitle(),
            $artwork->getDescription(),
            $artwork->getPrice(),
            $artwork->getImage(),
        ]);
    }


    public function update(Artwork $artwork) {
        $stmt = $this->pdo->prepare(
            "UPDATE artwork SET title = ?, description = ?, price = ?, image = ?
            WHERE id = ?"
        );

        return $stmt->execute([
            $artwork->getTitle(),
            $artwork->getDescription(),
            $artwork->getPrice(),
            $artwork->getImage(),
            $artwork->getId()
        ]);
    }

    
    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM artwork");
        return $stmt->fetchColumn();
    }

    private function deleteImageFile(string $imagePath): void {
        $fullPath = __DIR__ . '/../../' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    
    public function deleteArtwork(int $artworkId): bool {
        try {
            $this->pdo->beginTransaction();

            
            $stmt = $this->pdo->prepare("SELECT image FROM artwork WHERE id = ?");
            $stmt->execute([$artworkId]);
            $artwork = $this->getById($artworkId);

            if ($artwork && !empty($artwork->getImage())) {
                $this->deleteImageFile($artwork->getImage());
            }

    
            $stmt = $this->pdo->prepare("DELETE FROM artwork WHERE id = ?");
            $result = $stmt->execute([$artworkId]);

            $this->pdo->commit();

            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
