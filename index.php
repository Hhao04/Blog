<?php
// Blog/index.php

// Bao gồm file cấu hình database
require_once 'config.php';

// Lấy tất cả bài viết từ database, sắp xếp theo ngày tạo mới nhất
$sql = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Của Tôi</title>
    <link rel="stylesheet" href="style.css">

    <style>
    .login-admin {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 0.9em;
    }
    </style>

</head>
<header style="position: relative;">
    <h1>Blog</h1>
    <nav>
        <a href="index.php">Trang chủ</a>
    </nav>
    <a href="/admin/login.php" class="login-admin">Đăng nhập Admin</a>
</header>

<body>
    <div class="container">
        <header>
            <h1>Blog</h1>
            <nav>
                <a href="index.php">Trang chủ</a>
            </nav>
        </header>

        <main>
            <h2>Các bài viết mới nhất</h2>
            <?php
            if ($result->num_rows > 0) {
                // Duyệt qua từng hàng dữ liệu
                while($row = $result->fetch_assoc()) {
                    echo "<article>";
                    echo "<h3><a href='post.php?id=" . $row["id"] . "'>" . htmlspecialchars($row["title"]) . "</a></h3>";
                    // Hiển thị một đoạn trích ngắn của nội dung (150 ký tự)
                    echo "<p>" . htmlspecialchars(mb_substr($row["content"], 0, 150, 'UTF-8')) . (mb_strlen($row["content"], 'UTF-8') > 150 ? "..." : "") . "</p>";
                    echo "<p class='meta'>Ngày đăng: " . $row["created_at"] . "</p>";
                    echo "</article>";
                }
            } else {
                echo "<p>Chưa có bài viết nào. Hãy đăng bài đầu tiên của bạn!</p>";
            }
            ?>
        </main>

        <footer>
            <p>© <?php echo date("Y"); ?> Blog</p>
        </footer>
    </div>
</body>
</html>
<?php
// Đóng kết nối database
$conn->close();
?>