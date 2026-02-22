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
    <title>Pregled korisnika</title>
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
            --accent-rental: #88B49A;
            --accent-payment: #A0C5A8;
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
            border: 1px solid #C48B7C;
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
        .border-left-primary { border-left: 4px solid var(--accent-rental); }
        .border-left-success { border-left: 4px solid var(--accent-payment); }
        .border-left-info { border-left: 4px solid var(--accent-sage); }
        .border-left-warning { border-left: 4px solid var(--accent-taupe); }

        .badge-rental {
            background-color: var(--accent-rental);
            color: white;
        }
        .badge-payment {
            background-color: var(--accent-payment);
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
            color: #C48B7C;
            border-color: var(--accent-light);
        }
        .btn-outline-danger:hover {
            background-color: #C48B7C;
            border-color: #C48B7C;
            color: white;
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
        .modal-body .form-control {
            background: var(--white);
            border: 1px solid var(--accent-light);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
        }
        .modal-body .form-control:focus {
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

        .text-danger {
            color: #C48B7C !important;
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
        .filter-section .form-control {
            background: var(--bg-primary);
            border: 1px solid var(--accent-light);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            color: var(--text-primary);
        }
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
            <h1>Pregled korisnika</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" style="background-color: #3d4a3e; border-color: #3d4a3e;color: white;"data-bs-target="#addUserModal">
                <i class="fas fa-plus me-2"></i>Dodaj korisnika
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <?php
            include("db__connection.php");
            
            $topRentalQuery = "SELECT k.IDKorisnici, k.ImeKorisnika, k.PrezimeKorisnika, 
                             SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)) AS UkupnoDana
                             FROM korisnici k
                             LEFT JOIN rezervacije r ON k.IDKorisnici = r.KorisnikID
                             GROUP BY k.IDKorisnici
                             ORDER BY UkupnoDana DESC
                             LIMIT 1";
            $topRentalResult = mysqli_query($db, $topRentalQuery);
            $topRentalUser = mysqli_fetch_assoc($topRentalResult);
            
            $topPaymentQuery = "SELECT k.IDKorisnici, k.ImeKorisnika, k.PrezimeKorisnika, 
                              SUM(r.UkupnaCijena) AS UkupnoPlatio
                              FROM korisnici k
                              LEFT JOIN rezervacije r ON k.IDKorisnici = r.KorisnikID
                              GROUP BY k.IDKorisnici
                              ORDER BY UkupnoPlatio DESC
                              LIMIT 1";
            $topPaymentResult = mysqli_query($db, $topPaymentQuery);
            $topPaymentUser = mysqli_fetch_assoc($topPaymentResult);
            
            $statsQuery = "SELECT 
                          SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)) AS UkupnoDana,
                          SUM(r.UkupnaCijena) AS UkupnoPlatio
                          FROM rezervacije r";
            $statsResult = mysqli_query($db, $statsQuery);
            $stats = mysqli_fetch_assoc($statsResult);
            ?>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-left-primary">
                    <div class="card-body">
                        <h6>Najaktivniji korisnik</h6>
                        <h4>
                            <?= $topRentalUser ? htmlspecialchars($topRentalUser['ImeKorisnika'].' '.$topRentalUser['PrezimeKorisnika']) : 'Nema podataka' ?>
                        </h4>
                        <span class="badge badge-rental">
                            <?= $topRentalUser ? $topRentalUser['UkupnoDana'].' dana' : '0 dana' ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-left-success">
                    <div class="card-body">
                        <h6>Najveći kupac</h6>
                        <h4>
                            <?= $topPaymentUser ? htmlspecialchars($topPaymentUser['ImeKorisnika'].' '.$topPaymentUser['PrezimeKorisnika']) : 'Nema podataka' ?>
                        </h4>
                        <span class="badge badge-payment">
                            <?= $topPaymentUser ? number_format($topPaymentUser['UkupnoPlatio'], 2).' €' : '0.00 €' ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-left-info">
                    <div class="card-body">
                        <h6>Ukupno dana iznajmljivanja</h6>
                        <h2><?= $stats['UkupnoDana'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card border-left-warning">
                    <div class="card-body">
                        <h6>Ukupna naplaćena vrijednost</h6>
                        <h2><?= isset($stats['UkupnoPlatio']) ? number_format($stats['UkupnoPlatio'], 2).' €' : '0.00 €' ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="dodaj_korisnika.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Dodaj novog korisnika</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Ime <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ime" maxlength="25" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prezime <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prezime" maxlength="25" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" maxlength="100" placeholder="primjer@email.com">
                                <small class="text-muted">Email će se koristiti za identifikaciju korisnika</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
                            <button type="submit" class="btn btn-primary">Spremi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="get" action="">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pretraži po imenu</label>
                        <input type="text" class="form-control" name="search_name" value="<?= $_GET['search_name'] ?? '' ?>" placeholder="Ime ili prezime">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Min. dana iznajmljivanja</label>
                        <input type="number" class="form-control" name="min_days" value="<?= $_GET['min_days'] ?? '' ?>" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Min. ukupno plaćeno (€)</label>
                        <input type="number" step="0.01" class="form-control" name="min_paid" value="<?= $_GET['min_paid'] ?? '' ?>" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                <label class="form-label">Sortiraj po</label>
                <select class="form-select" name="sort_by">
                            <option value="">Zadano (A-Z)</option>
                            <option value="name_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc') ? 'selected' : '' ?>>Ime A-Z</option>
                            <option value="name_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc') ? 'selected' : '' ?>>Ime Z-A</option>
                            <option value="days_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'days_asc') ? 'selected' : '' ?>>Dani ↑</option>
                            <option value="days_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'days_desc') ? 'selected' : '' ?>>Dani ↓</option>
                            <option value="paid_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'paid_asc') ? 'selected' : '' ?>>Plaćeno ↑</option>
                            <option value="paid_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'paid_desc') ? 'selected' : '' ?>>Plaćeno ↓</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #3d4a3e; border-color: #3d4a3e;color: white;">
                            <i class="fas fa-filter me-1"></i> Filtriraj
                        </button>
                        <a href="korisnici.php" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>Email</th>
                                <th>Dana iznajmljivanja</th>
                                <th>Ukupno plaćeno</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT 
                                    k.IDKorisnici, 
                                    k.ImeKorisnika, 
                                    k.PrezimeKorisnika, 
                                    k.KontaktKorisnika,
                                    COALESCE(SUM(DATEDIFF(r.DatumZavrsetka, r.DatumPocetka)), 0) AS UkupnoDana,
                                    COALESCE(SUM(r.UkupnaCijena), 0) AS UkupnoPlatio
                                  FROM 
                                    korisnici k
                                  LEFT JOIN 
                                    rezervacije r ON k.IDKorisnici = r.KorisnikID";
                            
                            // Add filter conditions for WHERE clause
                            $whereClauses = [];
                            if (!empty($_GET['search_name'])) {
                                $searchName = mysqli_real_escape_string($db, $_GET['search_name']);
                                $whereClauses[] = "(k.ImeKorisnika LIKE '%{$searchName}%' OR k.PrezimeKorisnika LIKE '%{$searchName}%')";
                            }
                            
                            if (!empty($whereClauses)) {
                                $query .= " WHERE " . implode(" AND ", $whereClauses);
                            }
                            
                            $query .= " GROUP BY k.IDKorisnici, k.ImeKorisnika, k.PrezimeKorisnika, k.KontaktKorisnika";
                            
                            // Add HAVING clause for aggregated filters
                            $havingClauses = [];
                            if (!empty($_GET['min_days'])) {
                                $havingClauses[] = "UkupnoDana >= " . intval($_GET['min_days']);
                            }
                            if (!empty($_GET['min_paid'])) {
                                $havingClauses[] = "UkupnoPlatio >= " . floatval($_GET['min_paid']);
                            }
                            
                            if (!empty($havingClauses)) {
                                $query .= " HAVING " . implode(" AND ", $havingClauses);
                            }
                            
                            // Add sorting
                            $orderBy = "k.PrezimeKorisnika, k.ImeKorisnika"; // Default sorting
                            if (!empty($_GET['sort_by'])) {
                                switch($_GET['sort_by']) {
                                    case 'name_asc':
                                        $orderBy = "k.PrezimeKorisnika ASC, k.ImeKorisnika ASC";
                                        break;
                                    case 'name_desc':
                                        $orderBy = "k.PrezimeKorisnika DESC, k.ImeKorisnika DESC";
                                        break;
                                    case 'days_asc':
                                        $orderBy = "UkupnoDana ASC";
                                        break;
                                    case 'days_desc':
                                        $orderBy = "UkupnoDana DESC";
                                        break;
                                    case 'paid_asc':
                                        $orderBy = "UkupnoPlatio ASC";
                                        break;
                                    case 'paid_desc':
                                        $orderBy = "UkupnoPlatio DESC";
                                        break;
                                }
                            }
                            
                            $query .= " ORDER BY " . $orderBy;
                            
                            $result = mysqli_query($db, $query) or die("Greška u SQL upitu: " . mysqli_error($db));
                            
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['IDKorisnici'] ?></td>
                                    <td><?= htmlspecialchars($row['ImeKorisnika']) ?></td>
                                    <td><?= htmlspecialchars($row['PrezimeKorisnika']) ?></td>
                                    <td><?= htmlspecialchars($row['KontaktKorisnika'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge badge-rental">
                                            <?= $row['UkupnoDana'] ?> dana
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-payment">
                                            <?= number_format($row['UkupnoPlatio'], 2) ?> €
                                        </span>
                                    </td>
                                    <td class="action-btns">
                                        <button class="btn btn-sm btn-outline-primary" title="Uredi"
                                            onclick="openEditModal(<?= $row['IDKorisnici'] ?>, '<?= htmlspecialchars(addslashes($row['ImeKorisnika'])) ?>', '<?= htmlspecialchars(addslashes($row['PrezimeKorisnika'])) ?>', '<?= htmlspecialchars(addslashes($row['KontaktKorisnika'] ?? '')) ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="obrisi_korisnika.php" style="display:inline;"
                                              onsubmit="return confirm('Jeste li sigurni?')">
                                            <input type="hidden" name="id" value="<?= $row['IDKorisnici'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Obriši">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="uredi_korisnika.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2" style="color:#8FA67E;"></i>Uredi korisnika</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">Ime <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ime" id="editIme" maxlength="25" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prezime <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prezime" id="editPrezime" maxlength="25" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email / Kontakt</label>
                            <input type="text" class="form-control" name="kontakt" id="editKontakt" maxlength="100">
                        </div>
                    </div>
                    <div class="modal-footer">
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

        function openEditModal(id, ime, prezime, kontakt) {
            document.getElementById('editUserId').value   = id;
            document.getElementById('editIme').value      = ime;
            document.getElementById('editPrezime').value  = prezime;
            document.getElementById('editKontakt').value  = kontakt;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }
    </script>
</body>
</html>