<?php
require_once('connect.php'); // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจาก AJAX
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

// ตรวจสอบอีเมลและรหัสผ่าน
$sql = "SELECT * FROM user_information WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // ตรวจสอบรหัสผ่าน
    if (password_verify($password, $user['password'])) {
        // หากเข้าสู่ระบบสำเร็จ
        echo json_encode(['success' => true, 'message' => 'เข้าสู่ระบบสำเร็จ', 'user' => $user]);
    } else {
        // รหัสผ่านไม่ถูกต้อง
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านไม่ถูกต้อง']);
    }
} else {
    // อีเมลไม่ถูกต้อง
    echo json_encode(['success' => false, 'message' => 'ไม่พบบัญชีผู้ใช้งาน']);
}

$stmt->close();
$conn->close();
?>
