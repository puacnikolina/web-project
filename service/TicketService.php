<?php
require_once __DIR__ . '/../model/Ticket.php';

class TicketService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveTicket(Ticket $ticket)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tickets (user_id, exhibition_id, category_id, quantity, total_price, reservation_date)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $ticket->getUserId(),
            $ticket->getExhibitionId(),
            $ticket->getCategoryId(),
            $ticket->getQuantity(),
            $ticket->getTotalPrice(),
            $ticket->getReservationDate()
        ]);
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tickets");
        return $stmt->fetchColumn();
    }

    public function getTicketsByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY reservation_date DESC");
        $stmt->execute([$user_id]);
        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = new Ticket(
                $row['user_id'],
                $row['exhibition_id'],
                $row['category_id'],
                $row['quantity'],
                $row['total_price'],
                $row['reservation_date']
            );
        }
        return $tickets;
    }

    public function getTotalEarnings(): float {
        $stmt = $this->pdo->query("SELECT SUM(total_price) AS total FROM tickets");
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

}
