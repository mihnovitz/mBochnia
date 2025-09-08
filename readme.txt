mBochnia - System Zarządzania Kartami Miejskimi
Opis Projektu

mBochnia to kompleksowy system webowy do zarządzania kartami miejskimi dla mieszkańców Bochni. Aplikacja umożliwia przechowywanie i zarządzanie cyfrowymi wersjami kart w jednym miejscu.
Funkcjonalności

    Rejestracja i logowanie użytkowników z walidacją danych

    Zarządzanie kartami: MKA, RPK i Kartą Mieszkańca

    Generowanie kodów QR do kontroli biletów

    Panel administracyjny do zarządzania użytkownikami

    Responsywny design dostosowany do urządzeń mobilnych

Wymagania Techniczne

    PHP 5.6+ (wsparcie dla starszych wersji)

    PostgreSQL

    Apache/Nginx

    Bootstrap 5.2

Struktura Bazy Danych
Główne tabele:

    account_doc - dane użytkowników

    mka_card_doc - karty Małopolskiej Karty Aglomeracyjnej

    rpk_card_doc - karty RPK Bochnia

    res_card_doc - Karty Mieszkańca

Pola tabeli account_doc:

    pesel (CHARACTER 11) - klucz główny

    imie (VARCHAR 50)

    nazwisko (VARCHAR 50)

    data_urodzenia (DATE)

    plec (CHARACTER 1)

    saldo (NUMERIC 12,2)

    admin (BOOLEAN)

    haslo (VARCHAR 255)

    email (VARCHAR 255)

Instalacja

    Sklonuj repozytorium

bash

git clone <repository-url>
cd mbochnia

    Konfiguracja bazy danych

bash

# Utwórz bazę danych PostgreSQL
createdb mbochnia

# Importuj strukturę (jeśli dostępny plik SQL)
psql mbochnia < database_schema.sql

    Konfiguracja połączenia z bazą
    Edytuj plik config.php:

php

$host = "localhost";
$dbname = "mbochnia";
$user = "twoj_uzytkownik";
$password = "twoje_haslo";

    Konfiguracja serwera WWW

    Ustaw katalog główny na folder projektu

    Włącz obsługę PHP

    Upewnij się że sesje PHP są włączone

Bezpieczeństwo

    Hasła hashowane za pomocą password_hash()

    Ochrona przed SQL Injection (PDO prepared statements)

    Walidacja danych wejściowych

    System sesji i uprawnień

Struktura Plików
text

mbochnia/
├── index.php          # Strona główna
├── login.php          # Logowanie
├── register.php       # Rejestracja
├── documents.php      # Panel kart użytkownika
├── admin.php          # Panel administratora
├── config.php         # Konfiguracja bazy
├── logout.php         # Wylogowanie
├── theme.css          # Style CSS
├── delete.php         # Usuwanie użytkowników
├── edit.php           # Edycja użytkowników
├── create.php         # Tworzenie użytkowników
└── rynek_bochnia.jpg  # Obraz strony głównej

Użycie
Dla Użytkowników:

    Zarejestruj konto przez register.php

    Zaloguj się przez login.php

    Dodaj karty miejskie w documents.php

    Zarządzaj kartami i wyświetlaj kody QR

Dla Administratorów:

    Zaloguj się kontem z uprawnieniami admin

    Dostęp do admin.php do zarządzania użytkownikami

    Przeglądaj wszystkie karty w systemie

Technologie

    Backend: PHP (proceduralny)

    Baza danych: PostgreSQL

    Frontend: HTML5, CSS3, Bootstrap 5.2

    Bezpieczeństwo: PDO, password_hash()

Autor

Mikołaj Michnowicz

System opracowany dla miasta Bochnia
Licencja

Projekt własnościowy miasta Bochnia