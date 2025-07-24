
<?php
session_start();
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
// Blog/admin/edit.php

require_once '../config.php';

$message = '';
$post = null; // Biến để lưu thông tin bài viết cần sửa

// Lấy ID bài viết từ URL khi truy cập lần đầu hoặc có lỗi
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin bài viết hiện có
    $stmt = $conn->prepare("SELECT id, title, content FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        // Nếu không tìm thấy bài viết, chuyển hướng về trang quản lý với thông báo lỗi
        header("Location: index.php?error=" . urlencode("Bài viết không tồn tại để sửa."));
        exit();
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý form khi người dùng submit để cập nhật bài viết
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (empty($title) || empty($content)) {
        $message = '<p class="error">Tiêu đề và nội dung không được để trống!</p>';
        // Nếu có lỗi, cần lấy lại thông tin bài viết để hiển thị trong form
        $stmt = $conn->prepare("SELECT id, title, content FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $post = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        // Cập nhật bài viết vào database
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id); // "ssi": string, string, integer

        if ($stmt->execute()) {
            $message = '<p class="success">Bài viết đã được cập nhật thành công!</p>';
            // Cập nhật lại biến $post để hiển thị dữ liệu mới trong form mà không cần load lại từ DB
            $post['title'] = $title;
            $post['content'] = $content;
        } else {
            $message = '<p class="error">Lỗi khi cập nhật bài viết: ' . $stmt->error . '</p>';
        }
        $stmt->close();
    }
} else {
    // Nếu truy cập edit.php mà không có ID hoặc không phải POST request
    header("Location: index.php?error=" . urlencode("Yêu cầu không hợp lệ để sửa bài viết."));
    exit();
}

// Nếu sau tất cả các kiểm tra mà biến $post vẫn null, có nghĩa là có lỗi nghiêm trọng
if (!$post) {
    header("Location: index.php?error=" . urlencode("Không thể tìm thấy hoặc xử lý bài viết để sửa."));
    exit();
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sửa Bài viết</title>
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
            <h2>Sửa Bài viết</h2>
            <?php echo $message; ?>

            <form action="edit.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">

                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" rows="15" required><?php echo htmlspecialchars($post['content']); ?></textarea>

                <button type="submit">Cập nhật bài viết</button>
                <a href="index.php" class="button">Hủy</a>
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


