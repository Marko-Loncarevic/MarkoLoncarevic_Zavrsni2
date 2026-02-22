<?php
session_start();
include("db__connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {

    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: pregled_vozila.php?error=Neispravan zahtjev");
        exit();
    }

    $id = intval($_POST['id']);

    $stmt = mysqli_prepare($db, "DELETE FROM vozila WHERE IDVozilo = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pregled_vozila.php?success=Vozilo je uspješno obrisano");
    } else {
        header("Location: pregled_vozila.php?error=Greška pri brisanju vozila");
    }
} else {
    header("Location: pregled_vozila.php");
}
exit();
?>