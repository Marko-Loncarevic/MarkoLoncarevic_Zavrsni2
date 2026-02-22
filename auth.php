<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['account_id']) || isset($_SESSION['is_admin']);
}

function isAdmin(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function isUser(): bool {
    return isset($_SESSION['account_id']) && !isAdmin();
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: login.php?error=Pristup+zabranjen');
        exit();
    }
}

function getAccountName(): string {
    if (isAdmin()) return 'Admin';
    return trim(($_SESSION['account_ime'] ?? '') . ' ' . ($_SESSION['account_prezime'] ?? ''));
}

function getAccountEmail(): string {
    return $_SESSION['account_email'] ?? '';
}
?>