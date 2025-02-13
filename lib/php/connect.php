<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "web_tam_roi_review";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
?>
