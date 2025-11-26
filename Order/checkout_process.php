<?php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'System Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = session_id();
    $customerName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $tableNumber = isset($_POST['table']) ? trim($_POST['table']) : '';

    if (empty($customerName) || empty($tableNumber)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields']);
        exit;
    }

    try {
        // 1. 获取购物车内容
        $stmt = $pdo->prepare("
            SELECT ci.ProductID, ci.Quantity, p.Price 
            FROM CartItems ci
            JOIN Cart c ON ci.CartID = c.CartID
            JOIN Product p ON ci.ProductID = p.ProductID
            WHERE c.SessionID = ?
        ");
        $stmt->execute([$sessionID]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cartItems)) {
            echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
            exit;
        }

        // 2. 计算总价 (包含 6% Tax)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['Price'] * $item['Quantity'];
        }
        $tax = $subtotal * 0.06;
        $grandTotal = $subtotal + $tax;

        // 3. 创建订单 (Insert Order)
        $pdo->beginTransaction();

        $orderStmt = $pdo->prepare("INSERT INTO Orders (CustomerName, TableNumber, TotalAmount) VALUES (?, ?, ?)");
        $orderStmt->execute([$customerName, $tableNumber, $grandTotal]);
        $orderID = $pdo->lastInsertId();

        // 4. 创建订单项 (Insert OrderItems)
        $itemStmt = $pdo->prepare("INSERT INTO OrderItems (OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        
        foreach ($cartItems as $item) {
            $itemSubtotal = $item['Price'] * $item['Quantity'];
            $itemStmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $itemSubtotal]);
        }

        // 5. 清空购物车
        $clearStmt = $pdo->prepare("DELETE FROM Cart WHERE SessionID = ?");
        $clearStmt->execute([$sessionID]);

        $pdo->commit();

        $response['status'] = 'success';
        $response['message'] = 'Order placed successfully!';

    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>