<?php
session_start();
include("db__connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {

    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: pregled_rezervacija.php?error=Neispravan zahtjev");
        exit();
    }

    $reservationId = intval($_POST['id']);

    // Only allow deleting Zavrsena or Otkazana reservations
    $stmt = mysqli_prepare($db, "SELECT StatusRezervacije FROM rezervacije WHERE IDRezervacija = ?");
    mysqli_stmt_bind_param($stmt, "i", $reservationId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row || !in_array($row['StatusRezervacije'], ['Zavrsena', 'Otkazana'])) {
        header("Location: pregled_rezervacija.php?error=Nije moguće obrisati aktivnu rezervaciju");
        exit();
    }

    $stmt = mysqli_prepare($db, "DELETE FROM rezervacije WHERE IDRezervacija = ?");
    mysqli_stmt_bind_param($stmt, "i", $reservationId);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pregled_rezervacija.php?success=Rezervacija je obrisana");
    } else {
        header("Location: pregled_rezervacija.php?error=Greška pri brisanju rezervacije");
    }
} else {
    header("Location: pregled_rezervacija.php");
}
exit();
?>