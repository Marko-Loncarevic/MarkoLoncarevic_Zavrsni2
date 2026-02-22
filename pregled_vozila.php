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
    <title>Pregled vozila</title>
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
            --success: #88B49A;
            --warning: #D4A574;
            --danger: #C48B7C;
        }

        body {
            background-color: var(--bg-secondary) !important;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Navigation - clean and minimal */
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
            border: 1px solid var(--danger);
            color: var(--text-primary);
            border-radius: 12px;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
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
        .stat-card h4 {
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-family: 'Outfit', sans-serif;
        }
        .stat-card h2 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .stat-card .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
        }
        .stat-card-1 { border-left: 4px solid var(--success); }
        .stat-card-2 { border-left: 4px solid var(--accent-sage); }
        .stat-card-3 { border-left: 4px solid var(--accent-taupe); }
        .stat-card-4 { border-left: 4px solid var(--warning); }

        .badge-available {
            background-color: var(--success);
            color: white;
        }
        .badge-unavailable {
            background-color: var(--danger);
            color: white;
        }
        .badge-reserved {
            background-color: var(--warning);
            color: white;
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

        /* Photo Thumbnails */
        .vehicle-photo-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            border: 2px solid var(--accent-light);
            transition: all 0.3s;
        }
        .vehicle-photo-thumbnail:hover {
            border-color: var(--accent-sage);
            transform: scale(1.05);
        }
        .no-photo-placeholder {
            width: 60px;
            height: 60px;
            background: var(--bg-secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            border: 2px solid var(--accent-light);
            cursor: pointer;
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
        .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--accent-light);
        }
        .btn-outline-secondary:hover {
            background-color: var(--accent-sage);
            border-color: var(--accent-sage);
            color: white;
        }
        .btn-outline-danger {
            color: var(--danger);
            border-color: var(--accent-light);
        }
        .btn-outline-danger:hover {
            background-color: var(--danger);
            border-color: var(--danger);
            color: white;
        }
        .btn-outline-info {
            color: var(--accent-sage);
            border-color: var(--accent-light);
        }
        .btn-outline-info:hover {
            background-color: var(--accent-sage);
            border-color: var(--accent-sage);
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
        .filter-section .btn-secondary:hover {
            background-color: var(--accent-light);
        }

        /* Modal */
        .modal-content {
            background-color: var(--bg-primary);
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-bottom: 1px solid var(--accent-light);
            background: var(--white);
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }
        .modal-header .modal-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.5rem;
            font-family: 'Outfit', sans-serif;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-body label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .modal-body .form-control,
        .modal-body .form-select {
            background: var(--white);
            border: 1px solid var(--accent-light);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
        }
        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: var(--accent-sage);
            box-shadow: 0 0 0 4px rgba(143, 166, 126, 0.1);
        }
        .modal-footer {
            border-top: 1px solid var(--accent-light);
            padding: 1.5rem;
            border-radius: 0 0 20px 20px;
            background: var(--white);
        }
        .modal-footer .btn-secondary {
            background: var(--bg-secondary);
            border: none;
            color: var(--text-primary);
        }
        .modal-footer .btn-secondary:hover {
            background: var(--accent-light);
        }

        /* Photo Gallery */
        .photo-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .photo-gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--accent-light);
        }
        .photo-gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .photo-gallery-item .delete-photo {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--danger);
            border: none;
            color: white;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .photo-gallery-item .main-photo-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--success);
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<?php include("navigacija.php"); ?>
    <div class="container-fluid py-4">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1>Pregled vozila</h1>
           <button type="button" class="btn btn-primary" data-bs-toggle="modal" style="background-color: #3d4a3e; border-color: #3d4a3e;color: white;" data-bs-target="#addVehicleModal">
                <i class="fas fa-plus me-2"></i> Dodaj novo vozilo
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <?php
            include("db__connection.php");
            
            $mostRentedQuery = "SELECT v.IDVozilo, v.Naziv, v.Model, 
                              COUNT(r.IDRezervacija) AS BrojRezervacija,
                              SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)) AS UkupnoDana
                              FROM vozila v
                              LEFT JOIN rezervacije r ON v.IDVozilo = r.VoziloID
                              GROUP BY v.IDVozilo, v.Naziv, v.Model
                              ORDER BY BrojRezervacija DESC, UkupnoDana DESC
                              LIMIT 1";
            $mostRentedResult = mysqli_query($db, $mostRentedQuery);
            $mostRented = mysqli_fetch_assoc($mostRentedResult);
            
            $highestEarningQuery = "SELECT v.IDVozilo, v.Naziv, v.Model, 
                                   SUM(r.UkupnaCijena) AS UkupnaZarada
                                   FROM vozila v
                                   LEFT JOIN rezervacije r ON v.IDVozilo = r.VoziloID
                                   GROUP BY v.IDVozilo, v.Naziv, v.Model
                                   ORDER BY UkupnaZarada DESC
                                   LIMIT 1";
            $highestEarningResult = mysqli_query($db, $highestEarningQuery);
            $highestEarning = mysqli_fetch_assoc($highestEarningResult);
            
            $statsQuery = "SELECT 
                          SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)) AS UkupnoDana,
                          SUM(r.UkupnaCijena) AS UkupnaZarada
                          FROM rezervacije r";
            $statsResult = mysqli_query($db, $statsQuery);
            $stats = mysqli_fetch_assoc($statsResult);
            
            $currentDate = date('Y-m-d');
            $rentedQuery = "SELECT COUNT(DISTINCT VoziloID) AS TrenutnoIznajmljeno
                           FROM rezervacije
                           WHERE StatusRezervacije = 'aktivna'";
            $stmt = mysqli_prepare($db, $rentedQuery);
            mysqli_stmt_execute($stmt);
            $rentedResult = mysqli_stmt_get_result($stmt);
            $rentedCount = mysqli_fetch_assoc($rentedResult);
            ?>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-1">
                    <div class="card-body">
                        <h6>Najiznajmljivanije vozilo</h6>
                        <h4>
                            <?= $mostRented ? htmlspecialchars($mostRented['Naziv'].' '.$mostRented['Model']) : 'Nema podataka' ?>
                        </h4>
                        <div>
                            <span class="badge " style="background-color: var(--accent-green);">
                                <?= $mostRented ? $mostRented['BrojRezervacija'].' rez.' : '0 rez.' ?>
                            </span>
                            <span class="badge" style="background-color: var(--accent-sage);">
                                <?= $mostRented ? $mostRented['UkupnoDana'].' dana' : '0 dana' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-2">
                    <div class="card-body">
                        <h6>Najveća zarada od vozila</h6>
                        <h4>
                            <?= $highestEarning ? htmlspecialchars($highestEarning['Naziv'].' '.$highestEarning['Model']) : 'Nema podataka' ?>
                        </h4>
                        <span class="badge badge-available">
                            <?= $highestEarning ? number_format($highestEarning['UkupnaZarada'], 2).' €' : '0.00 €' ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-3">
                    <div class="card-body">
                        <h6>Ukupno iznajmljivanja</h6>
                        <h2><?= $stats['UkupnoDana'] ?? 0 ?> dana</h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card stat-card-4">
                    <div class="card-body">
                        <h6>Trenutno iznajmljeno</h6>
                        <h2><?= $rentedCount['TrenutnoIznajmljeno'] ?? 0 ?> vozila</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="get" action="">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Svi statusi</option>
                            <option value="Dostupno" <?= (isset($_GET['status']) && $_GET['status'] == 'Dostupno') ? 'selected' : '' ?>>Dostupno</option>
                            <option value="Rezervirano" <?= (isset($_GET['status']) && $_GET['status'] == 'Rezervirano') ? 'selected' : '' ?>>Rezervirano</option>
                            <option value="Nije dostupno" <?= (isset($_GET['status']) && $_GET['status'] == 'Nije dostupno') ? 'selected' : '' ?>>Nije dostupno</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tip vozila</label>
                        <select class="form-select" name="tip_vozila">
                            <option value="">Svi tipovi</option>
                            <option value="Gradski" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Gradski') ? 'selected' : '' ?>>Gradski</option>
                            <option value="Kompakt" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Kompakt') ? 'selected' : '' ?>>Kompakt</option>
                            <option value="Limuzina" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Limuzina') ? 'selected' : '' ?>>Limuzina</option>
                            <option value="SUV" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'SUV') ? 'selected' : '' ?>>SUV</option>
                            <option value="Karavan" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Karavan') ? 'selected' : '' ?>>Karavan</option>
                            <option value="Kabriolet" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Kabriolet') ? 'selected' : '' ?>>Kabriolet</option>
                            <option value="Kombi" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Kombi') ? 'selected' : '' ?>>Kombi</option>
                            <option value="Sportski" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Sportski') ? 'selected' : '' ?>>Sportski</option>
                            <option value="Terenac" <?= (isset($_GET['tip_vozila']) && $_GET['tip_vozila'] == 'Terenac') ? 'selected' : '' ?>>Terenac</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cijena od (€)</label>
                        <input type="number" step="0.01" class="form-control" name="price_from" value="<?= $_GET['price_from'] ?? '' ?>" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cijena do (€)</label>
                        <input type="number" step="0.01" class="form-control" name="price_to" value="<?= $_GET['price_to'] ?? '' ?>" placeholder="999.99">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sortiraj po</label>
                        <select class="form-select" name="sort_by">
                            <option value="">Zadano</option>
                            <option value="name_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc') ? 'selected' : '' ?>>Naziv A-Z</option>
                            <option value="name_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc') ? 'selected' : '' ?>>Naziv Z-A</option>
                            <option value="price_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_asc') ? 'selected' : '' ?>>Cijena ↑</option>
                            <option value="price_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_desc') ? 'selected' : '' ?>>Cijena ↓</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #3d4a3e; border-color: #3d4a3e;color: white;">
                            <i class="fas fa-filter me-1"></i> Filtriraj
                        </button>
                        <a href="pregled_vozila.php" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> 
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add Vehicle Modal -->
        <div class="modal fade" id="addVehicleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px; border:1px solid #C8D5B9;">
                    <div class="modal-header" style="border-bottom:1px solid #C8D5B9; background:#fff; border-radius:16px 16px 0 0;">
                        <h5 class="modal-title" style="font-family:'Outfit',sans-serif; color:#3d4a3e;">
                            <i class="fas fa-plus me-2" style="color:#8FA67E;"></i>Dodaj novo vozilo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;">
                        <form id="addVehicleForm" action="dodaj_vozilo.php" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="nazivVozila" class="form-label fw-semibold" style="font-size:0.88rem;">Naziv vozila <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nazivVozila" name="nazivVozila" maxlength="25" required placeholder="npr. Volkswagen"
                                           style="border-radius:10px; border:1px solid #C8D5B9;">
                                </div>
                                <div class="col-6">
                                    <label for="modelVozila" class="form-label fw-semibold" style="font-size:0.88rem;">Model vozila</label>
                                    <input type="text" class="form-control" id="modelVozila" name="modelVozila" maxlength="25" placeholder="npr. Golf 8"
                                           style="border-radius:10px; border:1px solid #C8D5B9;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tipVozila" class="form-label fw-semibold" style="font-size:0.88rem;">Tip vozila</label>
                                <select class="form-select" id="tipVozila" name="tipVozila" style="border-radius:10px; border:1px solid #C8D5B9;">
                                    <option value="Gradski">Gradski</option>
                                    <option value="Kompakt">Kompakt</option>
                                    <option value="Limuzina" selected>Limuzina</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Karavan">Karavan</option>
                                    <option value="Kabriolet">Kabriolet</option>
                                    <option value="Kombi">Kombi</option>
                                    <option value="Sportski">Sportski</option>
                                    <option value="Terenac">Terenac</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cijenaVozila" class="form-label fw-semibold" style="font-size:0.88rem;">Cijena korištenja dnevno <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:10px 0 0 10px; border-color:#C8D5B9; background:#E8EDE7; color:#68896B; font-weight:600;">€</span>
                                    <input type="text" class="form-control" id="cijenaVozila" name="cijenaVozila"
                                           placeholder="0,00" required inputmode="decimal"
                                           pattern="^\d{1,6}([.,]\d{0,2})?$"
                                           title="Unesite iznos u eurima, npr. 45,00"
                                           style="border-radius:0 10px 10px 0; border-color:#C8D5B9;">
                                </div>
                                <div class="invalid-feedback">Unesite ispravnu cijenu (npr. 45,00)</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="godiste" class="form-label fw-semibold" style="font-size:0.88rem;">Godina proizvodnje</label>
                                    <input type="text" class="form-control" id="godiste" name="godiste"
                                           placeholder="npr. 2021" maxlength="4" inputmode="numeric"
                                           style="border-radius:10px; border:1px solid #C8D5B9;">
                                    <div class="invalid-feedback">Unesite ispravnu godinu (npr. 2021)</div>
                                </div>
                                <div class="col-6">
                                    <label for="kilometraza" class="form-label fw-semibold" style="font-size:0.88rem;">Kilometraža</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kilometraza" name="kilometraza"
                                               placeholder="npr. 45000" inputmode="numeric"
                                               style="border-radius:10px 0 0 10px; border-color:#C8D5B9;">
                                        <span class="input-group-text" style="border-radius:0 10px 10px 0; border-color:#C8D5B9; background:#E8EDE7; color:#68896B; font-weight:600;">km</span>
                                    </div>
                                    <div class="invalid-feedback">Unesite broj kilometara (samo brojevi)</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="registracija" class="form-label fw-semibold" style="font-size:0.88rem;">Registracija</label>
                                <input type="text" class="form-control" id="registracija" name="registracija"
                                       placeholder="npr. ZG-1234-AB" maxlength="15"
                                       oninput="this.value = this.value.toUpperCase()"
                                       style="border-radius:10px; border:1px solid #C8D5B9; text-transform:uppercase;">
                                <small style="color:#6B7B6E; font-size:0.8rem;">Format: ZG-1234-AB</small>
                            </div>
                            <div class="mb-1">
                                <label for="vehicle_photo" class="form-label fw-semibold" style="font-size:0.88rem;">Slika vozila</label>
                                <input type="file" class="form-control" id="vehicle_photo" name="vehicle_photo" accept="image/*"
                                       style="border-radius:10px; border:1px solid #C8D5B9;">
                                <small style="color:#6B7B6E; font-size:0.8rem;">JPG, PNG, GIF, WEBP · max 5MB</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #C8D5B9; background:#fff; border-radius:0 0 16px 16px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                style="background:#E8EDE7; border:none; color:#3d4a3e; border-radius:10px;">Odustani</button>
                        <button type="submit" form="addVehicleForm" class="btn btn-primary"
                                style="background:#68896B; border:none; border-radius:10px;">
                            <i class="fas fa-plus me-1"></i> Dodaj vozilo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Gallery Modal -->
        <div class="modal fade" id="photoGalleryModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Galerija slika</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="upload_photo.php" method="POST" enctype="multipart/form-data" class="mb-4">
                            <input type="hidden" id="galleryVehicleId" name="voziloID">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="vehicle_photo" accept="image/*" required>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="jeGlavna" id="jeGlavna">
                                        <label class="form-check-label" for="jeGlavna">Glavna slika</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fas fa-upload"></i> Dodaj sliku
                            </button>
                        </form>
                        <div id="photoGalleryContent" class="photo-gallery-grid"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicles Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Slika</th>
                                <th>ID</th>
                                <th>Naziv</th>
                                <th>Model</th>
                                <th>Tip</th>
                                <th>Cijena/dan</th>
                                <th>Godište</th>
                                <th>Kilometraža</th>
                                <th>Registracija</th>
                                <th>Status</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Sync vozila.Raspolozivost from rezervacije table
                            // 1. Reset all to Dostupno
                            mysqli_query($db, "UPDATE vozila SET Raspolozivost = 'Dostupno'");
                            // 2. Vehicles with an ongoing active reservation → Nije dostupno
                            mysqli_query($db, "UPDATE vozila v
                                               JOIN rezervacije r ON v.IDVozilo = r.VoziloID
                                               SET v.Raspolozivost = 'Nije dostupno'
                                               WHERE LOWER(r.StatusRezervacije) = 'aktivna'
                                               AND NOW() BETWEEN r.DatumPocetka AND r.DatumZavrsetka");
                            // 3. Vehicles with a future reservation (not currently in use) → Rezervirano
                            mysqli_query($db, "UPDATE vozila v
                                               JOIN rezervacije r ON v.IDVozilo = r.VoziloID
                                               SET v.Raspolozivost = 'Rezervirano'
                                               WHERE v.Raspolozivost = 'Dostupno'
                                               AND LOWER(r.StatusRezervacije) IN ('aktivna','rezervirano')
                                               AND r.DatumPocetka > NOW()");

                            $query = "SELECT 
                                v.IDVozilo, v.Naziv, v.Model, v.TipVozila, v.CijenaKoristenjaDnevno, v.Raspolozivost,
                                ka.Godiste, ka.Kilometraza, ka.Registracija,
                                COUNT(r.IDRezervacija) AS BrojRezervacija,
                                SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)) AS UkupnoDana,
                                SUM(r.UkupnaCijena) AS UkupnaZarada,
                                vs.PutanjaSlike AS GlavnaSlika
                            FROM vozila v
                            LEFT JOIN karakteristike_automobila ka ON v.IDVozilo = ka.VoziloID
                            LEFT JOIN rezervacije r ON v.IDVozilo = r.VoziloID
                            LEFT JOIN vozila_slike vs ON v.IDVozilo = vs.VoziloID AND vs.JeGlavna = 1";
                            
                            // Filter
                            $whereClauses = [];
                            $selectedStatus = $_GET['status'] ?? '';
                            if ($selectedStatus !== '') {
                                $whereClauses[] = "v.Raspolozivost = '" . mysqli_real_escape_string($db, $selectedStatus) . "'";
                            }
                            if (!empty($_GET['tip_vozila'])) {
                                $whereClauses[] = "v.TipVozila = '" . mysqli_real_escape_string($db, $_GET['tip_vozila']) . "'";
                            }
                            if (!empty($_GET['price_from'])) {
                                $whereClauses[] = "v.CijenaKoristenjaDnevno >= " . floatval($_GET['price_from']);
                            }
                            if (!empty($_GET['price_to'])) {
                                $whereClauses[] = "v.CijenaKoristenjaDnevno <= " . floatval($_GET['price_to']);
                            }
                            if (!empty($whereClauses)) {
                                $query .= " WHERE " . implode(" AND ", $whereClauses);
                            }
                            
                            $query .= " GROUP BY v.IDVozilo, v.Naziv, v.Model, v.TipVozila, v.CijenaKoristenjaDnevno, 
                                     v.Raspolozivost, ka.Godiste, ka.Kilometraza, ka.Registracija, vs.PutanjaSlike";

                            // Sorting
                            $orderBy = "v.Naziv, v.Model";
                            if (!empty($_GET['sort_by'])) {
                                switch($_GET['sort_by']) {
                                    case 'name_asc':  $orderBy = "v.Naziv ASC, v.Model ASC";  break;
                                    case 'name_desc': $orderBy = "v.Naziv DESC, v.Model DESC"; break;
                                    case 'price_asc': $orderBy = "v.CijenaKoristenjaDnevno ASC"; break;
                                    case 'price_desc':$orderBy = "v.CijenaKoristenjaDnevno DESC"; break;
                                }
                            }
                            $query .= " ORDER BY " . $orderBy;

                            $result = mysqli_query($db, $query) or die("Greška u SQL upitu: " . mysqli_error($db));
                            
                            while ($row = mysqli_fetch_assoc($result)): 
                                $status = $row['Raspolozivost'];
                                if ($status === 'Dostupno') {
                                    $statusClass = 'badge-available';
                                    $statusText  = 'Dostupno';
                                } elseif ($status === 'Rezervirano') {
                                    $statusClass = 'badge-reserved';
                                    $statusText  = 'Rezervirano';
                                } else {
                                    $statusClass = 'badge-unavailable';
                                    $statusText  = 'Nije dostupno';
                                }
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($row['GlavnaSlika']): ?>
                                            <img src="<?= htmlspecialchars($row['GlavnaSlika']) ?>" 
                                                 class="vehicle-photo-thumbnail" 
                                                 alt="Vehicle photo"
                                                 onclick="openPhotoGallery(<?= $row['IDVozilo'] ?>)">
                                        <?php else: ?>
                                            <div class="no-photo-placeholder" onclick="openPhotoGallery(<?= $row['IDVozilo'] ?>)">
                                                <i class="fas fa-camera"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['IDVozilo'] ?></td>
                                    <td>
                                        <?= htmlspecialchars($row['Naziv']) ?>
                                        <?php if ($row['BrojRezervacija'] > 0): ?>
                                            <span class="badge" style="background-color: var(--accent-green); color: white; margin-left: 0.5rem;">
                                                <?= $row['BrojRezervacija'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['Model'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['TipVozila'] ?? 'Limuzina') ?></td>
                                    <td><?= number_format($row['CijenaKoristenjaDnevno'], 2) ?> €</td>
                                    <td><?= htmlspecialchars($row['Godiste'] ?? 'N/A') ?></td>
                                    <td><?= isset($row['Kilometraza']) ? number_format($row['Kilometraza'], 0, ',', '.') . ' km' : 'N/A' ?></td>
                                    <td><?= htmlspecialchars($row['Registracija'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td class="action-btns">
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                onclick="openPhotoGallery(<?= $row['IDVozilo'] ?>)" 
                                                title="Slike">
                                            <i class="fas fa-images"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                title="Uredi"
                                                onclick="openEditVozilo(
                                                    <?= $row['IDVozilo'] ?>,
                                                    '<?= htmlspecialchars(addslashes($row['Naziv'])) ?>',
                                                    '<?= htmlspecialchars(addslashes($row['Model'] ?? '')) ?>',
                                                    <?= floatval($row['CijenaKoristenjaDnevno']) ?>,
                                                    '<?= htmlspecialchars(addslashes($row['Godiste'] ?? '')) ?>',
                                                    <?= intval($row['Kilometraza'] ?? 0) ?>,
                                                    '<?= htmlspecialchars(addslashes($row['Registracija'] ?? '')) ?>',
                                                    '<?= htmlspecialchars($row['Raspolozivost']) ?>'
                                                )">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="obrisi_vozilo.php" style="display:inline;"
                                              onsubmit="return confirm('Jeste li sigurni da želite obrisati ovo vozilo?')">
                                            <input type="hidden" name="id" value="<?= $row['IDVozilo'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Obriši">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <?php if ($row['BrojRezervacija'] > 0): ?>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    title="Statistika iznajmljivanja"
                                                    data-bs-toggle="popover"
                                                    data-bs-html="true"
                                                    data-bs-content="<div><small>Ukupno dana:</small> <?= $row['UkupnoDana'] ?? 0 ?></div>
                                                                  <div><small>Ukupna zarada:</small> <?= number_format($row['UkupnaZarada'] ?? 0, 2) ?> €</div>">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:1px solid #C8D5B9;">
                <form action="edit_vozilo.php" method="POST">
                    <input type="hidden" name="id" id="editVoziloId">
                    <div class="modal-header" style="border-bottom:1px solid #C8D5B9; background:#fff; border-radius:16px 16px 0 0;">
                        <h5 class="modal-title" style="font-family:'Outfit',sans-serif; color:#3d4a3e;">
                            <i class="fas fa-edit me-2" style="color:#8FA67E;"></i>Uredi vozilo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.5rem;">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Naziv <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="naziv" id="editNaziv" maxlength="25" required
                                       style="border-radius:10px; border:1px solid #C8D5B9;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Model</label>
                                <input type="text" class="form-control" name="model" id="editModel" maxlength="25"
                                       style="border-radius:10px; border:1px solid #C8D5B9;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.88rem;">Cijena/dan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-radius:10px 0 0 10px; border-color:#C8D5B9; background:#E8EDE7; color:#68896B; font-weight:600;">€</span>
                                <input type="number" step="0.01" class="form-control" name="cijena" id="editCijena" required
                                       style="border-radius:0 10px 10px 0; border-color:#C8D5B9;">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Godište</label>
                                <input type="text" class="form-control" name="godiste" id="editGodiste" maxlength="4" inputmode="numeric"
                                       style="border-radius:10px; border:1px solid #C8D5B9;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Kilometraža</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="kilometraza" id="editKilometraza"
                                           style="border-radius:10px 0 0 10px; border-color:#C8D5B9;">
                                    <span class="input-group-text" style="border-radius:0 10px 10px 0; border-color:#C8D5B9; background:#E8EDE7; color:#68896B; font-weight:600;">km</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Registracija</label>
                                <input type="text" class="form-control" name="registracija" id="editRegistracija" maxlength="15"
                                       oninput="this.value = this.value.toUpperCase()"
                                       style="border-radius:10px; border:1px solid #C8D5B9; text-transform:uppercase;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Raspoloživost <span class="text-danger">*</span></label>
                                <select class="form-select" name="raspolozivost" id="editRaspolozivost" required
                                        style="border-radius:10px; border:1px solid #C8D5B9;">
                                    <option value="Dostupno">Dostupno</option>
                                    <option value="Rezervirano">Rezervirano</option>
                                    <option value="Nije dostupno">Nije dostupno</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #C8D5B9; background:#fff; border-radius:0 0 16px 16px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                style="background:#E8EDE7; border:none; color:#3d4a3e; border-radius:10px;">Odustani</button>
                        <button type="submit" class="btn btn-primary"
                                style="background:#68896B; border:none; border-radius:10px;">
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

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                trigger: 'hover focus'
            });
        });

        // Add vehicle form validation
        document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
            let valid = true;

            // Cijena — prihvati zarez ili točku, konvertiraj u točku za PHP
            const cijenaInput = document.getElementById('cijenaVozila');
            const cijenaVal = cijenaInput.value.trim().replace(',', '.');
            if (!/^\d{1,6}(\.\d{0,2})?$/.test(cijenaVal) || parseFloat(cijenaVal) <= 0) {
                cijenaInput.classList.add('is-invalid');
                valid = false;
            } else {
                cijenaInput.classList.remove('is-invalid');
                cijenaInput.value = cijenaVal; // normalizirano s točkom
            }

            // Godište — između 1900 i tekuće godine
            const godisteInput = document.getElementById('godiste');
            if (godisteInput.value.trim() !== '') {
                const g = parseInt(godisteInput.value);
                const currentYear = new Date().getFullYear();
                if (isNaN(g) || g < 1900 || g > currentYear) {
                    godisteInput.classList.add('is-invalid');
                    valid = false;
                } else {
                    godisteInput.classList.remove('is-invalid');
                }
            }

            // Kilometraža — samo cijeli broj
            const kmInput = document.getElementById('kilometraza');
            if (kmInput.value.trim() !== '') {
                if (!/^\d+$/.test(kmInput.value.trim())) {
                    kmInput.classList.add('is-invalid');
                    valid = false;
                } else {
                    kmInput.classList.remove('is-invalid');
                }
            }

            if (!valid) e.preventDefault();
        });

        // Cijena — live format: dozvoli samo brojeve i zarez/točku
        document.getElementById('cijenaVozila').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9.,]/g, '');
        });

        // Kilometraza — samo brojevi
        document.getElementById('kilometraza').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Godiste — samo brojevi, max 4 znaka
        document.getElementById('godiste').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });

        // Reset validacije kad se modal zatvori
        document.getElementById('addVehicleModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('addVehicleForm').reset();
            document.querySelectorAll('#addVehicleForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        function openEditVozilo(id, naziv, model, cijena, godiste, kilometraza, registracija, raspolozivost) {
            document.getElementById('editVoziloId').value        = id;
            document.getElementById('editNaziv').value           = naziv;
            document.getElementById('editModel').value           = model;
            document.getElementById('editCijena').value          = cijena;
            document.getElementById('editGodiste').value         = godiste;
            document.getElementById('editKilometraza').value     = kilometraza;
            document.getElementById('editRegistracija').value    = registracija;
            document.getElementById('editRaspolozivost').value   = raspolozivost;
            new bootstrap.Modal(document.getElementById('editVehicleModal')).show();
        }

        function openPhotoGallery(vehicleId) {
            document.getElementById('galleryVehicleId').value = vehicleId;
            
            fetch('get_vehicle_photos.php?id=' + vehicleId)
                .then(response => response.json())
                .then(data => {
                    const gallery = document.getElementById('photoGalleryContent');
                    gallery.innerHTML = '';
                    
                    if (data.length === 0) {
                        gallery.innerHTML = '<p class="text-muted">Nema slika za ovo vozilo</p>';
                    } else {
                        data.forEach(photo => {
                            const photoDiv = document.createElement('div');
                            photoDiv.className = 'photo-gallery-item';
                            photoDiv.innerHTML = `
                                <img src="${photo.PutanjaSlike}" alt="Vehicle photo">
                                ${photo.JeGlavna ? '<span class="main-photo-badge">Glavna</span>' : ''}
                                <button class="delete-photo" onclick="deletePhoto(${photo.IDSlika})" title="Obriši">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            gallery.appendChild(photoDiv);
                        });
                    }
                    
                    const modal = new bootstrap.Modal(document.getElementById('photoGalleryModal'));
                    modal.show();
                });
        }

        function deletePhoto(photoId) {
            if (confirm('Jeste li sigurni da želite obrisati ovu sliku?')) {
                window.location.href = 'delete_photo.php?id=' + photoId;
            }
        }
    </script>
</body>
</html>