<?php
require '../DB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $sessionID = session_id();

    if ($productID > 0 && $quantity > 0) {
        try {
            
            $stmt = $pdo->prepare("SELECT CartID FROM Cart WHERE SessionID = ?");
            $stmt->execute([$sessionID]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                $cartID = $cart['CartID'];
            } else {
                
                $stmt = $pdo->prepare("INSERT INTO Cart (SessionID) VALUES (?)");
                $stmt->execute([$sessionID]);
                $cartID = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("SELECT CartItemID, Quantity FROM CartItems WHERE CartID = ? AND ProductID = ?");
            $stmt->execute([$cartID, $productID]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                
                $newQuantity = $existingItem['Quantity'] + $quantity;
                $updateStmt = $pdo->prepare("UPDATE CartItems SET Quantity = ? WHERE CartItemID = ?");
                $updateStmt->execute([$newQuantity, $existingItem['CartItemID']]);
            } else {
                
                $insertStmt = $pdo->prepare("INSERT INTO CartItems (CartID, ProductID, Quantity) VALUES (?, ?, ?)");
                $insertStmt->execute([$cartID, $productID, $quantity]);
            }

            $response['status'] = 'success';
            $response['message'] = 'Item added to cart successfully!';

        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }
}

echo json_encode($response);
?>