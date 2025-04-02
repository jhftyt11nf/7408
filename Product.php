<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';
$user_id = $_SESSION['user_id'];

// Lấy danh sách sản phẩm từ database
$stmt = $conn->prepare("SELECT id, name, image FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #ccc;
        }

        h1 {
            color: #333;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            width: 250px;
            background: white;
            text-align: center;
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .add-to-cart {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%;
        }

        .add-to-cart:hover {
            background: #218838;
        }

        .back-home {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-home:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chào mừng Admin</h1>
        <p>Bạn có quyền quản lý sản phẩm và người dùng.</p>
        
        <div class="nav">
            <a href="index.php">🏠 Trang chủ</a>
            <a href="list_product.php">📦 Quản lý sản phẩm</a>
            <a href="User.php">👥 Quản lý người dùng</a>
            <a href="logout.php" class="logout">🚪 Đăng xuất</a>
        </div>
    </div>
</body>
</html>