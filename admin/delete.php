<?php
// Blog/admin/delete.php

require_once '../config.php';

// Kiểm tra xem có ID bài viết được truyền qua URL và là số hợp lệ không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Chuẩn bị và thực thi câu lệnh DELETE an toàn
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" nghĩa là tham số là số nguyên

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý với thông báo thành công
        header("Location: index.php?message=" . urlencode("Bài viết đã được xóa thành công!"));
        exit(); // Dừng thực thi script sau khi chuyển hướng
    } else {
        // Chuyển hướng về trang quản lý với thông báo lỗi
        header("Location: index.php?error=" . urlencode("Lỗi khi xóa bài viết: " . $stmt->error));
        exit();
    }
    $stmt->close(); // Đóng statement
} else {
    // Nếu ID không hợp lệ hoặc không có ID được truyền, chuyển hướng về trang quản lý
    header("Location: index.php?error=" . urlencode("ID bài viết không hợp lệ để xóa."));
    exit();
}

$conn->close(); // Đóng kết nối database (mặc dù đã exit, nhưng vẫn nên có)
?>
