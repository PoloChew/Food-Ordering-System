<?php
require '../DB.php';
//
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$sessionID = session_id();
$cartTotalQty = 0;
try {
    $qtyStmt = $pdo->prepare("SELECT SUM(Quantity) FROM CartItems ci JOIN Cart c ON ci.CartID = c.CartID WHERE c.SessionID = ?");
    $qtyStmt->execute([$sessionID]);
    $cartTotalQty = $qtyStmt->fetchColumn();
    if (!$cartTotalQty) $cartTotalQty = 0;
} catch (Exception $e) { $cartTotalQty = 0; }

$currentSeat = isset($_COOKIE['user_seat']) ? $_COOKIE['user_seat'] : '';
$currentPax = isset($_COOKIE['user_pax']) ? $_COOKIE['user_pax'] : '';

$type = isset($_GET['type']) ? $_GET['type'] : "unknown";
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : "";
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : "";
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : "";
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereSQL = "WHERE 1=1";
$params = [];
if (!empty($search_name)) { $whereSQL .= " AND Name LIKE :name"; $params[':name'] = "%$search_name%"; }
if (!empty($category_id)) { $whereSQL .= " AND CategoryID = :cid"; $params[':cid'] = $category_id; }
if (!empty($min_price)) { $whereSQL .= " AND Price >= :min"; $params[':min'] = $min_price; }
if (!empty($max_price)) { $whereSQL .= " AND Price <= :max"; $params[':max'] = $max_price; }

$countSql = "SELECT COUNT(*) FROM Product $whereSQL";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

