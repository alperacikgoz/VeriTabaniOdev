<?php
$host = 'localhost';
$dbname = 'tiyatroRezarvasyon';
$username = 'root'; // Varsayılan kullanıcı adını değiştirdiysen güncelle
$password = 'mesut'; // Şifre varsa ekle

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>
