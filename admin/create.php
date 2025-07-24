<?php
session_start();
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
?>

<?php
// Blog/admin/create.php

require_once '../config.php';

$message = '';
$title = '';    // Để giữ lại giá trị đã nhập nếu có lỗi
$content = '';  // Để giữ lại giá trị đã nhập nếu có lỗi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($title) || empty($content)) {
        $message = '<p class="error">Tiêu đề và nội dung không được để trống!</p>';
    } else {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu (sử dụng prepared statement)
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content); // "ss" nghĩa là hai tham số đều là chuỗi (string)

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            $message = '<p class="success">Bài viết đã được đăng thành công!</p>';
            // Xóa nội dung form sau khi đăng thành công để chuẩn bị cho bài mới
            $title = '';
            $content = '';
        } else {
            $message = '<p class="error">Lỗi khi đăng bài: ' . $stmt->error . '</p>';
        }
        $stmt->close(); // Đóng statement
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tạo Bài viết Mới</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin</h1>
            <nav>
                <a href="../index.php">Xem Blog</a>
                <a href="index.php">Quản lý Bài viết</a>
                <a href="create.php">Tạo Bài viết Mới</a>
            </nav>
        </header>

        <main>
            <h2>Tạo Bài viết Mới</h2>
            <?php echo $message; // Hiển thị thông báo ?>

            <form action="create.php" method="POST">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" rows="15" required><?php echo htmlspecialchars($content); ?></textarea>

                <button type="submit">Đăng bài</button>
            </form>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Blog Đơn Giản</p>
        </footer>
    </div>
</body>
</html>
<?php
$conn->close();
?>
