<?php
session_start();
require_once '../db/config.php'; // Veritabanı bağlantısı

// Kullanıcı giriş yapmamışsa yönlendirme
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Veritabanından oyunları ve ilişkili bilgileri çekme
$stmt = $pdo->prepare("
    SELECT 
        Plays.PlayID, 
        Plays.OyunAdi, 
        Plays.Kategori, 
        Plays.Sure, 
        Plays.Aciklama,
        COALESCE(MIN(Halls.SalonAdi), 'Bilinmiyor') AS SalonAdi,
        COALESCE(MIN(Halls.Konum), 'Bilinmiyor') AS Konum,
        COALESCE(MIN(Sessions.BiletFiyati), 0) AS EnDusukBiletFiyati,
        COALESCE(MIN(Sessions.SessionID), NULL) AS SessionID,
        COUNT(Sessions.SessionID) AS SeansSayisi
    FROM Plays
    LEFT JOIN Sessions ON Plays.PlayID = Sessions.PlayID
    LEFT JOIN Halls ON Sessions.HallID = Halls.HallID
    GROUP BY Plays.PlayID, Plays.OyunAdi, Plays.Kategori, Plays.Sure, Plays.Aciklama
    ORDER BY Plays.OyunAdi
");
$stmt->execute();
$plays = $stmt->fetchAll();

// Varsayılan ve özel görseller
$playImages = [
    'Romeo ve Juliet' => '../assets/images/Ekran görüntüsü 2025-01-04 221735.png',
    'Kral Lear' => '../assets/images/Ekran görüntüsü 2025-01-04 221354.png',
    'Komik Tiyatro' => '../assets/images/Ekran görüntüsü 2025-01-04 221213.png',
    'Macbeth' => '../assets/images/Ekran görüntüsü 2025-01-04 222537.png',
    'Hamlet' => '../assets/images/Ekran görüntüsü 2025-01-04 175058.png',
    'alper' => '../assets/images/Ekran görüntüsü 2025-01-04 230143.png',
    'zero' => '../assets/images/Ekran görüntüsü 2025-01-04 230143.png',
    '0' => '../assets/images/Ekran görüntüsü 2025-01-04 230003.png',
    'Bir Yaz Gecesi Rüyası' => '../assets/images/Ekran görüntüsü 2025-01-06 230947.png',
    'default' => '../assets/images/default.png'
    

];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #141E30, #243B55);
            color: #fff;
        }
        header {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        header h1 {
            margin: 0;
            font-size: 28px;
        }
        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background 0.3s ease;
        }
        header nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .play-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .play-card {
            background: #ffffff;
            color: #333;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .play-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        .play-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .play-details {
            padding: 20px;
            text-align: center;
        }
        .play-details h2 {
            font-size: 22px;
            color: #4CAF50;
        }
        .play-details p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .play-details button {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            margin-top: 15px;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
        }
        .play-details button:hover {
            background: linear-gradient(to right, #feb47b, #ff7e5f);
            transform: scale(1.1);
        }
        .no-data {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hoş Geldiniz, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <nav>
            <a href="logout.php">Çıkış Yap</a>
        </nav>
    </header>
    <div class="main-container">
        <h2 style="text-align: center; margin-bottom: 20px;">Mevcut Tiyatro Oyunları</h2>
        <?php if ($plays): ?>
            <div class="play-container">
                <?php foreach ($plays as $play): ?>
                    <?php
                    // Görsel yolu belirleme
                    $imagePath = $playImages[$play['OyunAdi']] ?? $playImages['default'];
                    ?>
                    <div class="play-card">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($play['OyunAdi']) ?> Görseli">
                        <div class="play-details">
                            <h2><?= htmlspecialchars($play['OyunAdi']) ?></h2>
                            <p><strong>Kategori:</strong> <?= htmlspecialchars($play['Kategori']) ?></p>
                            <p><strong>Süre:</strong> <?= htmlspecialchars($play['Sure']) ?> dakika</p>
                            <p><strong>Salon:</strong> <?= htmlspecialchars($play['SalonAdi']) ?></p>
                            <p><strong>Konum:</strong> <?= htmlspecialchars($play['Konum']) ?></p>
                            <p><strong>En Düşük Bilet Fiyatı:</strong> <?= htmlspecialchars($play['EnDusukBiletFiyati']) ?> TL</p>
                            <p><strong>Toplam Seans Sayısı:</strong> <?= htmlspecialchars($play['SeansSayisi']) ?></p>
                            <button 
                                onclick="location.href='reservation.php?session=<?= $play['SessionID'] ?>'">
                                Rezervasyon Yap
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">Şu anda herhangi bir oyun bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</body>
</html>
