<!-- <?php 
 $severname = "localhost";
 $username = "root";
 $password = "";
 $dbname = "asm";
 // tạo kêt nối 
 $conn = new mysqli($severname, $username, $password, $dbname);
 // kiểm tra kết nối 
 if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
 }
 ?> -->

<?php
// db_config.php
function getConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "asm"; // Standardize on one database name
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>