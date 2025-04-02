<?php
session_start();
$conn = new mysqli("localhost", "root", "", "se7101");

if (!isset($_SESSION["user_id"])) {
    echo 0;
    exit;
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT SUM(cart_items.quantity) FROM cart_items 
        JOIN cart ON cart_items.cart_id = cart.id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_quantity);
$stmt->fetch();

echo $total_quantity ?? 0;

$stmt->close();
$conn->close();
?>
