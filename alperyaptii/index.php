<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiyatro Rezervasyon Sistemi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #1e3c72, #2a5298);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            overflow: hidden;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        header {
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 56px;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
            animation: slideIn 1.2s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        main p {
            font-size: 20px;
            margin: 20px 0;
            line-height: 1.8;
            max-width: 700px;
            color: #d1d8e0;
            animation: fadeUp 1.5s ease-in-out;
        }

        @keyframes fadeUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .button-container {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 40px;
            animation: fadeInButtons 2s ease-in-out;
        }

        @keyframes fadeInButtons {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .button-container a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            padding: 15px 40px;
            border-radius: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
        }

        .login-btn {
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        .login-btn:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            transform: translateY(-5px);
        }

        .register-btn {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
        }

        .register-btn:hover {
            background: linear-gradient(to right, #feb47b, #ff7e5f);
            transform: translateY(-5px);
        }

        footer {
            margin-top: 50px;
            font-size: 16px;
            color: #ddd;
            animation: fadeIn 2s ease-in-out;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tiyatro Rezervasyon Sistemine Hoş Geldiniz!</h1>
    </header>
    <main>
        <p>
            Tiyatro dünyasının sihirli atmosferine adım atmaya hazır mısınız? <br>
            Şimdi giriş yaparak en sevdiğiniz tiyatro oyunları için biletlerinizi ayırtabilir <br>
            ya da kolayca rezervasyon yapabilirsiniz. Eğlenceli bir yolculuğa başlayın!
        </p>
        <div class="button-container">
            <a href="pages/login.php" class="login-btn">Giriş Yap</a>
            <a href="pages/register.php" class="register-btn">Kayıt Ol</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Tiyatro Rezervasyon Sistemi | Tüm Hakları Saklıdır</p>
    </footer>
</body>
</html>
