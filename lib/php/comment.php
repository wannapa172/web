<?php
require_once('connect.php');

$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['postId'];
$comment = $data['comment'];

// บันทึกความคิดเห็นลงฐานข้อมูล
$sql = "INSERT INTO comments (post_id, comment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $postId, $comment);
$stmt->execute();

echo json_encode(['success' => true]);
?>
