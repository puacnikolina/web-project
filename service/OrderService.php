<?php
require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../model/OrderItem.php';

class OrderService {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function saveOrder($user_id, $cart, $total, $artworkService) {
        try {
            $this->pdo->beginTransaction();
        
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
            $stmt->execute([$user_id, $total]);
            $order_id = $this->pdo->lastInsertId();

            foreach ($cart as $artwork_id) {
                $artwork = $artworkService->getById($artwork_id);
                if (!$artwork) continue;
                $price = $artwork->getPrice();
                $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, artwork_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $artwork_id, 1, $price]);
        
                $stmt = $this->pdo->prepare("UPDATE artwork SET sold = 1 WHERE id = ?");
                $stmt->execute([$artwork_id]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getOrdersByUserId($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM orders 
            WHERE user_id = ? 
            ORDER BY order_date DESC
        ");
        $stmt->execute([$user_id]);
        $orderRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $orders = [];

        foreach ($orderRows as $row) {
            $orders[] = new Order(
                $row['id'],
                $row['user_id'],
                $row['order_date'],
                $row['status'],
                $row['total_amount']
            );
        }

        $stmt = $this->pdo->prepare("
            SELECT * FROM order_items WHERE order_id = ?
        ");
        foreach ($orders as $order) {
            $stmt->execute([$order->getId()]);
            $itemRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $items = [];
            foreach ($itemRows as $itemRow) {
                $items[] = new OrderItem(
                    $itemRow['id'],
                    $itemRow['order_id'],
                    $itemRow['artwork_id'],
                    $itemRow['quantity'],
                    $itemRow['price_per_item']
                );
            }
            $order->setItems($items);
        }
        return $orders;
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders");
        return $stmt->fetchColumn();
    }

    public function getAllOrders() {
        $stmt = $this->pdo->query("
            SELECT o.*, u.username 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.order_date DESC
        ");

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order(
                $row['id'],
                $row['user_id'],
                $row['order_date'],
                $row['status'],
                $row['total_amount'],
            );
            $order->setUsername($row['username']);

            $orders[] = $order;
        }

        return $orders;
    }


    public function updateOrderStatus($order_id, $status) {
        if (!in_array($status, ['pending', 'completed'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $order_id]);
    }

    public function getTotalEarnings(): float {
        $stmt = $this->pdo->query("SELECT SUM(total_amount) AS total FROM orders");
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

} 