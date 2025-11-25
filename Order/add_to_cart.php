<?php
// Order/add_to_cart.php
require '../DB.php';

// 开启 Session (如果 DB.php 没开的话)
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
            // 1. 检查当前 Session 是否已有购物车
            $stmt = $pdo->prepare("SELECT CartID FROM Cart WHERE SessionID = ?");
            $stmt->execute([$sessionID]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart) {
                $cartID = $cart['CartID'];
            } else {
                // 没有购物车，创建一个新的
                $stmt = $pdo->prepare("INSERT INTO Cart (SessionID) VALUES (?)");
                $stmt->execute([$sessionID]);
                $cartID = $pdo->lastInsertId();
            }

            // 2. 检查该商品是否已经在购物车里
            $stmt = $pdo->prepare("SELECT CartItemID, Quantity FROM CartItems WHERE CartID = ? AND ProductID = ?");
            $stmt->execute([$cartID, $productID]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // 如果有，更新数量
                $newQuantity = $existingItem['Quantity'] + $quantity;
                $updateStmt = $pdo->prepare("UPDATE CartItems SET Quantity = ? WHERE CartItemID = ?");
                $updateStmt->execute([$newQuantity, $existingItem['CartItemID']]);
            } else {
                // 如果没有，插入新条目
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