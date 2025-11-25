<?php
// Order/cart.php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$sessionID = session_id();

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cartItemID = intval($_GET['id']);
    $delStmt = $pdo->prepare("DELETE FROM CartItems WHERE CartItemID = ?");
    $delStmt->execute([$cartItemID]);
    header("Location: cart.php");
    exit;
}

// Fetch Cart Items
$cartItems = [];
$totalPrice = 0;

$stmt = $pdo->prepare("
    SELECT ci.CartItemID, ci.Quantity, p.Name, p.Price, p.ImageURL 
    FROM CartItems ci
    JOIN Cart c ON ci.CartID = c.CartID
    JOIN Product p ON ci.ProductID = p.ProductID
    WHERE c.SessionID = ?
");
$stmt->execute([$sessionID]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartItems as $item) {
    $totalPrice += $item['Price'] * $item['Quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Nordic Taste</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #0f2f2f; margin: 0; padding: 0; color: #e8f5e9; min-height: 100vh; display: flex; flex-direction: column; }
        header { background-color: #0b2222; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        .brand { font-size: 20px; font-weight: bold; color: #d0f0d0; letter-spacing: 1px; }
        .nav-links a { color: #aebcb9; text-decoration: none; margin-left: 25px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; transition: color 0.3s; }
        .nav-links a:hover { color: #ffffff; border-bottom: 1px solid #fff; }

        .cart-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            width: 90%;
            flex: 1;
        }

        .cart-title { text-align: center; color: #d4af37; font-size: 32px; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }

        .cart-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            transition: 0.3s;
        }
        
        .cart-item:hover { border-color: #2e7d6f; background: rgba(255,255,255,0.08); }

        .item-img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; margin-right: 20px; }
        
        .item-details { flex: 1; }
        .item-name { font-size: 18px; color: #fff; font-weight: bold; margin-bottom: 5px; }
        .item-price { color: #d4af37; font-size: 16px; }
        .item-qty { color: #8faaa5; font-size: 14px; margin-top: 5px; }

        .remove-btn {
            color: #ff6b6b;
            text-decoration: none;
            font-size: 14px;
            border: 1px solid rgba(255, 107, 107, 0.3);
            padding: 8px 15px;
            border-radius: 20px;
            transition: 0.3s;
        }
        .remove-btn:hover { background: #ff6b6b; color: #fff; }

        .cart-summary {
            background: #163f3f;
            padding: 30px;
            border-radius: 20px;
            margin-top: 30px;
            text-align: right;
            border: 1px solid #2e7d6f;
        }

        .total-price { font-size: 28px; color: #fff; font-weight: bold; }
        .checkout-btn {
            display: inline-block;
            background: #d4af37;
            color: #000;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            font-size: 18px;
            transition: 0.3s;
        }
        .checkout-btn:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4); }

        .empty-cart { text-align: center; color: #8faaa5; padding: 50px; font-size: 18px; }

        footer { background: #081a1a; padding: 40px 20px; border-top: 1px solid #1f4f4f; text-align: center; color: #6c8c8c; margin-top: auto; }
        footer p { margin: 5px 0; font-size: 14px; letter-spacing: 1px; }
        footer .fade-text { font-size: 12px; opacity: 0.5; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px; }
    </style>
</head>
<body>

    <header>
        <div class="brand">🌿 Nordic Taste</div>
        <div class="nav-links">
            <a href="product.php">Back to Menu</a>
            <a href="#" style="color: #fff; border-bottom: 1px solid #fff;">Cart</a>
        </div>
    </header>

    <div class="cart-container">
        <h1 class="cart-title">Your Selection</h1>

        <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="../image/<?= $item['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/100'" class="item-img">
                    <div class="item-details">
                        <div class="item-name"><?= $item['Name'] ?></div>
                        <div class="item-price">RM <?= number_format($item['Price'], 2) ?></div>
                        <div class="item-qty">Qty: <?= $item['Quantity'] ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 18px; color: #fff; font-weight: bold; margin-bottom: 10px;">
                            RM <?= number_format($item['Price'] * $item['Quantity'], 2) ?>
                        </div>
                        <a href="cart.php?action=delete&id=<?= $item['CartItemID'] ?>" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <div style="color: #8faaa5; margin-bottom: 10px;">Total Amount</div>
                <div class="total-price">RM <?= number_format($totalPrice, 2) ?></div>
                <a href="#" class="checkout-btn">Proceed to Checkout</a>
            </div>

        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is currently empty 🍃</p>
                <a href="product.php" class="add-btn" style="border:1px solid #2e7d6f; padding:10px 20px; border-radius:20px; color:#2e7d6f; text-decoration:none; margin-top:10px; display:inline-block;">Start Ordering</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Nordic Taste. All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>

</body>
</html>