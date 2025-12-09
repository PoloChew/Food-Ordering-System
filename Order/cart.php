<?php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$sessionID = session_id();

// Get Seat and Pax
$userSeat = isset($_COOKIE['user_seat']) ? $_COOKIE['user_seat'] : 'Not Selected';
$userPax = isset($_COOKIE['user_pax']) ? $_COOKIE['user_pax'] : '1';

// --- 🌟 FIXED: Safe Delete Action ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cartItemID = intval($_GET['id']);

    // 1. Double check this item actually belongs to the current user's cart (Security)
    // This prevents accidental deletion if IDs get mixed up
    $checkStmt = $pdo->prepare("
        SELECT ci.CartItemID 
        FROM CartItems ci
        JOIN Cart c ON ci.CartID = c.CartID
        WHERE ci.CartItemID = ? AND c.SessionID = ?
    ");
    $checkStmt->execute([$cartItemID, $sessionID]);

    if ($checkStmt->fetch()) {
        // 2. Delete STRICTLY by CartItemID and LIMIT 1 for safety
        $delStmt = $pdo->prepare("DELETE FROM CartItems WHERE CartItemID = ? LIMIT 1");
        $delStmt->execute([$cartItemID]);
    }

    header("Location: cart.php");
    exit;
}

// --- Handle Quantity Update ---
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && isset($_GET['qty'])) {
    $cartItemID = intval($_GET['id']);
    $newQty = intval($_GET['qty']);
    
    // Security check before update
    $checkStmt = $pdo->prepare("
        SELECT ci.CartItemID 
        FROM CartItems ci
        JOIN Cart c ON ci.CartID = c.CartID
        WHERE ci.CartItemID = ? AND c.SessionID = ?
    ");
    $checkStmt->execute([$cartItemID, $sessionID]);

    if ($checkStmt->fetch()) {
        if ($newQty > 0) {
            $updStmt = $pdo->prepare("UPDATE CartItems SET Quantity = ? WHERE CartItemID = ?");
            $updStmt->execute([$newQty, $cartItemID]);
        } else {
            // Safe Delete if qty is 0
            $delStmt = $pdo->prepare("DELETE FROM CartItems WHERE CartItemID = ? LIMIT 1");
            $delStmt->execute([$cartItemID]);
        }
    }
    header("Location: cart.php");
    exit;
}

$cartItems = [];
$subtotal = 0;
$totalQuantity = 0;

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
    $subtotal += $item['Price'] * $item['Quantity'];
    $totalQuantity += $item['Quantity'];
}

