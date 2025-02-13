<?php
require_once('connect.php'); // เชื่อมต่อฐานข้อมูล

$data = json_decode(file_get_contents("php://input"), true); // รับข้อมูล JSON
$postId = $data['postId']; // ID ของโพสต์
$userId = $data['userId']; // ID ของผู้ใช้
$isLiked = $data['isLiked']; // สถานะการกดไลก์ (true/false)

// ตรวจสอบว่าผู้ใช้เคยกดไลก์โพสต์นี้หรือยัง
$sql_check = "SELECT * FROM `like` WHERE Post_ID = ? AND User_ID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $postId, $userId);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($isLiked) {
    // ถ้าเป็นการกดไลก์และยังไม่มีในตาราง `like`
    if ($result->num_rows === 0) {
        $sql_insert = "INSERT INTO `like` (Post_ID, User_ID, Timestamp) VALUES (?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $postId, $userId);
        $stmt_insert->execute();
        echo json_encode(['success' => true, 'message' => 'Liked']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Already liked']);
    }
} else {
    // ถ้าเป็นการยกเลิกไลก์
    if ($result->num_rows > 0) {
        $sql_delete = "DELETE FROM `like` WHERE Post_ID = ? AND User_ID = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $postId, $userId);
        $stmt_delete->execute();
        echo json_encode(['success' => true, 'message' => 'Unliked']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No like to remove']);
    }
}

$stmt_check->close();
$conn->close();
?>
