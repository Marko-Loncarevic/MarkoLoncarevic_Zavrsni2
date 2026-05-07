<?php
// ============================================================
//  config.php  —  NIKAD ne commitaj ovaj fajl u git!
//  Dodaj u .gitignore:  config.php
// ============================================================

// Admin username (nije email, može biti bilo što)
define('ADMIN_USERNAME', 'admin');

// Generiraj hash jednom:  php -r "echo password_hash('tvoja_lozinka', PASSWORD_BCRYPT, ['cost'=>12]);"
// i zalijepi ovdje — NIKAD ne stavljaj plaintext lozinku
// ZA TEST: lozinka = "admin123"
define('ADMIN_PASSWORD_HASH', '$2y$12$zkq359cPWEnxB7u0lE70WufsCKoW0.i/BJZPe1LwF.8wx.8iG.vJy');

// Koliko neuspjelih pokušaja prijave dopustiti
define('MAX_LOGIN_ATTEMPTS', 5);

// Koliko sekundi blokirati nakon prekoračenja
define('LOCKOUT_DURATION', 900); // 15 minuta

// Tablica u bazi za brute-force zaštitu
define('LOGIN_ATTEMPTS_TABLE', 'login_attempts');
?>