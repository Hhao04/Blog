<?php
// Blog/post.php

require_once 'config.php';

$post = null; // Khởi tạo biến $post

// Kiểm tra xem có ID bài viết được truyền qua URL không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Chuẩn bị câu lệnh SQL an toàn (sử dụng prepared statement để tránh SQL Injection)
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" nghĩa là tham số là số nguyên (integer)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc(); // Lấy dữ liệu bài viết
    }
    $stmt->close(); // Đóng statement
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? htmlspecialchars($post['title']) : 'Bài viết không tìm thấy'; ?> - Blog Đơn Giản</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Blog Đơn Giản</h1>
            <nav>
                <a href="index.php">Trang chủ</a>
                <a href="admin/">Quản lý bài viết (Admin)</a>
            </nav>
        </header>

        <main>
            <?php if ($post): ?>
                <article>
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="meta">Ngày đăng: <?php echo $post['created_at']; ?></p>
                    <div class="post-content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </article>
                <p><a href="index.php">Quay lại danh sách bài viết</a></p>
            <?php else: ?>
                <p>Bài viết bạn yêu cầu không tồn tại.</p>
                <p><a href="index.php">Quay lại trang chủ</a></p>
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
