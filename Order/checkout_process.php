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
    
    // 🌟 1. 接收 Pax (如果没有传，默认是 1)
    $pax = isset($_POST['pax']) ? intval($_POST['pax']) : 1; 

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
            FROM cartitems ci
            JOIN cart c ON ci.CartID = c.CartID
            JOIN product p ON ci.ProductID = p.ProductID
            WHERE c.SessionID = ?
        ");
        $stmt->execute([$sessionID]);
        $cartitems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cartitems)) {
            echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
            exit;
        }

        $subtotal = 0;
        foreach ($cartitems as $item) {
            $subtotal += $item['Price'] * $item['Quantity'];
        }
        $tax = $subtotal * 0.06;
        $grandTotal = $subtotal + $tax;

        $pdo->beginTransaction();
        
        // Insert Order
        $orderStmt = $pdo->prepare("INSERT INTO orders (CustomerName, TableNumber, Pax, TotalAmount, PaymentMethod, Status) VALUES (?, ?, ?, ?, ?, ?)");
        $orderStmt->execute([$customerName, $tableNumber, $pax, $grandTotal, $paymentMethod, $orderStatus]);
        $orderID = $pdo->lastInsertId();

        // Insert Order Items - Changed orderItems to orderitems
        $itemStmt = $pdo->prepare("INSERT INTO orderitems (OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cartitems as $item) {
            $itemSubtotal = $item['Price'] * $item['Quantity'];
            $itemStmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $itemSubtotal]);
        }

        // 🌟 FIX: Explicitly Delete CartItems First 🌟
        // 1. Get the CartID for this session
        $getCartIdStmt = $pdo->prepare("SELECT CartID FROM cart WHERE SessionID = ?");
        $getCartIdStmt->execute([$sessionID]);
        $cartRow = $getCartIdStmt->fetch(PDO::FETCH_ASSOC);

        if ($cartRow) {
            $currentCartID = $cartRow['CartID'];

            // 2. Delete items inside the cart manually - Changed cartItems to cartitems
            $deleteItemsStmt = $pdo->prepare("DELETE FROM cartitems WHERE CartID = ?");
            $deleteItemsStmt->execute([$currentCartID]);

            // 3. Delete the Cart itself
            $clearStmt = $pdo->prepare("DELETE FROM cart WHERE CartID = ?");
            $clearStmt->execute([$currentCartID]);
        }

        $pdo->commit();

        $response['status'] = 'success';
        $response['message'] = 'Order placed successfully!';
        $response['payment_status'] = $orderStatus; 

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>