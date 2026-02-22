<?php
session_start();
include("db__connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {

    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: korisnici.php?error=Neispravan zahtjev");
        exit();
    }

    $id = intval($_POST['id']);

    $stmt = mysqli_prepare($db, "DELETE FROM korisnici WHERE IDKorisnici = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: korisnici.php?success=Korisnik je uspješno obrisan");
    } else {
        header("Location: korisnici.php?error=Greška pri brisanju korisnika");
    }
} else {
    header("Location: korisnici.php");
}
exit();
?>