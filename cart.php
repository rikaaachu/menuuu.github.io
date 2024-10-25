<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dishId = $_POST['dish_id'];
    $quantity = $_POST['quantity'];
    $userId = 1; 

    try {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, dish_id, quantity, date_added) VALUES (:user_id, :dish_id, :quantity, NOW())
                               ON DUPLICATE KEY UPDATE quantity = quantity + :quantity");
        $stmt->execute(['user_id' => $userId, 'dish_id' => $dishId, 'quantity' => $quantity]);
        echo json_encode(['message' => 'Item added to cart']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = 1; 

    try {
        $stmt = $pdo->prepare("SELECT c.quantity, d.dish FROM cart c JOIN dishes d ON c.dish_id = d.d_id WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cartItems);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
