<?php
// app/views/home/index.php
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mBochnia - Twoje miasto w smartfonie</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">  -->

    <link rel="stylesheet" href="/public/assets/css/theme.css">
    <link rel="stylesheet" href="/public/assets/css/auth.css"> <!-- For auth pages -->
    <link rel="stylesheet" href="/public/assets/css/admin.css"> <!-- For admin pages -->
    <style>
        body {
            background: linear-gradient(135deg, #ff5151 0%, #e04040 100%);
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            color: #333;
        }
        .hero-section {
            background: white;
            border-radius: 20px;
            margin: 2rem auto;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 1200px;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-text {
            color: #ff5151;
            font-weight: bold;
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .logo-subtitle {
            color: #666;
            font-size: 1.2rem;
        }
        .main-image {
            width: 100%;
            border-radius: 15px;
            margin: 2rem 0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #ff5151;
            font-weight: bold;
            margin: 2rem 0 1rem 0;
            border-bottom: 3px solid #ff5151;
            padding-bottom: 0.5rem;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }
        .feature-list li:before {
            content: "✓";
            color: #ff5151;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .btn-download {
            background-color: #000;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-weight: bold;
            font-size: 1.1rem;
            margin: 1rem 0.5rem;
            transition: all 0.3s;
        }
        .btn-download:hover {
            background-color: #333;
            transform: translateY(-2px);
        }
        .app-badges {
            text-align: center;
            margin: 2rem 0;
        }
        .login-section {
            background: #000;
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }
        .btn-login {
            background-color: #ff5151;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        .btn-login:hover {
            background-color: #e04040;
        }
        .btn-register {
            background-color: transparent;
            color: white;
            border: 2px solid #ff5151;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        .btn-register:hover {
            background-color: #ff5151;
        }
        .btn-dashboard {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        .btn-dashboard:hover {
            background-color: #218838;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: bold;
            margin: 0 0.5rem;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
        .footer {
            text-align: center;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .welcome-message {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="hero-section">
        <!-- Logo i nagłówek -->
        <div class="logo">
            <div class="logo-text">mBochnia</div>
            <div class="logo-subtitle">Twoje miasto w smartfonie</div>

            <?php if ($is_logged_in && isset($user_data['name'])): ?>
                <div class="welcome-message">
                    Witaj, <?php echo htmlspecialchars($user_data['name']); ?>!
                </div>
            <?php endif; ?>
        </div>

        <!-- Zdjęcie -->
        <div class="text-center">
            <img src="/public/assets/images/rynek_bochnia.jpg" alt="Rynek w Bochni" class="main-image"
                 onerror="this.style.display='none'">
        </div>

        <!-- Główna sekcja -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-4">Wszystko, czego potrzebujesz, w jednym miejscu!</h2>

                <p class="lead text-center">
                    mBochnia to aplikacja, którą możesz już bezpiecznie i bezpłatnie pobrać na swojego smartfona
                    ze sklepu Google Play и App Store. To Twój osobisty asystent, dzięki któremu załatwianie spraw
                    urzędowych i codziennych spraw w mieście będzie prostsze i wygodniejsze – bez wychodzenia z domu.
                </p>

                <!-- Przyciski pobierania -->
                <div class="app-badges">
                    <button class="btn-download">Pobierz z Google Play</button>
                    <button class="btn-download">Pobierz z App Store</button>
                </div>

                <!-- Korzyści -->
                <h3 class="section-title">Dzięki aplikacji mBochnia:</h3>
                <ul class="feature-list">
                    <li>masz swoje miejskie dokumenty i karty zawsze pod ręką,</li>
                    <li>szybko i wygodnie załatwiasz sprawy urzędowe i miejskie,</li>
                    <li>oszczędzasz czas i pieniądze.</li>
                </ul>

                <p>
                    Aplikacja mBochnia została zaprojektowana zgodnie z najnowszymi trendami.
                    Korzystanie z niej jest intuicyjne i komfortowe.
                </p>

                <!-- Cyfrowe karty -->
                <h3 class="section-title">Twoje cyfrowe karty – wygoda na co dzień</h3>
                <p>
                    Zapomnij o noszeniu dodatkowych kart – wystarczy, że pokażesz je w aplikacji.
                    W mBochni masz do dyspozycji:
                </p>
                <ul class="feature-list">
                    <li><strong>Kartę Mieszkańca</strong> – korzystaj z benefitów i usług przeznaczonych dla mieszkańców Bochni.</li>
                    <li><strong>Kartę MKA (Małopolską Kartę Aglomeracyjną)</strong> – podróżuj autobusami i pociągami do Krakowa.</li>
                    <li><strong>Kartę RPK (Regionalne Przedsiębiorstwo Komunikacyjne)</strong> – korzystaj z miejskich autobusów w Bochni.</li>
                </ul>

                <!-- Nowa jakość usług -->
                <h3 class="section-title">Nowa jakość usług miejskich</h3>
                <p>
                    Zależy nam na tym, aby funkcje dostępne w aplikacji spełniały oczekiwania i zaspokajały
                    potrzeby wszystkich mieszkańców. W mBochnia znajdziesz m.in.:
                </p>
                <ul class="feature-list">
                    <li><strong>Cyfrowe dokumenty i karty</strong> – miej je zawsze przy sobie, w swoim smartfonie.</li>
                    <li><strong>Składanie wniosków</strong> – załatwiaj swoje sprawy urzędowe bez wychodzenia z domu.</li>
                    <li><strong>Ogłoszenia i alerts</strong> – bądź na bieżąco z wszystkim, co dzieje się w Twoim mieście.</li>
                    <li><strong>Zgłaszanie spraw</strong> – poinformuj urząd o awarii lub problemie w przestrzeni miejskiej.</li>
                </ul>

                <!-- Podsumowanie -->
                <h3 class="section-title">Twoje miasto w aplikacji mBochnia</h3>
                <p>
                    mBochnia to centralny punkt dostępu do usług i informacji Twojego miasta.
                    To bardzo ciekawe i korzystne rozwiązanie, zarówno dla samorządu, jak i dla mieszkańców.
                </p>
                <p class="lead text-center">
                    Załatwiaj swoje sprawy szybko, wygodnie i nowocześnie. Pobierz aplikację mBochnia już dziś!
                </p>

                <!-- Sekcja logowania/dashboardu -->
                <div class="login-section">
                    <?php if ($is_logged_in): ?>
                        <h4>Jesteś zalogowany!</h4>
                        <p>Przejdź do panelu użytkownika aby zarządzać swoimi kartami</p>
                        <div>
                            <a href="index.php?action=dashboard" class="btn btn-dashboard">Przejdź do panelu</a>
                            <a href="index.php?action=logout" class="btn btn-logout">Wyloguj się</a>
                        </div>
                    <?php else: ?>
                        <h4>Masz już konto?</h4>
                        <p>Zaloguj się aby uzyskać dostęp do wszystkich funkcji</p>
                        <div>
                            <a href="index.php?action=login" class="btn btn-login">Zaloguj się</a>
                            <a href="index.php?action=register" class="btn btn-register">Zarejestruj się</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stopka -->
    <div class="footer">
        <p>&copy; 2024 mBochnia - Oficjalna aplikacja miasta Bochnia</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>