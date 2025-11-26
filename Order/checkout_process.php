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

    $orderStatus = 'Pending'; 
    $hashedPin = null;

    if ($paymentMethod === 'Cash') {
        $orderStatus = 'Pending';
    } else {
        $orderStatus = 'Successfully';

        $rawPin = isset($_POST['pin']) ? $_POST['pin'] : '';
        if (!empty($rawPin)) {
            $hashedPin = password_hash($rawPin, PASSWORD_DEFAULT);
        }
    }

    try {
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

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['Price'] * $item['Quantity'];
        }
        $tax = $subtotal * 0.06;
        $grandTotal = $subtotal + $tax;

        $pdo->beginTransaction();
        
        $orderStmt = $pdo->prepare("INSERT INTO Orders (CustomerName, TableNumber, TotalAmount, PaymentMethod, Status) VALUES (?, ?, ?, ?, ?)");
        $orderStmt->execute([$customerName, $tableNumber, $grandTotal, $paymentMethod, $orderStatus]);
        $orderID = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare("INSERT INTO OrderItems (OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        
        foreach ($cartItems as $item) {
            $itemSubtotal = $item['Price'] * $item['Quantity'];
            $itemStmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $itemSubtotal]);
        }

        $clearStmt = $pdo->prepare("DELETE FROM Cart WHERE SessionID = ?");
        $clearStmt->execute([$sessionID]);

        $pdo->commit();

        $response['status'] = 'success';
        $response['message'] = 'Order placed successfully!';
        $response['payment_status'] = $orderStatus; 

    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>