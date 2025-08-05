<?php
require_once __DIR__ . '/../model/TicketCategory.php';

class TicketCategoryService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories(): array
{
    $stmt = $this->pdo->query("SELECT * FROM ticket_categories");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categories = [];
    foreach ($rows as $row) {
        $categories[] = new TicketCategory(
            $row['id'],
            $row['category_name'],
            $row['price']
        );
    }

    return $categories;
}

}
