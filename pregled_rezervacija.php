<!doctype html>
<?php require_once __DIR__ . "/auth.php"; requireAdmin(); ?>
<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Pregled rezervacija</title>
    <style>
        /* ===== MODERN PASTEL PALETTE ===== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-primary: #F5F7F4;
            --bg-secondary: #E8EDE7;
            --text-primary: #3d4a3e;
            --text-secondary: #6B7B6E;
            --accent-green: #68896B;
            --accent-sage: #8FA67E;
            --accent-light: #C8D5B9;
            --accent-taupe: #A0937D;
            --white: #ffffff;
            --status-active: #88B49A;
            --status-completed: #D98B8B;
            --status-cancelled: #C48B7C;
            --status-reserved: #D4A574;
        }

        body {
            background-color: var(--bg-secondary);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
        }

        .container-fluid {
            max-width: 1400px;
            padding: 2rem;
        }

        /* Alerts */
        .alert-success {
            background-color: var(--accent-light);
            border: 1px solid var(--accent-sage);
            color: var(--text-primary);
            border-radius: 12px;
        }
        .alert-danger {
            background-color: #f4ddd4;
            border: 1px solid var(--status-cancelled);
            color: var(--text-primary);
            border-radius: 12px;
        }

        /* Header */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Statistics Cards */
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--accent-light);
            transition: all 0.3s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .stat-card h6 {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }
        .stat-card h2 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            margin: 0 0 0.5rem 0;
        }
        .stat-card .stat-subtext {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .stat-card .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
        }
        .stat-card-1 { border-left: 4px solid var(--status-active); }
        .stat-card-2 { border-left: 4px solid var(--status-completed); }
        .stat-card-3 { border-left: 4px solid var(--accent-sage); }
        .stat-card-4 { border-left: 4px solid var(--accent-taupe); }

        /* Status Badges */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .status-aktivna {
            background-color: var(--status-active);
            color: white;
        }
        .status-zavrsena {
            background-color: var(--status-completed);
            color: white;
        }
        .status-otkazana {
            background-color: var(--status-cancelled);
            color: white;
        }
        .status-rezervirano {
            background-color: var(--status-reserved);
            color: white;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid var(--accent-light);
            margin-bottom: 2rem;
        }
        .filter-section .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .filter-section .form-select,
        .filter-section .form-control {
            background: var(--bg-primary);
            border: 1px solid var(--accent-light);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            color: var(--text-primary);
        }
        .filter-section .form-select:focus,
        .filter-section .form-control:focus {
            border-color: var(--accent-sage);
            box-shadow: 0 0 0 4px rgba(143, 166, 126, 0.1);
        }
        .filter-section .btn-secondary {
            background-color: var(--bg-secondary);
            border: none;
            color: var(--text-primary);
        }
        /* Buttons */
        .btn-primary {
            background-color: var(--accent-green);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: var(--accent-sage);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background-color: var(--bg-secondary);
            border: none;
            color: var(--text-primary);
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-secondary:hover {
            background-color: var(--accent-light);
        }
        

        /* Table Card */
        .card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--accent-light);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .table {
            margin: 0;
            color: var(--text-primary);
        }
        .table thead {
            background-color: var(--bg-secondary);
            border-bottom: 2px solid var(--accent-light);
        }
        .table thead th {
            padding: 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border: none;
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e8ede7;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background-color: #fafbfa;
        }

        /* Action Buttons */
        .action-btns .btn {
            margin-right: 0.25rem;
            border-radius: 8px;
            padding: 0.4rem 0.7rem;
        }
        .btn-outline-primary {
            color: var(--accent-green);
            border-color: var(--accent-light);
        }
        .btn-outline-primary:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            color: white;
        }
        .btn-outline-danger {
            color: var(--status-cancelled);
            border-color: var(--accent-light);
        }
        .btn-outline-danger:hover {
            background-color: var(--status-cancelled);
            border-color: var(--status-cancelled);
            color: white;
        }
        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--accent-light);
        }
        .btn-outline-secondary:hover {
            background-color: var(--text-secondary);
            border-color: var(--text-secondary);
            color: white;
        }

        .badge-duration {
            background-color: var(--accent-sage);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 500;
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
    <div class="container-fluid mt-4">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1>Pregled rezervacija</h1>
        </div>

       
<?php
        include("db__connection.php");
        
        // Auto-update reservation statuses and sync vozila.Raspolozivost

        // 1. Past reservations → Zavrsena, vehicle → Dostupno
        mysqli_query($db, "UPDATE rezervacije r
                           JOIN vozila v ON r.VoziloID = v.IDVozilo
                           SET r.StatusRezervacije = 'Zavrsena',
                               v.Raspolozivost = 'Dostupno'
                           WHERE LOWER(r.StatusRezervacije) IN ('aktivna','rezervirano')
                           AND r.DatumZavrsetka < NOW()");

        // 2. Future reservations → Rezervirano, vehicle → Rezervirano
        mysqli_query($db, "UPDATE rezervacije r
                           JOIN vozila v ON r.VoziloID = v.IDVozilo
                           SET r.StatusRezervacije = 'Rezervirano',
                               v.Raspolozivost = 'Rezervirano'
                           WHERE LOWER(r.StatusRezervacije) = 'aktivna'
                           AND r.DatumPocetka > NOW()");

        // 3. Ongoing reservations → Aktivna, vehicle → Nije dostupno
        mysqli_query($db, "UPDATE rezervacije r
                           JOIN vozila v ON r.VoziloID = v.IDVozilo
                           SET r.StatusRezervacije = 'Aktivna',
                               v.Raspolozivost = 'Nije dostupno'
                           WHERE LOWER(r.StatusRezervacije) = 'rezervirano'
                           AND NOW() BETWEEN r.DatumPocetka AND r.DatumZavrsetka");

        // Get statistics - FIXED: Removed special character from column alias
        $statsQuery = "SELECT 
            COUNT(CASE WHEN StatusRezervacije = 'Aktivna' THEN 1 END) as AktivneRezervacije,
            COALESCE(SUM(CASE WHEN StatusRezervacije = 'Aktivna' THEN UkupnaCijena END), 0) as AktivnaVrijednost,
            COUNT(CASE WHEN StatusRezervacije = 'Zavrsena' THEN 1 END) as ZavrseneRezervacije,
            COALESCE(SUM(CASE WHEN StatusRezervacije = 'Zavrsena' THEN UkupnaCijena END), 0) as ZavrsenaProdaja,
            COALESCE(SUM(UkupnaCijena), 0) as UkupniPrihod,
            COALESCE(AVG(UkupnaCijena), 0) as ProsjecnaCijena,
            COUNT(CASE WHEN StatusRezervacije = 'Rezervirano' THEN 1 END) as PredstojeceRezervacije
        FROM rezervacije";
        $statsResult = mysqli_query($db, $statsQuery);
        
        if ($statsResult) {
            $stats = mysqli_fetch_assoc($statsResult);
        } else {
            $stats = [
                'AktivneRezervacije' => 0,
                'AktivnaVrijednost' => 0,
                'ZavrseneRezervacije' => 0,
                'ZavrsenaProdaja' => 0,
                'UkupniPrihod' => 0,
                'ProsjecnaCijena' => 0,
                'PredstojeceRezervacije' => 0
            ];
        }
        ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-1">
                    <div class="card-body">
                        <h6>Aktivne rezervacije</h6>
                        <h2><?= $stats['AktivneRezervacije'] ?? 0 ?></h2>
                        <span class="badge status-aktivna">
                            <?= number_format($stats['AktivnaVrijednost'] ?? 0, 2) ?> €
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-2">
                    <div class="card-body">
                        <h6>Završene rezervacije</h6>
                        <h2><?= $stats['ZavrseneRezervacije'] ?? 0 ?></h2>
                        <span class="badge status-zavrsena">
                            <?= number_format($stats['ZavrsenaProdaja'] ?? 0, 2) ?> €
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-3">
                    <div class="card-body">
                        <h6>Ukupan prihod</h6>
                        <h2><?= number_format($stats['UkupniPrihod'] ?? 0, 2) ?> €</h2>
                        <small class="stat-subtext">Prosjek: <?= number_format($stats['ProsjecnaCijena'] ?? 0, 2) ?> €</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-4">
                    <div class="card-body">
                        <h6>Predstojeće rezervacije</h6>
                        <h2><?= $stats['PredstojeceRezervacije'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
        </div>

      
         <div class="filter-section">
    <form method="get" action="">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">Svi statusi</option>
                    <option value="Aktivna"     <?= (isset($_GET['status']) && $_GET['status'] == 'Aktivna')     ? 'selected' : '' ?>>Aktivna</option>
                    <option value="Rezervirano" <?= (isset($_GET['status']) && $_GET['status'] == 'Rezervirano') ? 'selected' : '' ?>>Rezervirano</option>
                    <option value="Zavrsena"    <?= (isset($_GET['status']) && $_GET['status'] == 'Zavrsena')    ? 'selected' : '' ?>>Završena</option>
                    <option value="Otkazana"    <?= (isset($_GET['status']) && $_GET['status'] == 'Otkazana')    ? 'selected' : '' ?>>Otkazana</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Datum od</label>
                <input type="date" class="form-control" name="date_from" value="<?= isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Datum do</label>
                <input type="date" class="form-control" name="date_to" value="<?= isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sortiraj po</label>
                <select class="form-select" name="sort_by">
                    <option value="">Zadano (datum ↓)</option>
                    <option value="date_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'date_asc') ? 'selected' : '' ?>>Datum ↑</option>
                    <option value="date_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'date_desc') ? 'selected' : '' ?>>Datum ↓</option>
                    <option value="price_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_asc') ? 'selected' : '' ?>>Cijena ↑</option>
                    <option value="price_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_desc') ? 'selected' : '' ?>>Cijena ↓</option>
                    <option value="customer_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'customer_asc') ? 'selected' : '' ?>>Korisnik A-Z</option>
                    <option value="customer_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'customer_desc') ? 'selected' : '' ?>>Korisnik Z-A</option>
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2" style="background-color: #3d4a3e; border-color: #3d4a3e; color: white;">
                    <i class="fas fa-filter me-1"></i> Filtriraj
                </button>
                <a href="pregled_rezervacija.php" class="btn btn-secondary">
                    <i class="fas fa-redo me-1"></i> 
                </a>
            </div>
        </div>
    </form>
</div>


        <!-- Reservations Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Korisnik</th>
                                <th>Vozilo</th>
                                <th>Datum rezervacije</th>
                                <th>Period</th>
                                <th>Trajanje</th>
                                <th>Cijena</th>
                                <th>Status</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    // Provjeri da li je konekcija otvorena
                    if (!isset($db) || !$db) {
                        include("db__connection.php");
                    }
                    
                    // Build query with filters
                    $query = "SELECT 
                        r.IDRezervacija,
                        k.ImeKorisnika,
                        k.PrezimeKorisnika,
                        v.Naziv AS VoziloNaziv,
                        v.Model AS VoziloModel,
                        r.DatumRezervacije,
                        r.DatumPocetka,
                        r.DatumZavrsetka,
                        r.UkupnaCijena,
                        r.StatusRezervacije,
                        v.Raspolozivost
                    FROM rezervacije r
                    JOIN korisnici k ON r.KorisnikID = k.IDKorisnici
                    JOIN vozila v ON r.VoziloID = v.IDVozilo";
                    
                    $conditions = [];
                    $params = [];
                    $types = '';
                    
                    if (!empty($_GET['status'])) {
                        $conditions[] = "r.StatusRezervacije = ?";
                        $params[] = $_GET['status'];
                        $types .= 's';
                    }
                    
                    if (!empty($_GET['date_from'])) {
                        $conditions[] = "r.DatumPocetka >= ?";
                        $params[] = $_GET['date_from'];
                        $types .= 's';
                    }
                    
                    if (!empty($_GET['date_to'])) {
                        $conditions[] = "r.DatumZavrsetka <= ?";
                        $params[] = $_GET['date_to'];
                        $types .= 's';
                    }
                    
                    if (!empty($conditions)) {
                        $query .= " WHERE " . implode(" AND ", $conditions);
                    }
                    
                    // Add sorting
                    $sort_by = $_GET['sort_by'] ?? '';
                    switch ($sort_by) {
                        case 'date_asc':
                            $query .= " ORDER BY r.DatumPocetka ASC";
                            break;
                        case 'date_desc':
                            $query .= " ORDER BY r.DatumPocetka DESC";
                            break;
                        case 'price_asc':
                            $query .= " ORDER BY r.UkupnaCijena ASC";
                            break;
                        case 'price_desc':
                            $query .= " ORDER BY r.UkupnaCijena DESC";
                            break;
                        case 'customer_asc':
                            $query .= " ORDER BY k.ImeKorisnika ASC, k.PrezimeKorisnika ASC";
                            break;
                        case 'customer_desc':
                            $query .= " ORDER BY k.ImeKorisnika DESC, k.PrezimeKorisnika DESC";
                            break;
                        default:
                            $query .= " ORDER BY r.DatumPocetka DESC";
                    }
                    
                    $stmt = mysqli_prepare($db, $query);
                    
                    if ($stmt) {
                        if (!empty($params)) {
                            mysqli_stmt_bind_param($stmt, $types, ...$params);
                        }
                        
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $startDate = new DateTime($row['DatumPocetka']);
                                    $endDate = new DateTime($row['DatumZavrsetka']);
                                    $duration = $startDate->diff($endDate)->days;
                                    
                                    $formattedStartDate = $startDate->format('d.m.Y');
                                    $formattedEndDate = $endDate->format('d.m.Y');
                                    
                                    // Determine status class and label
                                    $statusVal = $row['StatusRezervacije'];
                                    $statusClass = strtolower($statusVal);
                                    $statusLabels = [
                                        'Aktivna'     => 'Aktivna',
                                        'Rezervirano' => 'Rezervirano',
                                        'Zavrsena'    => 'Završena',
                                        'Otkazana'    => 'Otkazana',
                                    ];
                                    $statusLabel = $statusLabels[$statusVal] ?? ucfirst($statusVal);
                                    
                                    echo "<tr>
                                        <td>{$row['IDRezervacija']}</td>
                                        <td>{$row['ImeKorisnika']} {$row['PrezimeKorisnika']}</td>
                                        <td>{$row['VoziloNaziv']} {$row['VoziloModel']}</td>
                                        <td>" . date('d.m.Y', strtotime($row['DatumRezervacije'])) . "</td>
                                        <td>{$formattedStartDate} - {$formattedEndDate}</td>
                                        <td>{$duration} dana</td>
                                        <td>" . number_format($row['UkupnaCijena'], 2) . " €</td>
                                        <td>
                                            <span class='status-badge status-{$statusClass}'>
                                                {$statusLabel}
                                            </span>
                                        </td>
                                        <td class='action-btns'>";
                                    
                                    // Edit button for active or reserved reservations
                                    if (in_array($statusVal, ['Aktivna', 'Rezervirano'])) {
                                        $voziloInfo   = htmlspecialchars(addslashes($row['VoziloNaziv'] . ' ' . $row['VoziloModel']));
                                        $korisnikInfo = htmlspecialchars(addslashes($row['ImeKorisnika'] . ' ' . $row['PrezimeKorisnika']));
                                        $odKada       = date('Y-m-d\TH:i', strtotime($row['DatumPocetka']));
                                        $doKada       = date('Y-m-d\TH:i', strtotime($row['DatumZavrsetka']));
                                        $cijena       = number_format($row['UkupnaCijena'], 2, '.', '');
                                        echo "<button class='btn btn-sm btn-outline-primary' title='Uredi'
                                                onclick=\"openEditModal(
                                                    {$row['IDRezervacija']},
                                                    '{$voziloInfo}',
                                                    '{$korisnikInfo}',
                                                    '{$odKada}',
                                                    '{$doKada}',
                                                    '{$cijena}',
                                                    '{$statusVal}'
                                                )\">
                                                <i class='fas fa-edit'></i>
                                              </button> ";
                                    }
                                    
                                    // Cancel button for active/reserved, delete button for completed/cancelled
                                    if (in_array($statusVal, ['Aktivna', 'Rezervirano'])) {
                                        echo "<form method='POST' action='otkazi_rezervaciju.php' style='display:inline;'
                                                onsubmit=\"return confirm('Jeste li sigurni da želite otkazati ovu rezervaciju?')\">
                                                <input type='hidden' name='id' value='{$row['IDRezervacija']}'>
                                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($csrf) . "'>
                                                <button type='submit' class='btn btn-sm btn-outline-danger' title='Otkaži'>
                                                    <i class='fas fa-ban'></i>
                                                </button>
                                              </form>";
                                    } elseif (in_array($statusVal, ['Zavrsena', 'Otkazana'])) {
                                        echo "<form method='POST' action='obrisi_rezervaciju.php' style='display:inline;'
                                                onsubmit=\"return confirm('Jeste li sigurni da želite trajno obrisati ovu rezervaciju?')\">
                                                <input type='hidden' name='id' value='{$row['IDRezervacija']}'>
                                                <input type='hidden' name='csrf_token' value='" . htmlspecialchars($csrf) . "'>
                                                <button type='submit' class='btn btn-sm btn-outline-danger' title='Obriši'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </button>
                                              </form>";
                                    } else {
                                        echo "<span class='text-muted'>-</span>";
                                    }
                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' class='text-center py-4'>Nema pronađenih rezervacija</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center text-danger'>Greška pri izvršavanju upita</td></tr>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<tr><td colspan='9' class='text-center text-danger'>Greška u pripremi upita</td></tr>";
                    }
                    
                    // Zatvori konekciju na kraju
                    if (isset($db) && $db) {
                        mysqli_close($db);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Reservation Modal -->
    <div class="modal fade" id="editRezervacijaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:1px solid #C8D5B9;">
                <form action="uredi_rezervaciju.php" method="POST">
                    <input type="hidden" name="id" id="editRezId">
                    <div class="modal-header" style="border-bottom:1px solid #C8D5B9; background:#fff; border-radius:16px 16px 0 0;">
                        <h5 class="modal-title" style="font-family:'Outfit',sans-serif; color:#3d4a3e;">
                            <i class="fas fa-edit me-2" style="color:#8FA67E;"></i>
                            Uredi rezervaciju <span id="editRezTitle" style="color:#8FA67E;"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;">
                        <!-- Info box -->
                        <div style="background:#E8EDE7; border-radius:10px; padding:0.85rem 1rem; border:1px solid #C8D5B9; margin-bottom:1.25rem; font-size:0.9rem; color:#3d4a3e;">
                            <div><i class="fas fa-car me-1" style="color:#8FA67E;"></i> <strong id="editVoziloInfo"></strong></div>
                            <div class="mt-1"><i class="fas fa-user me-1" style="color:#8FA67E;"></i> <span id="editKorisnikInfo"></span></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Od kada <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="odKada" id="editOdKada" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Do kada <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="doKada" id="editDoKada" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Ukupna cijena (€)</label>
                                <input type="number" step="0.01" class="form-control" name="ukupnaCijena" id="editCijena" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="editStatus" required>
                                    <option value="Aktivna">Aktivna</option>
                                    <option value="Rezervirano">Rezervirano</option>
                                    <option value="Zavrsena">Završena</option>
                                    <option value="Otkazana">Otkazana</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #C8D5B9; background:#fff; border-radius:0 0 16px 16px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
                        <button type="submit" class="btn btn-primary" style="background:#68896B; border:none;">
                            <i class="fas fa-save me-1"></i> Spremi promjene
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);

        function openEditModal(id, vozilo, korisnik, odKada, doKada, cijena, status) {
            document.getElementById('editRezId').value       = id;
            document.getElementById('editRezTitle').textContent = '#' + id;
            document.getElementById('editVoziloInfo').textContent = vozilo;
            document.getElementById('editKorisnikInfo').textContent = korisnik;
            document.getElementById('editOdKada').value     = odKada;
            document.getElementById('editDoKada').value     = doKada;
            document.getElementById('editCijena').value     = cijena;
            document.getElementById('editStatus').value     = status;
            new bootstrap.Modal(document.getElementById('editRezervacijaModal')).show();
        }
    </script>
</body>
</html>