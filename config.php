<?php
// Blog/config.php

// Thông tin kết nối MySQL/MariaDB
// Sẽ cần được cập nhật khi triển khai lên môi trường server
define('DB_HOST', 'dbmysql.chma88iq6hht.ap-southeast-1.rds.amazonaws.com'); // Host của database (ví dụ: 'localhost' nếu MariaDB chạy cùng server)
define('DB_USER', 'admin'); // Tên người dùng database của bạn
define('DB_PASS', '12345678'); // Mật khẩu database của bạn
define('DB_NAME', 'mydb'); // Tên database của blog bạn đã tạo

// Kết nối database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Thiết lập charset để hỗ trợ tiếng Việt và các ký tự đặc biệt
$conn->set_charset("utf8mb4");

?>

