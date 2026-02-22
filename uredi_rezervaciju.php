<?php
include("db__connection.php");

// Fetch reservation data for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT r.*, k.ImeKorisnika, k.PrezimeKorisnika, k.KontaktKorisnika,
                     v.Naziv AS VoziloNaziv, v.Model AS VoziloModel, v.CijenaKoristenjaDnevno
              FROM rezervacije r
              JOIN korisnici k ON r.KorisnikID = k.IDKorisnici
              JOIN vozila v ON r.VoziloID = v.IDVozilo
              WHERE r.IDRezervacija = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rez = mysqli_fetch_assoc($result);

    if (!$rez) {
        header("Location: pregled_rezervacija.php?error=Rezervacija nije pronađena");
        exit();
    }
}

// Handle POST - save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = intval($_POST['id']);
    $odKada     = $_POST['odKada'];
    $doKada     = $_POST['doKada'];
    $status     = $_POST['status'];
    $cijena     = floatval($_POST['ukupnaCijena']);

    // Basic validation
    $start = new DateTime($odKada);
    $end   = new DateTime($doKada);
    if ($start >= $end) {
        header("Location: uredi_rezervaciju.php?id=$id&error=Datum završetka mora biti nakon datuma početka");
        exit();
    }

    // Allowed statuses
    $allowedStatuses = ['Aktivna', 'Rezervirano', 'Zavrsena', 'Otkazana'];
    if (!in_array($status, $allowedStatuses)) {
        header("Location: uredi_rezervaciju.php?id=$id&error=Nevažeći status");
        exit();
    }

    // Check for overlapping reservations (excluding this one)
    $overlapQuery = "SELECT COUNT(*) as cnt FROM rezervacije
                     WHERE VoziloID = (SELECT VoziloID FROM rezervacije WHERE IDRezervacija = ?)
                     AND IDRezervacija != ?
                     AND LOWER(StatusRezervacije) IN ('aktivna', 'rezervirano')
                     AND DatumPocetka < ? AND DatumZavrsetka > ?";
    $stmt = mysqli_prepare($db, $overlapQuery);
    mysqli_stmt_bind_param($stmt, "iiss", $id, $id, $doKada, $odKada);
    mysqli_stmt_execute($stmt);
    $overlapResult = mysqli_stmt_get_result($stmt);
    $overlap = mysqli_fetch_assoc($overlapResult);

    if ($overlap['cnt'] > 0) {
        header("Location: uredi_rezervaciju.php?id=$id&error=Vozilo je već rezervirano za odabrani period");
        exit();
    }

    $updateQuery = "UPDATE rezervacije SET
                    DatumPocetka      = ?,
                    DatumZavrsetka    = ?,
                    StatusRezervacije = ?,
                    UkupnaCijena      = ?
                    WHERE IDRezervacija = ?";
    $stmt = mysqli_prepare($db, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sssdi", $odKada, $doKada, $status, $cijena, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pregled_rezervacija.php?success=Rezervacija uspješno ažurirana");
    } else {
        header("Location: uredi_rezervaciju.php?id=$id&error=Greška pri ažuriranju rezervacije");
    }
    exit();
}
?>
<!doctype html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Uredi rezervaciju</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-secondary: #E8EDE7;
            --accent-green: #68896B;
            --accent-sage: #8FA67E;
            --accent-light: #C8D5B9;
            --text-primary: #3d4a3e;
            --text-secondary: #6B7B6E;
            --white: #ffffff;
        }
        body { background-color: var(--bg-secondary); font-family: 'Inter', sans-serif; color: var(--text-primary); }
        .card { border-radius: 16px; border: 1px solid var(--accent-light); }
        .card-header { background: var(--white); border-radius: 16px 16px 0 0 !important; border-bottom: 1px solid var(--accent-light); }
        .form-control, .form-select {
            border: 1px solid var(--accent-light); border-radius: 10px;
            color: var(--text-primary);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-sage);
            box-shadow: 0 0 0 4px rgba(143,166,126,0.12);
        }
        .btn-primary { background-color: var(--accent-green); border: none; border-radius: 10px; }
        .btn-primary:hover { background-color: var(--accent-sage); }
        .btn-secondary { background-color: var(--bg-secondary); border: none; color: var(--text-primary); border-radius: 10px; }
        .vehicle-info-box {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 1px solid var(--accent-light);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .vehicle-info-box strong { color: var(--accent-green); font-family: 'Outfit', sans-serif; font-size: 1.1rem; }
        .status-preview {
            display: inline-block;
            padding: 0.35rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 0.4rem;
        }
        .sp-aktivna    { background: #88B49A; color: white; }
        .sp-rezervirano{ background: #D4A574; color: white; }
        .sp-zavrsena   { background: #D98B8B; color: white; }
        .sp-otkazana   { background: #C48B7C; color: white; }
        .alert-danger  { background: #f4ddd4; border: 1px solid #C48B7C; border-radius: 10px; color: var(--text-primary); }
    </style>
</head>
<body>
<?php include("navigacija.php"); ?>

<div class="container py-4" style="max-width: 700px;">

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header py-3 px-4">
            <h4 class="mb-0" style="font-family:'Outfit',sans-serif;">
                <i class="fas fa-edit me-2" style="color:#8FA67E;"></i>Uredi rezervaciju #<?= $rez['IDRezervacija'] ?>
            </h4>
        </div>
        <div class="card-body p-4">

            <!-- Vehicle & customer info (read-only) -->
            <div class="vehicle-info-box">
                <strong><?= htmlspecialchars($rez['VoziloNaziv'] . ' ' . $rez['VoziloModel']) ?></strong>
                <span class="text-muted ms-2" style="font-size:0.85rem;">(<?= number_format($rez['CijenaKoristenjaDnevno'], 2) ?> €/dan)</span>
                <div class="mt-1" style="color:var(--text-secondary);">
                    <i class="fas fa-user me-1"></i>
                    <?= htmlspecialchars($rez['ImeKorisnika'] . ' ' . $rez['PrezimeKorisnika']) ?>
                    <?php if ($rez['KontaktKorisnika']): ?>
                        &nbsp;·&nbsp; <?= htmlspecialchars($rez['KontaktKorisnika']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $rez['IDRezervacija'] ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Od kada <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="odKada" id="odKada"
                               value="<?= date('Y-m-d\TH:i', strtotime($rez['DatumPocetka'])) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Do kada <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="doKada" id="doKada"
                               value="<?= date('Y-m-d\TH:i', strtotime($rez['DatumZavrsetka'])) ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ukupna cijena (€)</label>
                        <input type="number" step="0.01" class="form-control" name="ukupnaCijena" id="ukupnaCijena"
                               value="<?= number_format($rez['UkupnaCijena'], 2, '.', '') ?>" required>
                        <small class="text-muted">Automatski se računa pri promjeni datuma</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="statusSelect" required>
                            <option value="Aktivna"     <?= $rez['StatusRezervacije'] == 'Aktivna'     ? 'selected' : '' ?>>Aktivna</option>
                            <option value="Rezervirano" <?= $rez['StatusRezervacije'] == 'Rezervirano' ? 'selected' : '' ?>>Rezervirano</option>
                            <option value="Zavrsena"    <?= $rez['StatusRezervacije'] == 'Zavrsena'    ? 'selected' : '' ?>>Završena</option>
                            <option value="Otkazana"    <?= $rez['StatusRezervacije'] == 'Otkazana'    ? 'selected' : '' ?>>Otkazana</option>
                        </select>
                        <div id="statusPreview" class="mt-1"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="pregled_rezervacija.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Natrag
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Spremi promjene
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const pricePerDay = <?= floatval($rez['CijenaKoristenjaDnevno']) ?>;

function recalcPrice() {
    const od = new Date(document.getElementById('odKada').value);
    const do_ = new Date(document.getElementById('doKada').value);
    if (od && do_ && do_ > od) {
        const days = Math.ceil((do_ - od) / (1000 * 60 * 60 * 24));
        document.getElementById('ukupnaCijena').value = (days * pricePerDay).toFixed(2);
    }
}
document.getElementById('odKada').addEventListener('change', recalcPrice);
document.getElementById('doKada').addEventListener('change', recalcPrice);

// Status badge preview
const statusColors = {
    'Aktivna':     'sp-aktivna',
    'Rezervirano': 'sp-rezervirano',
    'Zavrsena':    'sp-zavrsena',
    'Otkazana':    'sp-otkazana'
};
const statusLabels = {
    'Aktivna':     'Aktivna',
    'Rezervirano': 'Rezervirano',
    'Zavrsena':    'Završena',
    'Otkazana':    'Otkazana'
};
function updateStatusPreview() {
    const val = document.getElementById('statusSelect').value;
    const preview = document.getElementById('statusPreview');
    preview.innerHTML = `<span class="status-preview ${statusColors[val]}">${statusLabels[val]}</span>`;
}
document.getElementById('statusSelect').addEventListener('change', updateStatusPreview);
updateStatusPreview();
</script>
</body>
</html>