$tax = $subtotal * 0.06;
$grandTotal = $subtotal + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - TAr UMT Cafe</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/751/751621.png">
    <link rel="stylesheet" href="../css/cart.css">
    
    <style>
        /* Ensure badge and nav styling matches other pages */
        .cart-badge {
            position: absolute; top: -8px; right: -12px;
            background-color: #ff4444; color: white; font-size: 10px; font-weight: bold;
            padding: 2px 6px; border-radius: 50%; border: 1px solid #0b2222;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .nav-links a { position: relative; display: flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>

    <header>
        <div class="brand">
            <img src="../image/logo.png" alt="TAR UMT" class="site-logo">
            TAR UMT Cafe
        </div>
        <div class="nav-links">
            <a href="product.php">⬅ Back to Menu</a>
            <a href="#" style="color: #fff; border-bottom: 1px solid #fff;">
                🛒 Cart
                <?php if ($totalQuantity > 0): ?>
                    <span class="cart-badge"><?= $totalQuantity ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <div class="cart-container">
        <h1 class="cart-title">Your Selection</h1>

        <div class="cart-info-bar">
            <div class="info-item">
                <span>📦 Total Items:</span>
                <strong><?= $totalQuantity ?></strong>
            </div>
            
            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 20px;"></div>
            
            <div class="info-item">
                <span>🪑 Seat:</span>
                <strong style="color: #d4af37;"><?= htmlspecialchars($userSeat) ?></strong>
            </div>

            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 20px;"></div>

            <div class="info-item">
                <span>👥 Pax:</span>
                <strong style="color: #d4af37;"><?= htmlspecialchars($userPax) ?></strong>
            </div>
        </div>

        <input type="hidden" id="pax_val" value="<?= htmlspecialchars($userPax) ?>">

        <?php if (count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="../image/<?= $item['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/100'" class="item-img">
                    <div class="item-details">
                        <div class="item-name"><?= $item['Name'] ?></div>
                        <div class="item-price">RM <?= number_format($item['Price'], 2) ?></div>
                        
                        <div class="qty-control">
                            <a href="cart.php?action=update&id=<?= $item['CartItemID'] ?>&qty=<?= $item['Quantity'] - 1 ?>" class="qty-btn">-</a>
                            <span class="item-qty-text"><?= $item['Quantity'] ?></span>
                            <a href="cart.php?action=update&id=<?= $item['CartItemID'] ?>&qty=<?= $item['Quantity'] + 1 ?>" class="qty-btn">+</a>
                        </div>
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
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>RM <?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Service Tax (6%)</span>
                    <span>RM <?= number_format($tax, 2) ?></span>
                </div>
                <div class="divider"></div>
                <div class="summary-row">
                    <span style="color: #fff; font-weight: bold;">Total</span>
                    <span class="total-price" style="font-size: 24px;">RM <?= number_format($grandTotal, 2) ?></span>
                </div>
                <button onclick="openCheckoutModal()" class="checkout-btn">Proceed to Checkout</button>
            </div>

        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is currently empty 🍃</p>
                <a href="product.php" class="add-btn" style="border:1px solid #2e7d6f; padding:10px 20px; border-radius:20px; color:#2e7d6f; text-decoration:none; margin-top:10px; display:inline-block;">Start Ordering</a>
            </div>
        <?php endif; ?>
    </div>

    <div id="modal-checkout" class="modal-overlay">
        <div class="modal-box">
            <button class="close-modal" onclick="closeModal('modal-checkout')">×</button>
            <div class="modal-title">Checkout & Pay</div>
            
            <p style="text-align: left; color: #d4af37; font-size: 14px; margin-bottom: 5px;">Order Summary</p>
            
            <div class="modal-summary-list">
                <?php foreach ($cartItems as $item): ?>
                    <div class="modal-item">
                        <img src="../image/<?= $item['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/50'" class="modal-img">
                        <div class="modal-info">
                            <div><?= $item['Name'] ?></div>
                            <small>Qty: <?= $item['Quantity'] ?></small>
                        </div>
                        <div class="modal-price">RM <?= number_format($item['Price'] * $item['Quantity'], 2) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="price-breakdown">
                <div class="breakdown-row">
                    <span>Subtotal</span>
                    <span>RM <?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="breakdown-row">
                    <span>Service Tax (6%)</span>
                    <span>RM <?= number_format($tax, 2) ?></span>
                </div>
                <div class="breakdown-total">
                    <span>Total Amount</span>
                    <span style="color: #d4af37;">RM <?= number_format($grandTotal, 2) ?></span>
                </div>
            </div>
            
            <div class="form-group">
                <label>Number of Pax</label>
                <input type="text" class="form-input" value="<?= htmlspecialchars($userPax) ?>" readonly style="background: rgba(255,255,255,0.1); color: #8faaa5; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" id="cust_name" class="form-input" placeholder="Enter your name">
            </div>

            <div class="form-group">
                <label>Table Number</label>
                <input type="text" id="table_num" class="form-input" value="<?= htmlspecialchars($userSeat) ?>" readonly style="background: rgba(255,255,255,0.1); color: #8faaa5; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select id="payment_method" class="form-input" onchange="togglePaymentFields()">
                    <option value="">-- Select Method --</option>
                    <option value="Cash">💵 Cash</option>
                    <option value="E-wallet">📱 TNG E-Wallet</option>
                    <option value="Online Banking">🏦 Online Banking</option>
                </select>
            </div>

            <div id="ewallet-section" class="payment-section hidden">
                <div style="text-align: center;">
                    <img src="../image/tng.jpg" alt="TNG" class="ewallet-logo">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <div style="display: flex; gap: 10px;">
                        <select id="country_code" class="form-input" style="width: 80px; padding: 5px; text-align: center;">
                            <option value="+60">🇲🇾 +60 (MY)</option>
                            <option value="+1">🇺🇸 +1 (US)</option>
                            <option value="+81">🇯🇵 +81 (JP)</option>
                            <option value="+82">🇰🇷 +82 (KR)</option>
                        </select>
                        <input type="text" id="ewallet_phone" class="form-input" placeholder="Enter Phone No.">
                    </div>
                </div>
                <div class="form-group">
                    <label>6-Digit PIN</label>
                    <input type="password" id="ewallet_pin" class="form-input" maxlength="6" placeholder="******">
                </div>
            </div>

            <div id="bank-section" class="payment-section hidden">
                <div class="form-group">
                    <label>Select Bank</label>
                    <input type="hidden" id="bank_name">
                    
                    <div class="bank-grid">
                        <div class="bank-option" onclick="selectBank(this, 'Maybank2u')">
                            <img src="../image/maybank.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>Maybank</span>
                        </div>
                        <div class="bank-option" onclick="selectBank(this, 'CIMB Clicks')">
                            <img src="../image/cimb.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>CIMB</span>
                        </div>
                        <div class="bank-option" onclick="selectBank(this, 'Public Bank')">
                            <img src="../image/publicbank.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>Public</span>
                        </div>
                        <div class="bank-option" onclick="selectBank(this, 'RHB Now')">
                            <img src="../image/rhb.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>RHB</span>
                        </div>
                        <div class="bank-option" onclick="selectBank(this, 'Hong Leong')">
                            <img src="../image/hongleong.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>HongLeong</span>
                        </div>
                        <div class="bank-option" onclick="selectBank(this, 'AmBank')">
                            <img src="../image/ambank.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span>AmBank</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" id="bank_acc" class="form-input" placeholder="Enter Account No.">
                </div>
                <div class="form-group">
                    <label>Bank PIN</label>
                    <input type="password" id="bank_pin" class="form-input" placeholder="Enter PIN">
                </div>
            </div>

            <div class="form-group verify-box" style="margin-top: 20px;">
                <label style="color: #d4af37;">Human Verify: 12 + 8 = ?</label>
                <input type="number" id="human_ans" class="form-input" style="border: 1px solid #d4af37;" placeholder="Enter Answer">
            </div>

            <button onclick="submitOrder()" class="checkout-btn" style="width: 100%; margin-top:20px;">Confirm & Pay</button>
        </div>
    </div>
    
    <div id="modal-success" class="modal-overlay">
        <div class="modal-box">
            <div id="success-icon" style="font-size: 60px; margin-bottom: 15px;">🎉</div>
            <div id="success-title" class="modal-title" style="color: #fff;">Order Placed!</div>
            
            <div id="success-message-container"></div>

            <div style="margin-top: 30px;">
                <a href="clear_session.php" class="checkout-btn" style="background: #2e7d6f; color: #fff; text-decoration: none; display: block;">Back to Home</a>
            </div>
        </div>
    </div>

    <footer><p>&copy; <?php echo date('Y'); ?> TAR UMT Cafe. All Rights Reserved.</p><p class="fade-text">Beyond Education</p></footer>
    <script src="../JS/cart.js"></script>
</body>
</html>