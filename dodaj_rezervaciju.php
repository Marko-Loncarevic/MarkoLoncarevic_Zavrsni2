<?php
require_once 'auth.php';
include("db__connection.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$imeKorisnika     = trim($_POST['imeKorisnika'] ?? '');
$prezimeKorisnika = trim($_POST['prezimeKorisnika'] ?? '');
$emailKorisnika   = trim($_POST['emailKorisnika'] ?? '');
$voziloID         = intval($_POST['voziloID'] ?? 0);
$odKada           = $_POST['odKada'] ?? '';
$doKada           = $_POST['doKada'] ?? '';
$ukupnaCijena     = floatval($_POST['ukupnaCijena'] ?? 0);
$accountId        = isUser() ? intval($_SESSION['account_id']) : null;

// Validacija
if (empty($imeKorisnika) || empty($prezimeKorisnika)) {
    header("Location: index.php?error=Molimo+unesite+ime+i+prezime");
    exit();
}
if (!$voziloID || empty($odKada) || empty($doKada)) {
    header("Location: index.php?error=Nepotpuni+podaci+rezervacije");
    exit();
}

$startDate = new DateTime($odKada);
$endDate   = new DateTime($doKada);
if ($startDate >= $endDate) {
    header("Location: index.php?error=Datum+završetka+mora+biti+nakon+datuma+početka");
    exit();
}

mysqli_begin_transaction($db);

try {
    // Pronađi ili kreiraj korisnika u tablici korisnici
    if (!empty($emailKorisnika)) {
        $stmt = mysqli_prepare($db, "SELECT IDKorisnici FROM korisnici WHERE KontaktKorisnika = ?");
        mysqli_stmt_bind_param($stmt, "s", $emailKorisnika);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        $korisnikID = $row ? $row['IDKorisnici'] : null;
    } else {
        $korisnikID = null;
    }

    if (!$korisnikID) {
        $stmt = mysqli_prepare($db, "INSERT INTO korisnici (ImeKorisnika, PrezimeKorisnika, KontaktKorisnika) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $imeKorisnika, $prezimeKorisnika, $emailKorisnika);
        if (!mysqli_stmt_execute($stmt)) throw new Exception("Greška pri dodavanju korisnika");
        $korisnikID = mysqli_insert_id($db);
    }

    // Provjera preklapanja
    $stmt = mysqli_prepare($db, "SELECT COUNT(*) as cnt FROM rezervacije
                                  WHERE VoziloID = ?
                                  AND LOWER(StatusRezervacije) IN ('aktivna','rezervirano')
                                  AND DatumPocetka < ? AND DatumZavrsetka > ?");
    mysqli_stmt_bind_param($stmt, "iss", $voziloID, $doKada, $odKada);
    mysqli_stmt_execute($stmt);
    $overlap = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($overlap['cnt'] > 0) throw new Exception("Vozilo je već rezervirano za odabrani period");

    // Spremi rezervaciju — poveži i account_id ako je prijavljen
    if ($accountId) {
        $stmt = mysqli_prepare($db, "INSERT INTO rezervacije
            (KorisnikID, VoziloID, DatumRezervacije, DatumPocetka, DatumZavrsetka, UkupnaCijena, StatusRezervacije, AccountID)
            VALUES (?, ?, NOW(), ?, ?, ?, 'Rezervirano', ?)");
        mysqli_stmt_bind_param($stmt, "iissdi", $korisnikID, $voziloID, $odKada, $doKada, $ukupnaCijena, $accountId);
    } else {
        $stmt = mysqli_prepare($db, "INSERT INTO rezervacije
            (KorisnikID, VoziloID, DatumRezervacije, DatumPocetka, DatumZavrsetka, UkupnaCijena, StatusRezervacije)
            VALUES (?, ?, NOW(), ?, ?, ?, 'Rezervirano')");
        mysqli_stmt_bind_param($stmt, "iissd", $korisnikID, $voziloID, $odKada, $doKada, $ukupnaCijena);
    }
    if (!mysqli_stmt_execute($stmt)) throw new Exception("Greška pri kreiranju rezervacije");
    $rezervacijaID = mysqli_insert_id($db);

    // Ažuriraj status vozila
    $newStatus = (strtotime($odKada) > time()) ? 'Rezervirano' : 'Nije dostupno';
    $stmt = mysqli_prepare($db, "UPDATE vozila SET Raspolozivost = ? WHERE IDVozilo = ?");
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $voziloID);
    mysqli_stmt_execute($stmt);

    mysqli_commit($db);

    // Redirect na ekransku potvrdu
    header("Location: potvrda_rezervacije.php?id={$rezervacijaID}");
    exit();

} catch (Exception $e) {
    mysqli_rollback($db);
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>