<?php
session_start();
include("db__connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {

    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: pregled_rezervacija.php?error=Neispravan zahtjev");
        exit();
    }

    $reservationId = intval($_POST['id']);

    // Fetch VoziloID before updating
    $stmt = mysqli_prepare($db, "SELECT VoziloID FROM rezervacije WHERE IDRezervacija = ?");
    mysqli_stmt_bind_param($stmt, "i", $reservationId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) {
        header("Location: pregled_rezervacija.php?error=Rezervacija nije pronađena");
        exit();
    }

    $voziloID = $row['VoziloID'];

    // Set status to Otkazana instead of deleting
    $stmt = mysqli_prepare($db, "UPDATE rezervacije SET StatusRezervacije = 'Otkazana' WHERE IDRezervacija = ?");
    mysqli_stmt_bind_param($stmt, "i", $reservationId);

    if (mysqli_stmt_execute($stmt)) {
        // Check if vehicle still has any active/reserved reservations
        $stmt2 = mysqli_prepare($db, "SELECT COUNT(*) as cnt FROM rezervacije
                                      WHERE VoziloID = ?
                                      AND LOWER(StatusRezervacije) IN ('aktivna','rezervirano')");
        mysqli_stmt_bind_param($stmt2, "i", $voziloID);
        mysqli_stmt_execute($stmt2);
        $remaining = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

        if ($remaining['cnt'] == 0) {
            $stmt3 = mysqli_prepare($db, "UPDATE vozila SET Raspolozivost = 'Dostupno' WHERE IDVozilo = ?");
            mysqli_stmt_bind_param($stmt3, "i", $voziloID);
            mysqli_stmt_execute($stmt3);
        }

        header("Location: pregled_rezervacija.php?success=Rezervacija je otkazana");
    } else {
        header("Location: pregled_rezervacija.php?error=Greška pri otkazivanju rezervacije");
    }
} else {
    header("Location: pregled_rezervacija.php");
}
exit();
?>