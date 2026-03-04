<!DOCTYPE html>
<?php require_once __DIR__ . '/auth.php'; ?>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Rent-a-Car </title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        :root {
            --bg:       #E8EDE7;
            --white:    #F5F7F4;
            --green:    #68896B;
            --sage:     #8FA67E;
            --light:    #C8D5B9;
            --text:     #3d4a3e;
            --muted:    #6B7B6E;
            --taupe:    #A0937D;
            --cream:    #F5F7F4;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg);
            font-family: 'Inter', sans-serif;
            color: var(--text);
            margin: 0;
        }

        /* ── HERO ─────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #3d4a3e 0%, #68896B 60%, #8FA67E 100%);
            padding: 5rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            margin-bottom: 1.5rem;
            letter-spacing: 0.3px;
        }
        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }
        .hero h1 em {
            font-style: normal;
            color: #C8D5B9;
        }
        .hero p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.78);
            max-width: 520px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        /* Hero stats */
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            flex-wrap: wrap;
            margin-top: 2.5rem;
            padding-top: 2.5rem;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        .hero-stat { text-align: center; }
        .hero-stat strong {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }
        .hero-stat span {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.65);
            margin-top: 0.25rem;
            display: block;
        }

        /* ── FILTER BAR ───────────────────────────── */
        .filter-bar {
            background: var(--white);
            border-bottom: 1px solid var(--light);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .filter-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .filter-search {
            position: relative;
            flex: 1;
            min-width: 200px;
        }
        .filter-search i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.88rem;
        }
        .filter-search input {
            width: 100%;
            border: 1.5px solid var(--light);
            border-radius: 10px;
            padding: 0.55rem 0.9rem 0.55rem 2.3rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--bg);
            outline: none;
            transition: border-color 0.2s;
        }
        .filter-search input:focus { border-color: var(--sage); }
        .filter-search input::placeholder { color: var(--muted); }

        .filter-btn {
            border: 1.5px solid var(--light);
            background: var(--bg);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.18s;
            white-space: nowrap;
            font-weight: 500;
        }
        .filter-btn:hover, .filter-btn.active {
            border-color: var(--green);
            background: rgba(104,137,107,0.08);
            color: var(--green);
        }
        .filter-btn.active { font-weight: 600; }

        .filter-count {
            font-size: 0.83rem;
            color: var(--muted);
            margin-left: auto;
            white-space: nowrap;
        }
        .filter-count strong { color: var(--green); }

        /* ── MAIN CONTENT ─────────────────────────── */
        .main-wrap {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
        }

        /* ── VEHICLE GRID ─────────────────────────── */
        .vehicle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.75rem;
        }

        /* ── CARD ─────────────────────────────────── */
        .vehicle-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e0e5de;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1), box-shadow 0.28s;
            display: flex;
            flex-direction: column;
        }
        .vehicle-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.1);
        }
        .vehicle-card.hidden { display: none; }

        .card-image-wrapper {
            height: 220px;
            position: relative;
            background: #fff;
            overflow: hidden;
        }
        .vehicle-card-image {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .vehicle-card:hover .vehicle-card-image { transform: scale(1.05); }

        .no-image-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #C8D5B9, #8FA67E);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7);
            font-size: 4rem;
        }

        .status-badge-card {
            position: absolute;
            top: 14px; right: 14px;
            padding: 5px 14px;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.6px;
            border-radius: 20px;
            text-transform: uppercase;
            font-family: 'Outfit', sans-serif;
            backdrop-filter: blur(6px);
        }
        .status-available   { background: rgba(200,213,185,0.95); color: #3d4a3e; }
        .status-reserved    { background: rgba(212,165,116,0.95); color: #fff; }
        .status-unavailable { background: rgba(160,147,125,0.92); color: #fff; }

        /* Card type pill on image */
        .card-type-pill {
            position: absolute;
            bottom: 14px; left: 14px;
            background: rgba(61,74,62,0.75);
            color: rgba(255,255,255,0.9);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.72rem;
            font-weight: 500;
            backdrop-filter: blur(6px);
            letter-spacing: 0.3px;
        }

        .vehicle-card-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .vehicle-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .vehicle-id {
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 1.1rem;
        }

        .spec-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
            margin-bottom: 1.1rem;
        }
        .spec-item {
            background: var(--bg);
            border-radius: 10px;
            padding: 0.6rem 0.8rem;
            border: 1px solid var(--light);
        }
        .spec-label {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 3px;
        }
        .spec-val {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }

        .card-footer-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--light);
        }
        .price-tag {
            font-family: 'Outfit', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--green);
            line-height: 1;
        }
        .price-tag small {
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--muted);
        }

        .btn-reserve {
            background: var(--green);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-reserve:hover:not(:disabled) {
            background: var(--sage);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(104,137,107,0.3);
        }
        .btn-reserve:disabled {
            background: #c5bdb4;
            color: #fff;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .avail-note {
            font-size: 0.78rem;
            color: var(--taupe);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.6rem;
        }

        /* ── EMPTY STATE ─────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--muted);
            grid-column: 1/-1;
        }
        .empty-state i { font-size: 3rem; opacity: 0.25; display: block; margin-bottom: 1rem; }

        /* ── ALERTS ──────────────────────────────── */
        .alert-success {
            background: var(--light);
            border: 1px solid var(--sage);
            color: var(--text);
            border-radius: 12px;
            font-weight: 500;
        }
        .alert-danger {
            background: #f4ddd4;
            border: 1px solid #d4a49a;
            color: #5a3d38;
            border-radius: 12px;
        }

        /* ── MODAL ───────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .modal-header {
            background: var(--white);
            border-bottom: 1px solid var(--light);
            padding: 1.5rem 1.75rem;
        }
        .modal-header .modal-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
        }
        .modal-header .btn-close {
            background-color: var(--bg);
            border-radius: 50%;
            opacity: 1;
        }
        .modal-body {
            background: var(--white);
            padding: 1.5rem 1.75rem;
        }
        .modal-body label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text);
            margin-bottom: 0.4rem;
        }
        .modal-body .form-control, .modal-body .form-select {
            background: var(--bg);
            border: 1.5px solid var(--light);
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            color: var(--text);
            font-size: 0.92rem;
            transition: border-color 0.2s;
        }
        .modal-body .form-control:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(143,166,126,0.12);
            outline: none;
        }
        .modal-footer {
            background: var(--white);
            border-top: 1px solid var(--light);
            padding: 1.25rem 1.75rem;
        }
        .modal-footer .btn { border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600; font-family: 'Outfit', sans-serif; }
        .modal-footer .btn-secondary { background: var(--bg); color: var(--text); border: none; }
        .modal-footer .btn-secondary:hover { background: var(--light); }
        .modal-footer .btn-primary { background: var(--green); color: #fff; border: none; }
        .modal-footer .btn-primary:hover { background: var(--sage); }

        /* Modal vehicle preview strip */
        .modal-vehicle-strip {
            background: linear-gradient(135deg, var(--green), var(--sage));
            padding: 1rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .modal-vehicle-strip i { color: rgba(255,255,255,0.7); font-size: 1.3rem; }
        .modal-vehicle-strip span { color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; }
        .modal-vehicle-strip small { color: rgba(255,255,255,0.75); font-size: 0.82rem; display: block; }

        /* Price preview in modal */
        .price-preview {
            background: var(--bg);
            border: 1.5px solid var(--light);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-preview-label { font-size: 0.85rem; color: var(--muted); font-weight: 500; }
        .price-preview-val { font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 700; color: var(--green); }

        @media (max-width: 700px) {
            .hero { padding: 3.5rem 1.25rem 3rem; }
            .vehicle-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .main-wrap { padding: 1.5rem 1rem 3rem; }
            .hero-stats { gap: 1.5rem; }
            .filter-bar { padding: 0.75rem 1rem; }
        }
        
        .container-fluid {
            max-width: 1400px;
            padding: 2rem;
        }
  .navbar {
            background-color: #F5F7F4 !important;
            border-bottom: 1px solid #C8D5B9;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            padding: 1rem 2rem;
        }
        .navbar .navbar-brand, .navbar .nav-link {
            color: #3d4a3e !important;
            font-weight: 500;
            letter-spacing: 0.3px;
            font-family: 'Outfit', sans-serif;
        }
        .navbar .nav-link:hover {
            color: #68896B !important;
        }
    </style>
</head>
<body>

<?php include("navigacija.php"); ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger text-center mx-3 mt-3">
    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_GET['error']) ?>
</div>
<?php endif; ?>

<!-- ── HERO ──────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero-badge">
        <i class="fas fa-shield-alt"></i> Pouzdano · Brzo · Povoljno
    </div>
    <h1>Pronađi savršeno<br><em>vozilo za svaku prigodu</em></h1>
    <p>Pregledaj naš asortiman vozila i rezerviraj online za nekoliko sekundi — bez čekanja, bez komplikacija.</p>

    <?php
    include("db__connection.php");
    $stats = mysqli_fetch_assoc(mysqli_query($db, "
        SELECT
            COUNT(*) AS ukupno,
            SUM(Raspolozivost = 'Dostupno') AS dostupno,
            MIN(CijenaKoristenjaDnevno) AS min_cijena
        FROM vozila
    "));
    ?>
    <div class="hero-stats">
        <div class="hero-stat">
            <strong><?= $stats['ukupno'] ?></strong>
            <span>vozila u asortimanu</span>
        </div>
        <div class="hero-stat">
            <strong><?= $stats['dostupno'] ?></strong>
            <span>odmah dostupno</span>
        </div>
        <div class="hero-stat">
            <strong>već od <?= number_format($stats['min_cijena'], 0) ?> €</strong>
            <span>cijena po danu</span>
        </div>
        <div class="hero-stat">
            <strong>24/7</strong>
            <span>online rezervacija</span>
        </div>
    </div>
</section>

<!-- ── FILTER BAR ─────────────────────────────────────────── -->
<?php
$query = "SELECT
    v.IDVozilo, v.Naziv, v.Model, v.TipVozila, v.CijenaKoristenjaDnevno, v.Raspolozivost,
    ka.Godiste, ka.Kilometraza, ka.Registracija,
    vs.PutanjaSlike,
    CASE WHEN EXISTS (
        SELECT 1 FROM rezervacije r
        WHERE r.VoziloID = v.IDVozilo
        AND LOWER(r.StatusRezervacije) = 'aktivna'
        AND r.DatumPocetka <= NOW() AND r.DatumZavrsetka >= NOW()
    ) THEN 1 ELSE 0 END AS ImaAktivnuRezervaciju,
    (SELECT MIN(r2.DatumPocetka) FROM rezervacije r2
     WHERE r2.VoziloID = v.IDVozilo
     AND LOWER(r2.StatusRezervacije) IN ('aktivna','rezervirano')
     AND r2.DatumPocetka > NOW()
    ) AS SljedeciPocetakRezervacije
FROM vozila v
JOIN karakteristike_automobila ka ON v.IDVozilo = ka.VoziloID
LEFT JOIN vozila_slike vs ON v.IDVozilo = vs.VoziloID AND vs.JeGlavna = 1
ORDER BY v.Raspolozivost = 'Dostupno' DESC, v.IDVozilo";
$result = mysqli_query($db, $query) or die("Greška: " . mysqli_error($db));
$vehicles = [];
while ($row = mysqli_fetch_assoc($result)) { $vehicles[] = $row; }
$totalCount = count($vehicles);
?>

<div class="filter-bar">
    <div class="filter-inner">
        <div class="filter-search">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Pretraži po nazivu, modelu...">
        </div>
        <button class="filter-btn active" data-filter="sve">Sva vozila</button>
        <button class="filter-btn" data-filter="dostupno">
            <i class="fas fa-circle" style="font-size:0.5rem; color:#68896B; vertical-align:middle;"></i>
            Dostupna
        </button>
        <button class="filter-btn" data-filter="rezervirano">Rezervirana</button>
        <button class="filter-btn" data-filter="nedostupno">Nedostupna</button>
        <span class="filter-count">
            <strong id="visibleCount"><?= $totalCount ?></strong> / <?= $totalCount ?> vozila
        </span>
    </div>
</div>

<!-- ── GRID ───────────────────────────────────────────────── -->
<div class="main-wrap">
    <div class="vehicle-grid" id="vehicleGrid">

    <?php foreach ($vehicles as $row):
        $isUnavailable = ($row['Raspolozivost'] == 'Nije dostupno') || $row['ImaAktivnuRezervaciju'];
        $isReserved    = !$isUnavailable && !empty($row['SljedeciPocetakRezervacije']);
        $isAvailable   = !$isUnavailable && !$isReserved;

        if ($isUnavailable)    { $statusText = 'Nedostupno';  $statusClass = 'status-unavailable'; $filterVal = 'nedostupno'; }
        elseif ($isReserved)   { $statusText = 'Rezervirano'; $statusClass = 'status-reserved';    $filterVal = 'rezervirano'; }
        else                   { $statusText = 'Dostupno';    $statusClass = 'status-available';   $filterVal = 'dostupno'; }

        $maxDate = '';
        if (!empty($row['SljedeciPocetakRezervacije'])) {
            $maxDate = date('Y-m-d\TH:i', strtotime($row['SljedeciPocetakRezervacije']) - 60);
        }
        $searchKey = strtolower($row['Naziv'] . ' ' . $row['Model'] . ' ' . ($row['TipVozila'] ?? ''));
    ?>
    <div class="vehicle-card"
         data-id="<?= $row['IDVozilo'] ?>"
         data-name="<?= htmlspecialchars($row['Naziv'].' '.$row['Model']) ?>"
         data-price="<?= $row['CijenaKoristenjaDnevno'] ?>"
         data-available="<?= $isAvailable ? '1' : '0' ?>"
         data-max-date="<?= htmlspecialchars($maxDate) ?>"
         data-filter="<?= $filterVal ?>"
         data-search="<?= htmlspecialchars($searchKey) ?>">

        <!-- Image -->
        <div class="card-image-wrapper">
            <div class="status-badge-card <?= $statusClass ?>"><?= $statusText ?></div>
            <?php if ($row['TipVozila']): ?>
            <div class="card-type-pill"><?= htmlspecialchars($row['TipVozila']) ?></div>
            <?php endif; ?>
            <?php if ($row['PutanjaSlike']): ?>
                <img src="<?= htmlspecialchars($row['PutanjaSlike']) ?>" class="vehicle-card-image" alt="<?= htmlspecialchars($row['Naziv']) ?>">
            <?php else: ?>
                <div class="no-image-placeholder"><i class="fas fa-car"></i></div>
            <?php endif; ?>
        </div>

        <!-- Body -->
        <div class="vehicle-card-body">
            <div class="vehicle-name"><?= htmlspecialchars($row['Naziv'].' '.$row['Model']) ?></div>
            <div class="vehicle-id">Vozilo #<?= $row['IDVozilo'] ?></div>

            <div class="spec-grid">
                <div class="spec-item">
                    <div class="spec-label">Godište</div>
                    <div class="spec-val"><?= htmlspecialchars($row['Godiste']) ?></div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Kilometraža</div>
                    <div class="spec-val"><?= number_format($row['Kilometraza'], 0, ',', '.') ?> km</div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Registracija</div>
                    <div class="spec-val"><?= htmlspecialchars($row['Registracija']) ?></div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Tip</div>
                    <div class="spec-val"><?= htmlspecialchars($row['TipVozila'] ?? '—') ?></div>
                </div>
            </div>

            <?php if ($isReserved && !empty($row['SljedeciPocetakRezervacije'])): ?>
            <div class="avail-note">
                <i class="fas fa-clock"></i>
                Rezervirano od <?= date('d.m.Y H:i', strtotime($row['SljedeciPocetakRezervacije'])) ?>
            </div>
            <?php endif; ?>

            <div class="card-footer-row">
                <div>
                    <div class="price-tag">
                        €<?= number_format($row['CijenaKoristenjaDnevno'], 2, ',', '.') ?>
                        <small>/dan</small>
                    </div>
                </div>
                <button class="btn-reserve" <?= $isUnavailable ? 'disabled' : '' ?>>
                    <?php if ($isUnavailable): ?>
                        <i class="fas fa-ban me-1"></i> Nedostupno
                    <?php else: ?>
                        <i class="fas fa-calendar-check me-1"></i> Rezerviraj
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="empty-state" id="emptyState" style="display:none;">
        <i class="fas fa-car"></i>
        <p>Nema vozila koja odgovaraju pretrazi.</p>
    </div>

    </div><!-- /vehicle-grid -->
</div><!-- /main-wrap -->

<!-- ── MODAL ─────────────────────────────────────────────── -->
<div class="modal fade" id="addReservationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Vehicle strip -->
      <div class="modal-vehicle-strip" id="modalVehicleStrip">
          <i class="fas fa-car"></i>
          <div>
              <span id="modalVehicleName">—</span>
              <small id="modalVehiclePrice">—</small>
          </div>
      </div>

      <div class="modal-header" style="border-radius:0;">
        <h5 class="modal-title"><i class="fas fa-calendar-alt me-2" style="color:#8FA67E;"></i>Nova rezervacija</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="addReservationForm" action="dodaj_rezervaciju.php" method="POST">
      <div class="modal-body">
          <input type="hidden" id="selectedVehicleId" name="voziloID">

          <?php if (isUser()): ?>
          <div style="background:#E8EDE7;border-radius:10px;padding:0.8rem 1rem;border:1px solid #C8D5B9;margin-bottom:1rem;font-size:0.88rem;color:#3d4a3e;display:flex;align-items:center;gap:0.6rem;">
              <span style="width:30px;height:30px;border-radius:50%;background:#68896B;color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;font-family:'Outfit',sans-serif;flex-shrink:0;">
                  <?= strtoupper(substr($_SESSION['account_ime'],0,1).substr($_SESSION['account_prezime'],0,1)) ?>
              </span>
              <span>Rezervacija za <strong><?= htmlspecialchars(getAccountName()) ?></strong></span>
              <input type="hidden" name="imeKorisnika"     value="<?= htmlspecialchars($_SESSION['account_ime']) ?>">
              <input type="hidden" name="prezimeKorisnika" value="<?= htmlspecialchars($_SESSION['account_prezime']) ?>">
              <input type="hidden" name="emailKorisnika"   value="<?= htmlspecialchars($_SESSION['account_email']) ?>">
          </div>
          <?php else: ?>
          <div class="row mb-2">
              <div class="col-6">
                  <label class="form-label">Ime <span style="color:#C48B7C;">*</span></label>
                  <input type="text" class="form-control" name="imeKorisnika" maxlength="25" >
              </div>
              <div class="col-6">
                  <label class="form-label">Prezime <span style="color:#C48B7C;">*</span></label>
                  <input type="text" class="form-control" name="prezimeKorisnika" maxlength="25" >
              </div>
          </div>
          <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="emailKorisnika" maxlength="100" >
          </div>
          <div style="background:#fff8f0;border:1px solid #D4A574;border-radius:10px;padding:0.65rem 1rem;margin-bottom:1rem;font-size:0.82rem;color:#3d4a3e;">
              <i class="fas fa-info-circle me-1" style="color:#D4A574;"></i>
              <a href="login.php" style="color:#68896B;font-weight:600;">Prijavi se</a> ili
              <a href="register.php" style="color:#68896B;font-weight:600;">registriraj se</a>
              za brže rezervacije u budućnosti.
          </div>
          <?php endif; ?>

          <hr style="border-color:#E8EDE7; margin:1rem 0;">

          <div class="row mb-3">
              <div class="col-6">
                  <label class="form-label">Od kada <span style="color:#C48B7C;">*</span></label>
                  <input type="datetime-local" class="form-control" id="odKada" name="odKada" required>
              </div>
              <div class="col-6">
                  <label class="form-label">Do kada <span style="color:#C48B7C;">*</span></label>
                  <input type="datetime-local" class="form-control" id="doKada" name="doKada" required>
              </div>
          </div>
          <div id="maxDateNote" style="display:none;font-size:0.81rem;color:#A0937D;margin-bottom:0.75rem;background:#fff8f0;border:1px solid #D4A574;border-radius:8px;padding:0.5rem 0.75rem;"></div>

          <div class="price-preview">
              <span class="price-preview-label"><i class="fas fa-receipt me-1"></i>Ukupna cijena</span>
              <span class="price-preview-val" id="ukupnaCijenaDisplay">—</span>
              <input type="hidden" id="ukupnaCijena" name="ukupnaCijena">
              <input type="hidden" id="cijenaKoristenjaDnevno" name="cijenaKoristenjaDnevno">
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check me-1"></i>Potvrdi rezervaciju
        </button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Filter & Search ──────────────────────────────────────
const cards = document.querySelectorAll('.vehicle-card');
const searchInput = document.getElementById('searchInput');
const filterBtns  = document.querySelectorAll('.filter-btn');
const emptyState  = document.getElementById('emptyState');
const visibleCount = document.getElementById('visibleCount');
let activeFilter = 'sve';

function applyFilters() {
    const q = searchInput.value.toLowerCase().trim();
    let count = 0;
    cards.forEach(card => {
        const matchFilter = activeFilter === 'sve' || card.dataset.filter === activeFilter;
        const matchSearch = !q || card.dataset.search.includes(q) || card.dataset.name.toLowerCase().includes(q);
        const show = matchFilter && matchSearch;
        card.classList.toggle('hidden', !show);
        if (show) count++;
    });
    visibleCount.textContent = count;
    emptyState.style.display = count === 0 ? 'block' : 'none';
}

filterBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

searchInput.addEventListener('input', applyFilters);

// ── Reserve button → Modal ───────────────────────────────
document.querySelectorAll('.btn-reserve').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const card  = this.closest('.vehicle-card');
        const price = parseFloat(card.dataset.price);
        const maxDate = card.dataset.maxDate;

        document.getElementById('selectedVehicleId').value = card.dataset.id;
        document.getElementById('cijenaKoristenjaDnevno').value = price;
        document.getElementById('modalVehicleName').textContent = card.dataset.name;
        document.getElementById('modalVehiclePrice').textContent = '€' + price.toFixed(2) + ' / dan';

        const now = new Date();
        const pad = n => String(n).padStart(2,'0');
        const nowStr = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;

        const odEl = document.getElementById('odKada');
        const doEl = document.getElementById('doKada');
        odEl.min = nowStr; odEl.value = '';
        doEl.min = nowStr; doEl.value = '';
        document.getElementById('ukupnaCijenaDisplay').textContent = '—';
        document.getElementById('ukupnaCijena').value = '';

        const note = document.getElementById('maxDateNote');
        if (maxDate) {
            doEl.max = maxDate;
            const d = new Date(maxDate).toLocaleString('hr-HR',{day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'});
            note.textContent = '⚠ Vozilo je rezervirano od ' + d + '. Odabir je ograničen.';
            note.style.display = 'block';
        } else {
            doEl.removeAttribute('max');
            note.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('addReservationModal')).show();
    });
});

// ── Price calculation ────────────────────────────────────
function calcPrice() {
    const dnevna = parseFloat(document.getElementById('cijenaKoristenjaDnevno').value);
    const od = new Date(document.getElementById('odKada').value);
    const do_ = new Date(document.getElementById('doKada').value);
    if (!isNaN(dnevna) && od && do_ && do_ > od) {
        const days = Math.ceil((do_ - od) / 86400000);
        const total = (dnevna * days).toFixed(2);
        document.getElementById('ukupnaCijenaDisplay').textContent = '€' + parseFloat(total).toLocaleString('hr-HR', {minimumFractionDigits:2});
        document.getElementById('ukupnaCijena').value = total;
    } else {
        document.getElementById('ukupnaCijenaDisplay').textContent = '—';
        document.getElementById('ukupnaCijena').value = '';
    }
}
document.getElementById('odKada').addEventListener('change', calcPrice);
document.getElementById('doKada').addEventListener('change', calcPrice);

// ── Form validation ──────────────────────────────────────
document.getElementById('addReservationForm').addEventListener('submit', function(e) {
    const od = new Date(document.getElementById('odKada').value);
    const do_ = new Date(document.getElementById('doKada').value);
    const maxVal = document.getElementById('doKada').max;
    if (od >= do_) {
        alert('Datum završetka mora biti nakon datuma početka!');
        e.preventDefault(); return;
    }
    if (maxVal && do_ > new Date(maxVal)) {
        alert('Datum završetka prelazi sljedeću rezervaciju.');
        e.preventDefault(); return;
    }
});
</script>

</body>
</html>