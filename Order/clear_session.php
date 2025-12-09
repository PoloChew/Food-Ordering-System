<?php
require '../DB.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$sessionID = session_id();

try {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE SessionID = ?");
    $stmt->execute([$sessionID]);

} catch (Exception $e) {
}

setcookie("user_seat", "", time() - 3600, "/");
setcookie("popup_shown", "", time() - 3600, "/");

header("Location: ../index.php");
exit;
?>