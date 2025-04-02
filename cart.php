<?php
session_start();
$conn = new mysqli("localhost", "root", "", "se7101");

if (!isset($_SESSION["user_id"])) {
    echo "<p>Bạn chưa đăng nhập!</p>";
    exit;
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT products.name, products.price, cart_items.quantity 
        FROM cart_items 
        JOIN carts ON cart_items.cart_id = carts.id 
        JOIN products ON cart_items.product_id = products.id
        WHERE carts.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>\${$row['price']}</td><td>{$row['quantity']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>Giỏ hàng trống!</p>";
}

$conn->close();
?>
