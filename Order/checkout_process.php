<?php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'System Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = session_id();
    
    // Get standard fields
    $customerName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $tableNumber = isset($_POST['table']) ? trim($_POST['table']) : '';
    $paymentMethod = isset($_POST['method']) ? trim($_POST['method']) : '';
    $pax = isset($_POST['pax']) ? intval($_POST['pax']) : 1; 

    // 🌟 NEW: Get Delivery specific fields
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;

    // Basic Validation
    if (empty($customerName) || empty($tableNumber) || empty($paymentMethod)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
        exit;
    }

    // Delivery Validation: If table is "Delivery", address and phone are required
    if ($tableNumber === 'Delivery') {
        if (empty($address) || empty($phone)) {
            echo json_encode(['status' => 'error', 'message' => 'Address and Phone are required for delivery']);
            exit;
        }
    }

    // Payment Status Logic
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
        // 1. Fetch Cart Items
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

        // 2. Calculate Totals
        $subtotal = 0;
        foreach ($cartitems as $item) {
            $subtotal += $item['Price'] * $item['Quantity'];
        }
        $tax = $subtotal * 0.06;
        $grandTotal = $subtotal + $tax;

        $pdo->beginTransaction();
        
        // 3. Insert Order (Updated with Address & Phone)
        // Note: Ensure you have added DeliveryAddress and PhoneNumber columns to your database as per instructions.
        $orderStmt = $pdo->prepare("
            INSERT INTO orders 
            (CustomerName, TableNumber, Pax, TotalAmount, PaymentMethod, Status, DeliveryAddress, PhoneNumber) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $orderStmt->execute([
            $customerName, 
            $tableNumber, 
            $pax, 
            $grandTotal, 
            $paymentMethod, 
            $orderStatus,
            $address, // New
            $phone    // New
        ]);
        
        $orderID = $pdo->lastInsertId();

        // 4. Insert Order Items
        $itemStmt = $pdo->prepare("INSERT INTO orderitems (OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cartitems as $item) {
            $itemSubtotal = $item['Price'] * $item['Quantity'];
            $itemStmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $itemSubtotal]);
        }

        // 5. Clear Cart
        $getCartIdStmt = $pdo->prepare("SELECT CartID FROM cart WHERE SessionID = ?");
        $getCartIdStmt->execute([$sessionID]);
        $cartRow = $getCartIdStmt->fetch(PDO::FETCH_ASSOC);

        if ($cartRow) {
            $currentCartID = $cartRow['CartID'];

            // Delete items inside the cart
            $deleteItemsStmt = $pdo->prepare("DELETE FROM cartitems WHERE CartID = ?");
            $deleteItemsStmt->execute([$currentCartID]);

            // Delete the Cart itself
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