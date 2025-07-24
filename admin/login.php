<?php
session_start();
$correct_username = 'admin';
$correct_password = '123456'; // ✅ Bạn nên đổi sau

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $correct_username && $pass === $correct_password) {
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Đăng nhập Quản trị</h2>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <form method="post">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" name="username" required>
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <p><a href="../index.php">← Về trang chính</a></p>
    </div>
</body>
</html>
