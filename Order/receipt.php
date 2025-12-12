<?php
require '../DB.php';

if (!isset($_GET['order_id'])) {
    die("Order ID not specified.");
}

$orderID = intval($_GET['order_id']);

try {
    // 1. Fetch Order Details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE OrderID = ?");
    $stmt->execute([$orderID]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Order not found.");
    }

    // 2. Fetch Order Items
    $stmtItems = $pdo->prepare("
        SELECT oi.*, p.Name, p.Price 
        FROM orderitems oi 
        JOIN product p ON oi.ProductID = p.ProductID 
        WHERE oi.OrderID = ?
    ");
    $stmtItems->execute([$orderID]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// 3. Determine Status Text & Color
$paymentMethod = $order['PaymentMethod'];
$isPaid = ($paymentMethod !== 'Cash');

if ($isPaid) {
    $statusText = "PAYMENT DONE";
    $statusColor = "#2e7d6f"; // Green
    $badgeText = "PAID";
} else {
    $statusText = "HAVEN'T PAID";
    $statusColor = "#cc0000"; // Red
    $badgeText = "UNPAID";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?= $orderID ?> - TAR UMT Cafe</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #f4f4f4; padding: 40px; }
        .receipt-container { 
            max-width: 400px; margin: 0 auto; background: #fff; padding: 20px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid <?= $statusColor ?>;
        }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
        .logo { width: 60px; margin-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 5px 0; }
        .info { font-size: 14px; color: #555; margin-bottom: 5px; display: flex; justify-content: space-between;}
        
        .status-box { 
            text-align: center; border: 2px solid <?= $statusColor ?>; color: <?= $statusColor ?>; 
            padding: 5px; font-weight: bold; margin: 15px 0; font-size: 18px; text-transform: uppercase;
        }

        .items-table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 14px; }
        .items-table th { text-align: left; border-bottom: 1px solid #000; padding: 5px 0; }
        .items-table td { padding: 5px 0; }
        .qty { width: 30px; }
        .price { text-align: right; }

        .totals { border-top: 1px dashed #000; padding-top: 10px; margin-top: 10px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 14px; }
        .grand-total { font-weight: bold; font-size: 18px; margin-top: 5px; }

        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
        
        .btn-print { 
            display: block; width: 100%; padding: 10px; background: #2e7d6f; color: #fff; 
            text-align: center; text-decoration: none; border-radius: 5px; margin-top: 20px; 
            font-family: Arial, sans-serif; font-weight: bold; cursor: pointer; border: none;
        }
        .btn-print:hover { background: #1e5c50; }

        @media print {
            .btn-print { display: none; }
            body { background: #fff; padding: 0; }
            .receipt-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        <div class="header">
            <img src="../image/logo.png" alt="Logo" class="logo">
            <div class="title">TAR UMT Cafe</div>
            <div style="font-size: 12px;">Jalan Genting Kelang, Setapak</div>
        </div>

        <div class="info"><span>Order ID:</span> <strong>#<?= $orderID ?></strong></div>
        <div class="info"><span>Date:</span> <span><?= date('Y-m-d H:i', strtotime($order['OrderDate'])) ?></span></div>
        <div class="info"><span>Customer:</span> <span><?= htmlspecialchars($order['CustomerName']) ?></span></div>
        <div class="info"><span>Method:</span> <span><?= htmlspecialchars($order['PaymentMethod']) ?></span></div>
        <?php if($order['TableNumber'] !== 'Delivery'): ?>
        <div class="info"><span>Table:</span> <span><?= htmlspecialchars($order['TableNumber']) ?></span></div>
        <?php else: ?>
        <div class="info"><span>Type:</span> <span>Delivery</span></div>
        <?php endif; ?>

        <div class="status-box">
            <?= $statusText ?>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="qty">Qty</th>
                    <th>Item</th>
                    <th class="price">Amt (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                foreach ($items as $item): 
                    $subtotal += $item['Subtotal'];
                ?>
                <tr>
                    <td class="qty"><?= $item['Quantity'] ?></td>
                    <td><?= htmlspecialchars($item['Name']) ?></td>
                    <td class="price"><?= number_format($item['Subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php 
            $tax = $subtotal * 0.06;
            $total = $subtotal + $tax;
        ?>

        <div class="totals">
            <div class="row">
                <span>Subtotal</span>
                <span><?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="row">
                <span>Service Tax (6%)</span>
                <span><?= number_format($tax, 2) ?></span>
            </div>
            <div class="row grand-total">
                <span>Total</span>
                <span>RM <?= number_format($order['TotalAmount'], 2) ?></span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your support!</p>
            <p>System Generated Receipt</p>
        </div>

        <button onclick="window.print()" class="btn-print">🖨️ Print / Download PDF</button>
    </div>

</body>
</html>