$sql = "SELECT * FROM Product $whereSQL LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) { $stmt->bindValue($key, $val); }
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtHigh = $pdo->query("SELECT * FROM Product ORDER BY Price DESC LIMIT 1");
$mostExpensive = $stmtHigh->fetch(PDO::FETCH_ASSOC);
$stmtSig = $pdo->query("SELECT * FROM Product ORDER BY RAND() LIMIT 1");
$signature = $stmtSig->fetch(PDO::FETCH_ASSOC);
$stmtCat = $pdo->query("SELECT * FROM Category");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
function buildUrl($newPage) { $params = $_GET; $params['page'] = $newPage; return '?' . http_build_query($params); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Yume (梦 - ゆめ)</title>
    <link rel="shortcut icon" href="/image/logo.png">
    <link rel="stylesheet" href="../css/product.css"> 
</head>
<body>

    <header>
        <div class="brand">Yume (梦 - ゆめ)</div>
        <div class="nav-links">
            <a href="clear_session.php" onclick="return confirm('Going home will clear your cart and seat selection. Continue?');">🏠 Home</a>
            
            <a href="cart.php" style="margin-right: 15px;">
                🛒 Cart
                <?php if ($cartTotalQty > 0): ?>
                    <span class="cart-badge"><?= $cartTotalQty ?></span>
                <?php endif; ?>
            </a>
            
            <span id="header-seat-display" style="color: #FFD700; font-weight: bold; font-size: 14px;">
                <?= !empty($currentSeat) ? "🪑 $currentSeat" . (!empty($currentPax) ? " 👥$currentPax" : "") : "" ?>
            </span>
        </div>
    </header>

    <div id="modal-dinein" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title">Select Your Seat 🪑</div>
            <p>Please choose a table and number of people.</p>
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="color: #aebcb9; font-size: 14px;">Number of People (Pax):</label>
                <select id="pax-select" class="pax-select">
                    <?php for($i=1; $i<=10; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Pax</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="bus-layout">
                <div class="seat" onclick="selectSeat('A1', this)">A1</div> <div class="seat" onclick="selectSeat('A2', this)">A2</div> <div class="aisle"></div>
                <div class="seat" onclick="selectSeat('B1', this)">B1</div> <div class="seat" onclick="selectSeat('B2', this)">B2</div>
                <div class="seat" onclick="selectSeat('A3', this)">A3</div> <div class="seat" onclick="selectSeat('A4', this)">A4</div> <div class="aisle"></div>
                <div class="seat" onclick="selectSeat('B3', this)">B3</div> <div class="seat" onclick="selectSeat('B4', this)">B4</div>
            </div>
            <div id="selected-seat-msg" style="color: #d4af37; height: 20px; margin-bottom: 10px;"></div>
            <button class="close-modal" onclick="closeModal('modal-dinein')">✅ Confirm Seat</button>
        </div>
    </div>

    <div id="modal-takeaway" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title">Takeaway Counter 🛍️</div>
            <p>Please proceed to <strong>Counter 2</strong> to collect your order.</p>
            <p style="color: #d4af37; font-size: 24px; font-weight: bold; margin-top: 10px;">Waiting Time: ~15 mins</p>
            <button class="close-modal" onclick="closeModal('modal-takeaway')">🚀 Start Ordering</button>
        </div>
    </div>

    <div id="modal-product-detail" class="modal-overlay">
        <div class="modal-box product-detail-box">
            <div class="detail-img-container"><img id="detail-img" src="" alt="Detail"></div>
            <div class="detail-info-container">
                <h2 id="detail-name" style="color: #d4af37; font-size: 32px; margin-top: 0;"></h2>
                <div style="color: #FFD700; font-size: 18px; margin-bottom: 15px;">★★★★★</div>
                <p id="detail-desc" style="color: #aebcb9; font-size: 16px; line-height: 1.6; min-height: 60px;"></p>
                <p id="detail-price" style="color: #fff; font-size: 36px; font-weight: bold; margin: 20px 0;"></p>
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <input type="number" id="detail-qty" class="qty-input" value="1" min="1">
                    <input type="hidden" id="detail-id"> 
                    <button onclick="addToCart()" class="add-btn" style="padding: 12px 30px; cursor: pointer;">🛒 Add to Cart</button>
                </div>
                <div style="margin-top: 10px;">
                    <span onclick="closeModal('modal-product-detail')" style="color: #6c8c8c; cursor: pointer; text-decoration: underline; font-size: 14px;">✖ Close Window</span>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-success" class="modal-overlay">
        <div class="modal-box">
            <div style="font-size: 50px; margin-bottom: 10px;">✅</div>
            <div class="modal-title" style="color: #fff;">Added to Cart!</div>
            <p>Would you like to continue ordering?</p>
            <div style="margin-top: 30px;">
                <button class="continue-btn" onclick="closeModal('modal-success')">⬅ Continue</button>
                <a href="cart.php" class="go-cart-btn">Go to Cart 🛒</a>
            </div>
        </div>
    </div>

    <div class="hero-section">
        <?php if ($mostExpensive): ?>
        <div class="hero-card">
            <img src="../image/<?= $mostExpensive['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/150'" alt="Expensive">
            <div class="hero-info">
                <h3>👑 Most Luxurious</h3>
                <h2><?= $mostExpensive['Name'] ?></h2>
                <div class="price">RM <?= number_format($mostExpensive['Price'], 2) ?></div>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($signature): ?>
        <div class="hero-card" style="border-color: #2e7d6f;">
            <img src="../image/<?= $signature['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/150'" alt="Signature" style="border-color: #2e7d6f;">
            <div class="hero-info">
                <h3 style="color: #2e7d6f;">👨‍🍳 Chef's Choice</h3>
                <h2><?= $signature['Name'] ?></h2>
                <div class="price">RM <?= number_format($signature['Price'], 2) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <form class="filter-bar" method="GET" action="product.php">
        <input type="hidden" name="type" value="<?= $type ?>">
        <select name="category_id" class="filter-select">
            <option value="">🍽️ All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['CategoryID'] ?>" <?= ($category_id == $cat['CategoryID']) ? 'selected' : '' ?>><?= $cat['CategoryName'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="min_price" class="filter-input" placeholder="Min $" style="width: 80px;" value="<?= $min_price ?>">
        <input type="number" name="max_price" class="filter-input" placeholder="Max $" style="width: 80px;" value="<?= $max_price ?>">
        <input type="text" name="search_name" class="filter-input" placeholder="🔍 Search Food..." value="<?= $search_name ?>">
        <button type="submit" class="filter-btn">🔎 Filter</button>
    </form>

    <div class="product-container">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $row): ?>
                <div class="product-card" onclick="openProductDetail(this)" data-id="<?= $row['ProductID'] ?>" data-name="<?= htmlspecialchars($row['Name']) ?>" data-price="<?= number_format($row['Price'], 2) ?>" data-desc="<?= htmlspecialchars($row['Description']) ?>" data-img="../image/<?= $row['ImageURL'] ?>">
                    <img src="../image/<?= $row['ImageURL'] ?>" onerror="this.src='https://via.placeholder.com/300x200'" alt="<?= $row['Name'] ?>">
                    <div class="product-info">
                        <div class="product-name"><?= $row['Name'] ?></div>
                        <div class="product-rating">★★★★½</div>
                        <div class="product-desc"><?= $row['Description'] ?></div>
                        <div class="product-price">RM <?= number_format($row['Price'], 2) ?></div>
                        <span class="add-btn">📄 View Details</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; width: 100%;">No products found.</p>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?> <a href="<?= buildUrl($page - 1) ?>" class="page-link">⬅ Prev</a> <?php endif; ?>
        <span class="page-info">Page <?= $page ?> of <?= $totalPages ?></span>
        <?php if ($page < $totalPages): ?> <a href="<?= buildUrl($page + 1) ?>" class="page-link">Next ➡</a> <?php endif; ?>
    </div>
    <?php endif; ?>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Yume (梦 - ゆめ). All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>
    
    <script>
        var orderType = "<?= $type ?>";
        var selectedSeat = ""; 
        function setCookie(cname, cvalue, minutes) {
            const d = new Date(); d.setTime(d.getTime() + (minutes * 60 * 1000));
            document.cookie = cname + "=" + cvalue + ";expires="+ d.toUTCString() + ";path=/";
        }
        function getCookie(cname) {
            let name = cname + "="; let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i]; while (c.charAt(0) == ' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }
        window.onload = function() {
            if (getCookie("popup_shown") === "") {
                if (orderType === 'dinein') document.getElementById('modal-dinein').style.display = 'flex';
                else if (orderType === 'takeaway') document.getElementById('modal-takeaway').style.display = 'flex';
            }
        };
        function closeModal(modalId) {
            if(modalId === 'modal-dinein') {
                if (selectedSeat === "") { alert("Please select a seat to continue!"); return; }
                setCookie("popup_shown", "true", 5);
                var pax = document.getElementById('pax-select').value;
                setCookie("user_pax", pax, 120); 
                setCookie("user_seat", selectedSeat, 120);
                document.getElementById('header-seat-display').innerText = "🪑 " + selectedSeat + " 👥" + pax;
            } else if (modalId === 'modal-takeaway') {
                setCookie("popup_shown", "true", 5);
                setCookie("user_seat", "Takeaway", 120);
                setCookie("user_pax", "1", 120);
                document.getElementById('header-seat-display').innerText = "🛍️ Takeaway";
            }
            document.getElementById(modalId).style.display = 'none';
        }
        function selectSeat(seatNum, element) {
            selectedSeat = seatNum;
            document.getElementById('selected-seat-msg').innerText = "Selected Seat: " + seatNum;
            var allSeats = document.querySelectorAll('.seat');
            allSeats.forEach(function(s) { s.classList.remove('selected'); });
            element.classList.add('selected');
        }
        function openProductDetail(card) {
            var id = card.getAttribute('data-id');
            var name = card.getAttribute('data-name');
            var price = card.getAttribute('data-price');
            var desc = card.getAttribute('data-desc');
            var img = card.getAttribute('data-img');
            document.getElementById('detail-id').value = id; 
            document.getElementById('detail-name').innerText = name;
            document.getElementById('detail-price').innerText = 'RM ' + price;
            document.getElementById('detail-desc').innerText = desc;
            document.getElementById('detail-img').src = img;
            document.getElementById('detail-qty').value = 1; 
            document.getElementById('modal-product-detail').style.display = 'flex';
        }
        function addToCart() {
            var productId = document.getElementById('detail-id').value;
            var quantity = document.getElementById('detail-qty').value;
            var formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            fetch('add_to_cart.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    closeModal('modal-product-detail');
                    document.getElementById('modal-success').style.display = 'flex';
                    // Optional: Manually update cart badge here via JS if desired, or let reload handle it
                } else { alert('Error: ' + data.message); }
            })
            .catch(error => { console.error('Error:', error); alert('Something went wrong!'); });
        }
    </script>
</body>
</html>