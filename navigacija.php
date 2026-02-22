<!DOCTYPE html>
<?php
require_once __DIR__ . '/auth.php';
?>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent-a-Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-primary: #F5F7F4; --bg-secondary: #E8EDE7;
            --text-primary: #3d4a3e; --text-secondary: #6B7B6E;
            --accent-green: #68896B; --accent-sage: #8FA67E;
            --accent-light: #C8D5B9; --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',-apple-system,sans-serif; background-color:var(--bg-secondary); }

        .navbar-modern { background-color:var(--bg-primary); padding:1rem 2rem; box-shadow:var(--shadow-sm); border-bottom:1px solid var(--accent-light); position:relative; z-index:1000; }
        .navbar-brand-modern { color:var(--text-primary); font-size:1.5rem; font-weight:600; display:flex; align-items:center; transition:var(--transition); font-family:'Outfit',sans-serif; text-decoration:none; }
        .navbar-brand-modern:hover { color:var(--accent-green); transform:translateY(-1px); }
        .navbar-brand-modern i { margin-right:10px; font-size:1.3rem; color:var(--accent-sage); }

        /* Brand centriran za non-admin */
        .navbar-brand-centered {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            color:var(--text-primary); font-size:1.5rem; font-weight:600;
            display:flex; align-items:center;
            font-family:'Outfit',sans-serif; text-decoration:none;
            transition:var(--transition);
            white-space: nowrap;
        }
        .navbar-brand-centered:hover { color:var(--accent-green); }
        .navbar-brand-centered i { margin-right:10px; font-size:1.3rem; color:var(--accent-sage); }

        .nav-item-modern { margin:0 0.25rem; position:relative; }
        .nav-link-modern { color:var(--text-secondary) !important; font-weight:500; padding:0.6rem 1rem !important; border-radius:12px; transition:var(--transition); display:flex; align-items:center; font-size:0.95rem; letter-spacing:0.2px; }
        .nav-link-modern i { margin-right:8px; font-size:1rem; opacity:0.8; }
        .nav-link-modern:hover { color:var(--accent-green) !important; background-color:rgba(200,213,185,0.3); transform:translateY(-1px); }
        .nav-link-modern.active { background-color:var(--accent-green); color:var(--white) !important; font-weight:600; box-shadow:0 2px 8px rgba(104,137,107,0.2); }
        .nav-link-modern.active i { opacity:1; }

        .admin-only { border-left: 2px solid var(--accent-light); padding-left: 0.5rem; }

        .user-badge {
            display:flex; align-items:center; gap:0.5rem;
            background:var(--bg-secondary); border:1px solid var(--accent-light);
            border-radius:20px; padding:0.4rem 1rem 0.4rem 0.6rem;
            font-size:0.88rem; color:var(--text-primary); font-weight:500;
            text-decoration:none; transition: border-color 0.2s;
        }
        .user-badge:hover { border-color: var(--accent-sage); color: var(--text-primary); }
        .user-badge .avatar {
            width:30px; height:30px; border-radius:50%;
            background:var(--accent-green); color:white;
            display:flex; align-items:center; justify-content:center;
            font-size:0.75rem; font-weight:700; font-family:'Outfit',sans-serif;
        }
        .user-badge.admin-badge { background:#fff8f0; border-color:#D4A574; cursor:default; }
        .user-badge.admin-badge:hover { border-color:#D4A574; }
        .user-badge.admin-badge .avatar { background:#D4A574; }

        .btn-logout { background:none; border:1px solid #C48B7C; color:#C48B7C; border-radius:20px; padding:0.4rem 1rem; font-size:0.85rem; font-weight:500; cursor:pointer; transition:var(--transition); }
        .btn-logout:hover { background:#f4ddd4; }
        .btn-login { background:var(--accent-green); color:white; border:none; border-radius:20px; padding:0.4rem 1.1rem; font-size:0.85rem; font-weight:600; text-decoration:none; transition:var(--transition); }
        .btn-login:hover { background:var(--accent-sage); color:white; }
        .btn-register { background:none; color:var(--accent-green); border:1px solid var(--accent-light); border-radius:20px; padding:0.4rem 1rem; font-size:0.85rem; font-weight:500; text-decoration:none; transition:var(--transition); }
        .btn-register:hover { background:var(--bg-secondary); color:var(--accent-green); }

        .navbar-toggler-modern { border:none; outline:none; padding:0.5rem; background-color:var(--bg-secondary); border-radius:8px; transition:var(--transition); }
        .navbar-toggler-modern:hover { background-color:var(--accent-light); }
        .navbar-toggler-modern:focus { box-shadow:none; }
        .navbar-toggler-icon-modern { background-image:none; position:relative; width:26px; height:2px; background-color:var(--text-primary); display:block; transition:var(--transition); border-radius:2px; }
        .navbar-toggler-icon-modern::before,.navbar-toggler-icon-modern::after { content:''; position:absolute; width:100%; height:100%; background-color:var(--text-primary); left:0; transition:var(--transition); border-radius:2px; }
        .navbar-toggler-icon-modern::before { top:-8px; }
        .navbar-toggler-icon-modern::after { top:8px; }
        .navbar-toggler-modern[aria-expanded="true"] .navbar-toggler-icon-modern { background-color:transparent; }
        .navbar-toggler-modern[aria-expanded="true"] .navbar-toggler-icon-modern::before { transform:rotate(45deg); top:0; }
        .navbar-toggler-modern[aria-expanded="true"] .navbar-toggler-icon-modern::after { transform:rotate(-45deg); top:0; }

        @media (max-width:991.98px) {
            .navbar-modern { padding:1rem 1.5rem; }
            .navbar-collapse-modern { background-color:var(--white); padding:1.5rem; border-radius:12px; box-shadow:var(--shadow-md); margin-top:1rem; border:1px solid var(--accent-light); }
            .nav-item-modern { margin:0.3rem 0; }
            .nav-link-modern { padding:0.75rem 1rem !important; justify-content:flex-start; }
            .admin-only { border-left:none; border-top:1px solid var(--accent-light); padding-top:0.5rem; margin-top:0.5rem; }
            /* Na mobilnom brand se ne centrira */
            .navbar-brand-centered { position:static; transform:none; }
        }
        html { scroll-behavior:smooth; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-modern">
    <div class="container-fluid">

        <?php if (isAdmin()): ?>
            <!-- Admin: brand normalno lijevo -->
            <a class="navbar-brand navbar-brand-modern" href="index.php">
                <i class="fas fa-car"></i> Rent-a-Car
            </a>
        <?php else: ?>
            <!-- Korisnik/gost: brand apsolutno centriran -->
            <a class="navbar-brand-centered" href="index.php">
                <i class="fas fa-car"></i> Rent-a-Car
            </a>
        <?php endif; ?>

        <button class="navbar-toggler navbar-toggler-modern" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarModern"
                aria-controls="navbarModern" aria-expanded="false">
            <span class="navbar-toggler-icon-modern"></span>
        </button>

        <div class="collapse navbar-collapse navbar-collapse-modern" id="navbarModern">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isAdmin()): ?>
                <li class="nav-item nav-item-modern admin-only">
                    <a class="nav-link nav-link-modern" href="korisnici.php">
                        <i class="fas fa-users"></i> Korisnici
                    </a>
                </li>
                <li class="nav-item nav-item-modern">
                    <a class="nav-link nav-link-modern" href="pregled_rezervacija.php">
                        <i class="fas fa-calendar-check"></i> Rezervacije
                    </a>
                </li>
                <li class="nav-item nav-item-modern">
                    <a class="nav-link nav-link-modern" href="pregled_vozila.php">
                        <i class="fas fa-car-side"></i> Vozila
                    </a>
                </li>
                <li class="nav-item nav-item-modern">
                    <a class="nav-link nav-link-modern" href="statistika.php">
                        <i class="fas fa-chart-line"></i> Statistika
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (isAdmin()): ?>
                    <div class="user-badge admin-badge">
                        <div class="avatar"><i class="fas fa-shield-alt" style="font-size:0.7rem;"></i></div>
                        Administrator
                    </div>
                    <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt me-1"></i>Odjava
                        </button>
                    </form>

                <?php elseif (isUser()): ?>
                    <?php $initials = strtoupper(substr($_SESSION['account_ime'],0,1) . substr($_SESSION['account_prezime'],0,1)); ?>
                    <a href="moj_profil.php" class="user-badge">
                        <div class="avatar"><?= $initials ?></div>
                        <?= htmlspecialchars(getAccountName()) ?>
                    </a>
                    <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt me-1"></i>Odjava
                        </button>
                    </form>

                <?php else: ?>
                    <a href="register.php" class="btn-register">
                        <i class="fas fa-user-plus me-1"></i>Registracija
                    </a>
                    <a href="login.php" class="btn-login">
                        <i class="fas fa-sign-in-alt me-1"></i>Prijava
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        document.querySelectorAll('.nav-link-modern').forEach(link => {
            if (link.getAttribute('href') === currentPage) link.classList.add('active');
        });
    });
</script>
</body>
</html>