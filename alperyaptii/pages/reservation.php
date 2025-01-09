<?php
session_start();
require_once '../db/config.php';

// Kullanıcı giriş yapmamışsa yönlendirme
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Seans ID'sini al ve kontrol et
$sessionId = $_GET['session'] ?? null;

if (!$sessionId || !is_numeric($sessionId)) {
    echo "<p style='color: red;'>Geçersiz Seans ID!</p>";
    exit;
}

// Seans ve salon bilgilerini al
$stmt = $pdo->prepare("
    SELECT Sessions.SessionID, Sessions.Tarih, Sessions.Saat, Sessions.BiletFiyati,
           Halls.HallID, Halls.SalonAdi, Halls.Kapasite
    FROM Sessions
    JOIN Halls ON Sessions.HallID = Halls.HallID
    WHERE Sessions.SessionID = ?
");
$stmt->execute([$sessionId]);
$session = $stmt->fetch();

if (!$session) {
    echo "<p style='color: red;'>Seans bulunamadı!</p>";
    exit;
}

// Koltuk bilgilerini al
$seatStmt = $pdo->prepare("SELECT * FROM Seats WHERE HallID = ?");
$seatStmt->execute([$session['HallID']]);
$seats = $seatStmt->fetchAll();

// Mesaj değişkeni
$message = "";

// Rezervasyon işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedSeat = $_POST['seat'] ?? null;

    if ($selectedSeat) {
        $checkSeat = $pdo->prepare("SELECT Durum FROM Seats WHERE SeatID = ?");
        $checkSeat->execute([$selectedSeat]);
        $seat = $checkSeat->fetch();

        if ($seat && $seat['Durum'] === 'Boş') {
            $pdo->beginTransaction();
            try {
                $reserveStmt = $pdo->prepare("
                    INSERT INTO Reservations (UserID, SessionID, SeatID, OdemeDurumu)
                    VALUES (?, ?, ?, 'Beklemede')
                ");
                $reserveStmt->execute([$_SESSION['user_id'], $sessionId, $selectedSeat]);

                $updateSeat = $pdo->prepare("UPDATE Seats SET Durum = 'Rezerve' WHERE SeatID = ?");
                $updateSeat->execute([$selectedSeat]);

                $pdo->commit();
                $message = "<p style='color: green;'>Koltuk başarıyla rezerve edildi! Koltuk ID: $selectedSeat</p>";
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "<p style='color: red;'>Rezervasyon sırasında bir hata oluştu!</p>";
            }
        } else {
            $message = "<p style='color: red;'>Seçilen koltuk uygun değil!</p>";
        }
    } else {
        $message = "<p style='color: red;'>Lütfen bir koltuk seçin!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyon Yap</title>
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
        header h1, header p {
            margin: 5px 0;
        }
        .main-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            color: #333;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .screen {
            background: #6a11cb;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
            gap: 10px;
            justify-content: center;
        }
        .seat {
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
        }
        .seat.empty:hover {
            transform: scale(1.1);
            background-color:rgb(38, 180, 128);
        }
        .seat.reserved {
            background-color: #f4f4f4;
            color: #999;
            cursor: not-allowed;
        }
        .seat.selected {
            background-color: #ff9800;
            color: white;
        }
        form {
            text-align: center;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            border: none;
            color: white;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
        }
        button:hover {
            background: linear-gradient(to right, #feb47b, #ff7e5f);
            transform: scale(1.1);
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Rezervasyon Yap - <?= htmlspecialchars($session['SalonAdi']) ?></h1>
        <p>Seans: <?= htmlspecialchars($session['Tarih']) ?> - <?= htmlspecialchars($session['Saat']) ?></p>
        <p>Bilet Fiyatı: <?= htmlspecialchars($session['BiletFiyati']) ?> TL</p>
    </header>
    <div class="main-container">
        <div class="screen">Sahne</div>
        <form method="POST" action="">
            <div class="seat-grid">
                <?php foreach ($seats as $seat): ?>
                    <div 
                        class="seat <?= $seat['Durum'] === 'Boş' ? 'empty' : 'reserved' ?>" 
                        data-seat-id="<?= $seat['SeatID'] ?>" 
                        data-seat-no="<?= htmlspecialchars($seat['KoltukNo']) ?>">
                        <?= htmlspecialchars($seat['KoltukNo']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="seat" id="selectedSeat" value="">
            <button type="submit">Rezervasyonu Onayla</button>
        </form>
        <div class="message"><?= $message ?></div>
    </div>
    <script>
        const seats = document.querySelectorAll('.seat.empty');
        const selectedSeatInput = document.getElementById('selectedSeat');

        seats.forEach(seat => {
            seat.addEventListener('click', function () {
                seats.forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                selectedSeatInput.value = this.dataset.seatId;
            });
        });
    </script>
</body>
</html>
