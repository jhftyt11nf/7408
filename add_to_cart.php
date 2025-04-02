<?php
session_start();
$conn = new mysqli("localhost", "root", "", "se7101");

if (!$conn) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
    exit;
}

$user_id = $_SESSION["user_id"];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'] ?? 1;  // Nếu không có số lượng, mặc định là 1

// Kiểm tra xem người dùng đã có giỏ hàng chưa
$sql_cart = "SELECT id FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql_cart);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    // Nếu người dùng chưa có giỏ hàng, tạo giỏ hàng mới
    $stmt = $conn->prepare("INSERT INTO cart (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_id = $stmt->insert_id; // Lấy ID giỏ hàng vừa tạo
} else {
    $stmt->bind_result($cart_id);
    $stmt->fetch();
}

// Kiểm tra sản phẩm đã có trong giỏ hàng chưa
$sql_cart_item = "SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql_cart_item);
$stmt->bind_param("ii", $cart_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
    $stmt->bind_result($cart_item_id, $existing_quantity);
    $stmt->fetch();
    $new_quantity = $existing_quantity + $quantity;

    $update_stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $update_stmt->bind_param("ii", $new_quantity, $cart_item_id);
    $update_stmt->execute();
    echo json_encode(["status" => "success", "message" => "Giỏ hàng đã được cập nhật!"]);
} else {
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
    $stmt->execute();
    echo json_encode(["status" => "success", "message" => "Sản phẩm đã được thêm vào giỏ hàng!"]);
}

$stmt->close();
$conn->close();
?>
