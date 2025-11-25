<?php
require '../DB.php';

// 获取用户选择的模式
$type = isset($_GET['type']) ? $_GET['type'] : "unknown";

// 查询所有 product
$stmt = $pdo->query("SELECT * FROM Product");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Page</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .product {
            width: 300px;
            background: white;
            padding: 15px;
            margin: 10px;
            float: left;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .name { font-size: 20px; font-weight: bold; margin-top: 10px; }
        .price { color: green; font-size: 18px; margin-top: 5px; }
    </style>
</head>

<body>

<h2>Order Type: <?= ucfirst($type) ?></h2>

<?php foreach ($products as $row): ?>
    <div class="product">
        <img src="../image/<?= $row['ImageURL'] ?>" alt="Product Image">
        <div class="name"><?= $row['Name'] ?></div>
        <div class="price">RM <?= number_format($row['Price'], 2) ?></div>
    </div>
<?php endforeach; ?>

</body>
</html>
