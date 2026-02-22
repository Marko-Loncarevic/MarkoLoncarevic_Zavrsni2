<!doctype html>
<?php
require_once 'auth.php';
include('db__connection.php');

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit(); }

$stmt = mysqli_prepare($db, "
    SELECT r.*, 
           k.ImeKorisnika, k.PrezimeKorisnika, k.KontaktKorisnika,
           v.Naziv AS VoziloNaziv, v.Model AS VoziloModel, v.CijenaKoristenjaDnevno,
           vs.PutanjaSlike
    FROM rezervacije r
    JOIN korisnici k  ON r.KorisnikID = k.IDKorisnici
    JOIN vozila v     ON r.VoziloID   = v.IDVozilo
    LEFT JOIN vozila_slike vs ON v.IDVozilo = vs.VoziloID AND vs.JeGlavna = 1
    WHERE r.IDRezervacija = ?
");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$r) { header('Location: index.php'); exit(); }

$start    = new DateTime($r['DatumPocetka']);
$end      = new DateTime($r['DatumZavrsetka']);
$days     = max(1, $start->diff($end)->days);
?>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rezervacija potvrđena — Rent-a-Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root { --bg:#E8EDE7;--white:#fff;--green:#68896B;--sage:#8FA67E;--light:#C8D5B9;--text:#3d4a3e;--muted:#6B7B6E; }
        body { background:var(--bg);font-family:'Inter',sans-serif;min-height:100vh; }
        .page-wrap { max-width:560px;margin:3rem auto;padding:0 1rem 3rem; }

        /* Success icon */
        .check-circle {
            width:90px;height:90px;border-radius:50%;
            background:linear-gradient(135deg,#68896B,#8FA67E);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 1.5rem;
            animation:popIn .55s cubic-bezier(.175,.885,.32,1.275) both;
            box-shadow:0 8px 24px rgba(104,137,107,.3);
        }
        .check-circle i { color:#fff;font-size:2.2rem; }
        @keyframes popIn { 0%{transform:scale(0);opacity:0}100%{transform:scale(1);opacity:1} }

        .conf-title { font-family:'Outfit',sans-serif;font-size:1.7rem;font-weight:700;color:var(--text);text-align:center;margin-bottom:.4rem; }
        .conf-sub { text-align:center;color:var(--muted);font-size:.93rem;margin-bottom:2rem; }
        .conf-id { display:inline-block;background:var(--light);border-radius:20px;padding:.25rem .9rem;font-size:.82rem;font-weight:600;color:var(--green); }

        /* Card */
        .conf-card { background:var(--white);border-radius:20px;border:1px solid var(--light);overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.06);margin-bottom:1.25rem; }
        .conf-card-header { background:linear-gradient(135deg,#68896B,#8FA67E);padding:1.25rem 1.5rem;color:#fff; }
        .conf-card-header h6 { margin:0;font-family:'Outfit',sans-serif;font-size:1rem;font-weight:600;opacity:.9;letter-spacing:.3px; }
        .conf-card-body { padding:1.25rem 1.5rem; }

        /* Vehicle row */
        .vehicle-row { display:flex;align-items:center;gap:1rem; }
        .vehicle-img { width:80px;height:56px;border-radius:10px;object-fit:cover;border:1px solid var(--light);flex-shrink:0; }
        .vehicle-img-placeholder { width:80px;height:56px;border-radius:10px;background:var(--bg);border:1px solid var(--light);display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .vehicle-img-placeholder i { color:var(--muted);font-size:1.5rem; }
        .vehicle-name { font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:700;color:var(--text); }
        .vehicle-price { font-size:.85rem;color:var(--muted);margin-top:.15rem; }

        /* Detail rows */
        .detail-row { display:flex;justify-content:space-between;align-items:center;padding:.6rem 0;border-bottom:1px solid var(--bg); }
        .detail-row:last-child { border-bottom:none; }
        .detail-label { font-size:.85rem;color:var(--muted);display:flex;align-items:center;gap:.5rem; }
        .detail-label i { width:16px;color:var(--sage); }
        .detail-value { font-size:.9rem;font-weight:600;color:var(--text); }
        .total-row { background:var(--bg);border-radius:10px;padding:.8rem 1rem;display:flex;justify-content:space-between;align-items:center;margin-top:.5rem; }
        .total-label { font-size:.9rem;font-weight:600;color:var(--muted); }
        .total-value { font-size:1.3rem;font-weight:700;color:var(--green);font-family:'Outfit',sans-serif; }

        /* Status badge */
        .status-pill { display:inline-flex;align-items:center;gap:.4rem;background:#e8f4ec;border:1px solid #88B49A;border-radius:20px;padding:.3rem .85rem;font-size:.82rem;font-weight:600;color:#3d6b47; }

        /* Buttons */
        .btn-green { background:var(--green);color:#fff;border:none;border-radius:12px;padding:.7rem 1.5rem;font-weight:600;font-size:.95rem;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s; }
        .btn-green:hover { background:var(--sage);color:#fff; }
        .btn-outline { background:none;color:var(--green);border:1.5px solid var(--light);border-radius:12px;padding:.7rem 1.5rem;font-weight:600;font-size:.95rem;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s; }
        .btn-outline:hover { background:var(--bg);color:var(--green); }
    </style>
</head>
<body>
<?php include('navigacija.php'); ?>

<div class="page-wrap">
    <!-- Header -->
    <div class="text-center mb-4">
        <div class="check-circle"><i class="fas fa-check"></i></div>
        <div class="conf-title">Rezervacija potvrđena!</div>
        <div class="conf-sub">
            Hvala! Tvoja rezervacija je uspješno kreirana.<br>
            <span class="conf-id">Rezervacija #<?= $r['IDRezervacija'] ?></span>
        </div>
    </div>

    <!-- Vozilo -->
    <div class="conf-card">
        <div class="conf-card-header"><h6><i class="fas fa-car me-2"></i>Vozilo</h6></div>
        <div class="conf-card-body">
            <div class="vehicle-row">
                <?php if ($r['PutanjaSlike']): ?>
                    <img src="<?= htmlspecialchars($r['PutanjaSlike']) ?>" class="vehicle-img" alt="vozilo">
                <?php else: ?>
                    <div class="vehicle-img-placeholder"><i class="fas fa-car"></i></div>
                <?php endif; ?>
                <div>
                    <div class="vehicle-name"><?= htmlspecialchars($r['VoziloNaziv'] . ' ' . $r['VoziloModel']) ?></div>
                    <div class="vehicle-price"><?= number_format($r['CijenaKoristenjaDnevno'], 2) ?> €/dan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalji rezervacije -->
    <div class="conf-card">
        <div class="conf-card-header"><h6><i class="fas fa-calendar-check me-2"></i>Detalji rezervacije</h6></div>
        <div class="conf-card-body">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar-plus"></i>Od</span>
                <span class="detail-value"><?= $start->format('d.m.Y H:i') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar-minus"></i>Do</span>
                <span class="detail-value"><?= $end->format('d.m.Y H:i') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-clock"></i>Trajanje</span>
                <span class="detail-value"><?= $days ?> <?= $days === 1 ? 'dan' : 'dana' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-info-circle"></i>Status</span>
                <span class="detail-value">
                    <span class="status-pill"><i class="fas fa-circle" style="font-size:.5rem;"></i>Rezervirano</span>
                </span>
            </div>
            <div class="total-row">
                <span class="total-label">Ukupno za platiti</span>
                <span class="total-value"><?= number_format($r['UkupnaCijena'], 2) ?> €</span>
            </div>
        </div>
    </div>

    <!-- Korisnik -->
    <div class="conf-card">
        <div class="conf-card-header"><h6><i class="fas fa-user me-2"></i>Korisnik</h6></div>
        <div class="conf-card-body">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user"></i>Ime</span>
                <span class="detail-value"><?= htmlspecialchars($r['ImeKorisnika'] . ' ' . $r['PrezimeKorisnika']) ?></span>
            </div>
            <?php if ($r['KontaktKorisnika']): ?>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-envelope"></i>Email</span>
                <span class="detail-value"><?= htmlspecialchars($r['KontaktKorisnika']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Akcije -->
    <div class="d-flex gap-2 flex-wrap">
        <?php if (isUser()): ?>
            <a href="moj_profil.php" class="btn-green"><i class="fas fa-list"></i>Moje rezervacije</a>
        <?php endif; ?>
        <a href="index.php" class="btn-outline"><i class="fas fa-car"></i>Rezerviraj još</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>