<?php
session_start();

// Zniszcz wszystkie zmienne sesji
$_SESSION = array();

// Jeśli chcesz zniszczyć całkowicie sesję, usuń również ciasteczko sesji
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Na koniec zniszcz sesję
session_destroy();

// Przekieruj do strony logowania
header("Location: login.php");
exit;
?>