<?php
require_once('connect.php');

$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['postId'];
$userId = 1; // ใช้ user_id ที่เข้าสู่ระบบอยู่

// บันทึกโพสต์ลงฐานข้อมูล
$sql = "INSERT INTO saved_posts (user_id, post_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $postId);
$stmt->execute();

echo json_encode(['success' => true]);
?>
