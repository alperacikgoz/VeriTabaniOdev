<?php
require_once '../db/config.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO Users (AdSoyad, Email, Telefon, Sifre, KullaniciTipi) VALUES (?, ?, ?, ?, 'Müşteri')");
    try {
        $stmt->execute([$name, $email, $phone, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Bu e-posta zaten kayıtlı!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .register-container {
            background: #ffffff;
            color: #333;
            width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .register-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #ff7e5f;
        }
        .register-container form {
            display: flex;
            flex-direction: column;
        }
        .register-container label {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .register-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .register-container button {
            background: #ff7e5f;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .register-container button:hover {
            background: #feb47b;
        }
        .register-container a {
            text-decoration: none;
            color: #ff7e5f;
            font-weight: bold;
        }
        .register-container a:hover {
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
    <div class="register-container">
        <h1>Kayıt Ol</h1>
        <form method="POST" action="">
            <label for="name">Ad Soyad:</label>
            <input type="text" id="name" name="name" required placeholder="Ad ve soyadınızı girin">
            <label for="email">E-posta:</label>
            <input type="email" id="email" name="email" required placeholder="E-posta adresinizi girin">
            <label for="phone">Telefon:</label>
            <input type="text" id="phone" name="phone" required placeholder="Telefon numaranızı girin">
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required placeholder="Şifrenizi belirleyin">
            <?php if (!empty($error)): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <button type="submit">Kayıt Ol</button>
        </form>
        <p style="text-align: center;">Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
    </div>
    <footer>&copy; 2025 Tiyatro Rezervasyon Sistemi</footer>
</body>
</html>
