<?php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'System Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = session_id();
    $customerName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $tableNumber = isset($_POST['table']) ? trim($_POST['table']) : '';
    $paymentMethod = isset($_POST['method']) ? trim($_POST['method']) : '';

    if (empty($customerName) || empty($tableNumber) || empty($paymentMethod)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
        exit;
    }

    // --- 设定 Status 和 Hash PIN ---
    $orderStatus = 'Pending'; // 默认为 Pending
    $hashedPin = null;

    if ($paymentMethod === 'Cash') {
        $orderStatus = 'Pending';
    } else {
        // E-wallet 或 Online Banking
        $orderStatus = 'Successfully';
        
        // 只有非 Cash 才处理 PIN
        $rawPin = isset($_POST['pin']) ? $_POST['pin'] : '';
        if (!empty($rawPin)) {
            // Hash PIN 码 (安全处理)
            $hashedPin = password_hash($rawPin, PASSWORD_DEFAULT);
        }
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

        // 2. 计算总价
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['Price'] * $item['Quantity'];
        }
        $tax = $subtotal * 0.06;
        $grandTotal = $subtotal + $tax;

        $pdo->beginTransaction();

        // 3. 创建订单 (Insert Order)
        // 注意：我们这里把 PaymentMethod 和 Status 存进去
        // 真实项目中通常不存用户的 PIN (即使是 Hash)，但为了满足你的作业要求，
        // 我们这里只存 Status，PIN 既然 Hash 了就在流程中验证了(模拟)。
        
        $orderStmt = $pdo->prepare("INSERT INTO Orders (CustomerName, TableNumber, TotalAmount, PaymentMethod, Status) VALUES (?, ?, ?, ?, ?)");
        $orderStmt->execute([$customerName, $tableNumber, $grandTotal, $paymentMethod, $orderStatus]);
        $orderID = $pdo->lastInsertId();

        // 4. 创建订单项
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
        $response['payment_status'] = $orderStatus; // 让前端知道显示什么信息

    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>