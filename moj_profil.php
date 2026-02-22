<!doctype html>
<?php
require_once 'auth.php';
if (!isUser()) {
    header('Location: login.php?error=Prijavi+se+za+pristup+profilu');
    exit();
}
include('db__connection.php');

$accountId = intval($_SESSION['account_id']);

$stmt = mysqli_prepare($db, "
    SELECT r.*,
           v.Naziv AS VoziloNaziv, v.Model AS VoziloModel,
           v.CijenaKoristenjaDnevno,
           vs.PutanjaSlike
    FROM rezervacije r
    JOIN vozila v ON r.VoziloID = v.IDVozilo
    LEFT JOIN vozila_slike vs ON v.IDVozilo = vs.VoziloID AND vs.JeGlavna = 1
    WHERE r.AccountID = ?
    ORDER BY r.DatumRezervacije DESC
");
mysqli_stmt_bind_param($stmt, 'i', $accountId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$rezervacije = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rezervacije[] = $row;
}

// Stats
$ukupno    = count($rezervacije);
$aktivne   = count(array_filter($rezervacije, fn($r) => in_array($r['StatusRezervacije'], ['Aktivna', 'Rezervirano'])));
$zavrsene  = count(array_filter($rezervacije, fn($r) => $r['StatusRezervacije'] === 'Zavrsena'));
$potroseno = array_sum(array_column(
    array_filter($rezervacije, fn($r) => $r['StatusRezervacije'] !== 'Otkazana'),
    'UkupnaCijena'
));
?>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moj profil </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root { --bg:#E8EDE7;--white:#fff;--green:#68896B;--sage:#8FA67E;--light:#C8D5B9;--text:#3d4a3e;--muted:#6B7B6E; }

        body { background:var(--bg);font-family:'Inter',sans-serif;min-height:100vh; }
        .page-wrap { max-width:900px;margin:0 auto;padding:2rem 1rem 4rem; }

        /* Hero */
        .profile-hero { background:linear-gradient(135deg,#68896B,#8FA67E);border-radius:20px;padding:2rem;color:#fff;margin-bottom:2rem;display:flex;align-items:center;gap:1.5rem; }
        .avatar-big { width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:1.6rem;font-weight:700;flex-shrink:0;border:3px solid rgba(255,255,255,.4); }
        .hero-name { font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:700;margin:0 0 .2rem; }
        .hero-email { opacity:.85;font-size:.9rem;margin:0; }

        /* Stat cards */
        .stats-row { display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem; }
        .stat-card { background:var(--white);border-radius:16px;border:1px solid var(--light);padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem; }
        .stat-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem; }
        .stat-icon.green  { background:#e8f4ec;color:#68896B; }
        .stat-icon.blue   { background:#e8f0f8;color:#5b82b0; }
        .stat-icon.yellow { background:#fff8e8;color:#b08c2e; }
        .stat-num { font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:700;color:var(--text);line-height:1; }
        .stat-label { font-size:.8rem;color:var(--muted);margin-top:.15rem; }

        /* Section header */
        .section-title { font-family:'Outfit',sans-serif;font-size:1.15rem;font-weight:700;color:var(--text);margin-bottom:1rem;display:flex;align-items:center;gap:.5rem; }
        .section-title i { color:var(--sage); }

        /* Reservation cards */
        .rez-card { background:var(--white);border-radius:16px;border:1px solid var(--light);overflow:hidden;margin-bottom:1rem;transition:box-shadow .2s; }
        .rez-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }
        .rez-card-body { display:flex;gap:1rem;padding:1.25rem; }
        .rez-img { width:90px;height:64px;border-radius:10px;object-fit:cover;border:1px solid var(--light);flex-shrink:0; }
        .rez-img-placeholder { width:90px;height:64px;border-radius:10px;background:var(--bg);border:1px solid var(--light);display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .rez-img-placeholder i { color:var(--muted);font-size:1.6rem; }
        .rez-info { flex:1;min-width:0; }
        .rez-vozilo { font-family:'Outfit',sans-serif;font-weight:700;font-size:1rem;color:var(--text);margin-bottom:.3rem; }
        .rez-dates { font-size:.83rem;color:var(--muted);margin-bottom:.4rem; }
        .rez-dates i { color:var(--sage);margin-right:.3rem; }
        .rez-meta { display:flex;align-items:center;gap:.75rem;flex-wrap:wrap; }
        .rez-price { font-weight:700;color:var(--green);font-size:.95rem; }
        .rez-days { font-size:.8rem;color:var(--muted); }

        /* Status badges */
        .badge-rez   { background:#e8f4ec;color:#3d6b47;border:1px solid #88B49A; }
        .badge-akt   { background:#e8f0f8;color:#2d5a8e;border:1px solid #7badd4; }
        .badge-zav   { background:#f0f0f0;color:#555;border:1px solid #ccc; }
        .badge-otk   { background:#fdecea;color:#a33;border:1px solid #f5a5a5; }
        .status-badge { display:inline-flex;align-items:center;gap:.3rem;border-radius:20px;padding:.2rem .7rem;font-size:.78rem;font-weight:600; }

        /* Empty state */
        .empty-state { text-align:center;padding:4rem 2rem;color:var(--muted); }
        .empty-state i { font-size:3rem;opacity:.3;margin-bottom:1rem;display:block; }
        .empty-state p { margin:0 0 1.5rem;font-size:.95rem; }
        .btn-green { background:var(--green);color:#fff;border:none;border-radius:12px;padding:.65rem 1.4rem;font-weight:600;font-size:.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s; }
        .btn-green:hover { background:var(--sage);color:#fff; }

        @media(max-width:600px){
            .stats-row { grid-template-columns:1fr 1fr; }
            .stats-row .stat-card:last-child { grid-column:span 2; }
            .profile-hero { flex-direction:column;text-align:center; }
        }
    </style>
</head>
<body>
<?php include('navigacija.php'); ?>

<div class="page-wrap">

    <!-- Hero -->
    <div class="profile-hero">
        <div class="avatar-big">
            <?= strtoupper(substr($_SESSION['account_ime'],0,1) . substr($_SESSION['account_prezime'],0,1)) ?>
        </div>
        <div>
            <p class="hero-name"><?= htmlspecialchars($_SESSION['account_ime'] . ' ' . $_SESSION['account_prezime']) ?></p>
            <p class="hero-email"><i class="fas fa-envelope me-1" style="opacity:.7;"></i><?= htmlspecialchars($_SESSION['account_email']) ?></p>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-list"></i></div>
            <div>
                <div class="stat-num"><?= $ukupno ?></div>
                <div class="stat-label">Ukupno rezervacija</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="stat-num"><?= $aktivne ?></div>
                <div class="stat-label">Aktivnih / nadolazećih</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-euro-sign"></i></div>
            <div>
                <div class="stat-num"><?= number_format($potroseno, 0, ',', '.') ?> €</div>
                <div class="stat-label">Ukupna vrijednost rezervacija</div>
            </div>
        </div>
    </div>

    <!-- Rezervacije -->
    <div class="section-title"><i class="fas fa-calendar-alt"></i>Moje rezervacije</div>

    <?php if (empty($rezervacije)): ?>
        <div class="empty-state">
            <i class="fas fa-car"></i>
            <p>Još nemaš rezervacija. Pronađi vozilo i rezerviraj!</p>
            <a href="index.php" class="btn-green"><i class="fas fa-search"></i>Pretraži vozila</a>
        </div>
    <?php else: ?>
        <?php foreach ($rezervacije as $rez):
            $start = new DateTime($rez['DatumPocetka']);
            $end   = new DateTime($rez['DatumZavrsetka']);
            $days  = max(1, $start->diff($end)->days);
            $status = $rez['StatusRezervacije'];
            $badgeClass = match($status) {
                'Rezervirano' => 'badge-rez',
                'Aktivna'     => 'badge-akt',
                'Zavrsena'    => 'badge-zav',
                default       => 'badge-otk',
            };
            $statusLabel = match($status) {
                'Rezervirano' => 'Rezervirano',
                'Aktivna'     => 'Aktivna',
                'Zavrsena'    => 'Završena',
                'Otkazana'    => 'Otkazana',
                default       => $status,
            };
        ?>
        <div class="rez-card">
            <div class="rez-card-body">
                <?php if ($rez['PutanjaSlike']): ?>
                    <img src="<?= htmlspecialchars($rez['PutanjaSlike']) ?>" class="rez-img" alt="vozilo">
                <?php else: ?>
                    <div class="rez-img-placeholder"><i class="fas fa-car"></i></div>
                <?php endif; ?>
                <div class="rez-info">
                    <div class="rez-vozilo"><?= htmlspecialchars($rez['VoziloNaziv'] . ' ' . $rez['VoziloModel']) ?></div>
                    <div class="rez-dates">
                        <i class="fas fa-calendar-plus"></i><?= $start->format('d.m.Y H:i') ?>
                        &nbsp;→&nbsp;
                        <i class="fas fa-calendar-minus"></i><?= $end->format('d.m.Y H:i') ?>
                    </div>
                    <div class="rez-meta">
                        <span class="rez-price"><?= number_format($rez['UkupnaCijena'], 2) ?> €</span>
                        <span class="rez-days"><?= $days ?> <?= $days === 1 ? 'dan' : 'dana' ?></span>
                        <span class="status-badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end justify-content-between" style="flex-shrink:0;">
                    <small style="color:var(--muted);font-size:.75rem;">#<?= $rez['IDRezervacija'] ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>