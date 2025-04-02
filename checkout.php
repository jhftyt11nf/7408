<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy giỏ hàng của user
$stmt = $conn->prepare("
    SELECT ci.product_id, p.name, p.price, ci.quantity 
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    JOIN carts c ON ci.cart_id = c.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    echo "<p>Giỏ hàng của bạn trống!</p>";
    exit();
}

// Xử lý đặt hàng khi nhấn nút "Đặt hàng"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_price = array_reduce($cart_items, function ($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0);

    // Tạo đơn hàng trong bảng `orders`
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $total_price]);

    // Lấy ID đơn hàng vừa tạo
    $order_id = $conn->lastInsertId();

    // Lưu sản phẩm vào `order_items`
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Xóa giỏ hàng sau khi đặt hàng thành công
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = ?)");
    $stmt->execute([$user_id]);

    echo "<p>Đặt hàng thành công! Mã đơn hàng: $order_id</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Xác nhận đơn hàng</h2>

<table border="1">
    <tr>
        <th>Sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    <?php 
    $total_price = 0;
    foreach ($cart_items as $item): 
        $subtotal = $item['price'] * $item['quantity'];
        $total_price += $subtotal;
    ?>
    <tr>
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td><?php echo number_format($item['price'], 2); ?> VND</td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo number_format($subtotal, 2); ?> VND</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Tổng cộng:</strong></td>
        <td><strong><?php echo number_format($total_price, 2); ?> VND</strong></td>
    </tr>
</table>

<form method="POST">
    <button type="submit">Đặt hàng</button>
</form>

</body>
</html>
