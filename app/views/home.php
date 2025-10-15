<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Witamy</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header>
    <h1>Witamy w mBochnia</h1>
</header>

<main>
    <section class="intro">
        <p>
            <h2>mBochnia – Twoje miasto w smartfonie!</h2>

		<p><strong>mBochnia to oficjalna aplikacja mieszkańca, którą można bezpiecznie i bezpłatnie pobrać na swój
		smartfon ze sklepu Google Play i App Store. To Twój osobisty asystent w mieście, dzięki któremu życie w Bochni
		stanie się prostsze i bardziej komfortowe – bez wychodzenia z domu.</strong></p>

		<p>Dzięki aplikacji:</p>

		<ul>
		    <li><strong>masz wszystkie miejskie informacje zawsze pod ręką,</strong></li>
		    <li><strong>jesteś na bieżąco z ważnymi ogłoszeniami i wydarzeniami,</strong></li>
		    <li><strong>oszczędzasz czas i niczego nie przegapisz.</strong></li>
		</ul>

		<p><strong>mBochnia</strong> zyskała nowoczesną szatę graficzną, zaprojektowaną dla wygody mieszkańców.
		Korzystanie z niej jest intuicyjne i przyjemne.</p>

		<h3>mOgłoszenia – zawsze na bieżąco</h3>

		<p>Nie musisz już szukać informacji po wielu stronach – najważniejsze komunikaty i aktualności trafią
		bezpośrednio do Ciebie. Dzięki systemowi powiadomień, <strong>nic Cię nie ominie</strong> – ani remont drogi,
		ani przerwa w dostawie wody, ani zaproszenie na nadchodzący festyn.</p>

		<p><strong>Z aplikacji dowiesz się między innymi o:</strong></p>

		<ul>
		    <li>Komunikatach Urzędu Miasta,</li>
		    <li>Wydarzeniach kulturalnych i sportowych,</li>
		    <li>Ważnych inwestycjach i zmianach w mieście,</li>
		    <li>Akcjach społecznych i możliwościach zaangażowania.</li>
		</ul>

		<h3>Twoja lokalna tablica ogłoszeń</h3>

		<p>Zależy nam na tym, aby <strong>mBochnia</strong> była jednym, wiarygodnym źródłem informacji dla każdego
		mieszkańca. W aplikacji znajdziesz usługi i ogłoszenia istotne dla Twojej okolicy. To praktyczne rozwiązanie,
		które łączy samorząd z mieszkańcami.</p>

		<h4>Zgłoś sprawę w kilka chwil</h4>

		<p>Zauważyłeś uszkodzoną ławkę w parku, wysypisko śmieci w lesie lub awarię latarni? Teraz możesz to łatwo
		zgłosić przez aplikację. Działa to prosto:</p>

		<ol>
		    <li><strong>Wybierz kategorię</strong> zgłoszenia.</li>
		    <li><strong>Opisz sytuację</strong> i <strong>dodaj zdjęcie</strong>.</li>
		    <li><strong>Wyślij</strong> – geolokalizacja sama wskaże miejsce. Twoje zgłoszenie trafi do odpowiedniego
		    służb miejskich.</li>
		</ol>

		<h3>Rozwijamy się dla Ciebie</h3>

		<p><strong>mBochnia</strong> będzie stale rozwijana, aby jeszcze lepiej odpowiadać na potrzeby mieszkańców.
		To dopiero początek naszej wspólnej, cyfrowej drogi. 
		<strong>Pobierz aplikację już dziś i miej Bochnię zawsze w kieszeni!</strong></p>
	</p>
    </section>

    <div class="container center">
        <nav class="auth-links">
            <div class="action-buttons">
                <a href="/feed" class="btn btn-light">View City Feed</a>
            </div>
            <div class="action-buttons" style="margin-top: 15px;">
                <a href="/login" class="btn btn-primary">Login</a>
                <a href="/register" class="btn btn-secondary">Create Account</a>
            </div>
        </nav>
    </div>

    <section class="note center">
        <p class="text-muted">
            Możesz przeglądać kanał bez logowania, ale niektóre funkcje wymagają założenia konta.
        </p>
    </section>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> mBochnia. All rights reserved.</p>
</footer>

</body>
</html>

