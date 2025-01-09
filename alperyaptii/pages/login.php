<?php
require_once '../db/config.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Sifre'])) {
        session_start();
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_name'] = $user['AdSoyad'];
        $_SESSION['user_type'] = $user['KullaniciTipi'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "E-posta veya şifre hatalı!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .login-container {
            background: #ffffff;
            color: #333;
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #6a11cb;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container label {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .login-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-container button {
            background: #6a11cb;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-container button:hover {
            background: #2575fc;
        }
        .login-container a {
            text-decoration: none;
            color: #6a11cb;
            font-weight: bold;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Giriş Yap</h1>
        <form method="POST" action="">
            <label for="email">E-posta:</label>
            <input type="email" id="email" name="email" required placeholder="E-posta adresinizi girin">
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required placeholder="Şifrenizi girin">
            <?php if (!empty($error)): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <button type="submit">Giriş Yap</button>
        </form>
        <p style="text-align: center;">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
    </div>
    <footer>&copy; 2025 Tiyatro Rezervasyon Sistemi</footer>
</body>
</html>
