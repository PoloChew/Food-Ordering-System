<?php
// Order/clear_session.php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$sessionID = session_id();

try {
    // 1. 删除数据库里的购物车数据
    // 因为 CartItems 是级联删除 (ON DELETE CASCADE)，所以删了 Cart，Items 也会自动删掉
    $stmt = $pdo->prepare("DELETE FROM Cart WHERE SessionID = ?");
    $stmt->execute([$sessionID]);

} catch (Exception $e) {
    // 即使出错也不管，继续往下执行
}

// 2. 清除选座的 Cookie
// 把过期时间设为过去的时间，浏览器就会自动删除
setcookie("user_seat", "", time() - 3600, "/");
setcookie("popup_shown", "", time() - 3600, "/");

// 3. 跳转回首页
header("Location: ../index.php");
exit;
?>