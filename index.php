<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hệ thống</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
        .nav {
            background: #333;
            padding: 15px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 10px;
            font-size: 18px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            background-color: red;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <div class="nav">
        <a href="home_page.php">🏠 Trang Chủ</a>
        <a href="Product.php">📦 Quản lý Sản Phẩm</a>
        <a href="User.php">👤 Quản lý Người Dùng</a>
        <a href="logout.php" class="logout-btn">logout</a>
    </div>

    <div class="container">
        <h1>Chào mừng, <?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "Người dùng"; ?>!</h1>
        <button onclick="window.location.href='Product.php'">Quản lý Sản Phẩm</button>
        <button onclick="window.location.href='User.php'">Quản lý Người Dùng</button>
    </div>

</body>
</html>
