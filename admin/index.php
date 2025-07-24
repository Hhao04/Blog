<?php
// Blog/admin/index.php

// Bao gồm file cấu hình database (lùi ra 1 cấp từ thư mục admin)
require_once '../config.php';

$message = ''; // Biến để hiển thị thông báo thành công/lỗi
if (isset($_GET['message'])) {
    $message = '<p class="success">' . htmlspecialchars($_GET['message']) . '</p>';
}
if (isset($_GET['error'])) {
    $message = '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
}

// Lấy tất cả bài viết từ database, sắp xếp theo ngày tạo mới nhất
$sql = "SELECT id, title, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Bài viết</title>
    <link rel="stylesheet" href="../style.css"> </head>
<body>
    <div class="container">
        <header>
            <h1>Admin Panel</h1>
            <nav>
                <a href="../index.php">Xem Blog</a>
                <a href="index.php">Quản lý Bài viết</a>
                <a href="create.php">Tạo Bài viết Mới</a>
            </nav>
        </header>

        <main>
            <h2>Danh sách Bài viết</h2>
            <?php echo $message; // Hiển thị thông báo ?>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Ngày đăng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Sửa</a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có bài viết nào trong hệ thống.</p>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Blog Đơn Giản</p>
        </footer>
    </div>
</body>
</html>
<?php
$conn->close(); // Đóng kết nối database
?>
