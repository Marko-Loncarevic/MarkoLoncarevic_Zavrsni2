<!doctype html>
<?php
require_once 'auth.php';
require_once 'config.php';

// Already logged in — redirect
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Admin check
    if ($input === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit();
    }

    // Regular user
    include 'db__connection.php';
    $stmt = mysqli_prepare($db, "SELECT id, ime, prezime, email, password_hash FROM accounts WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $input);
    mysqli_stmt_execute($stmt);
    $account = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($account && password_verify($password, $account['password_hash'])) {
        $_SESSION['account_id']      = $account['id'];
        $_SESSION['account_ime']     = $account['ime'];
        $_SESSION['account_prezime'] = $account['prezime'];
        $_SESSION['account_email']   = $account['email'];
        header('Location: index.php');
        exit();
    }

    $error = 'Pogrešan email ili lozinka.';
}
?>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prijava — Rent-a-Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root {
            --bg:           #E8EDE7;
            --white:        #ffffff;
            --green:        #68896B;
            --sage:         #8FA67E;
            --light:        #C8D5B9;
            --text:         #3d4a3e;
            --text-muted:   #6B7B6E;
        }
        body { background: var(--bg); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--light);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .brand { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--text); text-align: center; margin-bottom: 0.25rem; }
        .brand i { color: var(--sage); margin-right: 8px; }
        .brand-sub { text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; }
        .form-label { font-weight: 600; font-size: 0.88rem; color: var(--text); }
        .form-control {
            border-radius: 10px;
            border: 1px solid var(--light);
            color: var(--text);
            padding: 0.6rem 0.9rem;
        }
        .form-control:focus { border-color: var(--sage); box-shadow: 0 0 0 4px rgba(143,166,126,0.12); }
        .btn-primary { background: var(--green); border: none; border-radius: 10px; padding: 0.65rem; font-weight: 600; width: 100%; }
        .btn-primary:hover { background: var(--sage); }
        .divider { text-align: center; color: var(--text-muted); font-size: 0.85rem; margin: 1.25rem 0; position: relative; }
        .divider::before, .divider::after { content:''; position:absolute; top:50%; width:42%; height:1px; background:var(--light); }
        .divider::before { left:0; } .divider::after { right:0; }
        .link-green { color: var(--green); font-weight: 600; text-decoration: none; }
        .link-green:hover { color: var(--sage); }
        .alert-danger { background:#f4ddd4; border:1px solid #C48B7C; border-radius:10px; color:var(--text); font-size:0.9rem; }
        .guest-btn { display:block; text-align:center; color:var(--text-muted); font-size:0.88rem; margin-top:1rem; text-decoration:none; }
        .guest-btn:hover { color:var(--green); }
        .input-icon { position:relative; }
        .input-icon i { position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.9rem; }
        .input-icon .form-control { padding-left: 2.3rem; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="brand"><i class="fas fa-car"></i>Rent-a-Car</div>
    <div class="brand-sub">Prijavi se u svoj račun</div>

    <?php if ($error): ?>
        <div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-3" style="background:#d4edda; border:1px solid #88B49A; border-radius:10px; color:var(--text); font-size:0.9rem;">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email </label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="text" class="form-control" name="email"  required autofocus
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Lozinka</label>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" name="password"  required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Prijavi se
        </button>
    </form>

    <div class="divider">ili</div>

    <div class="text-center" style="font-size:0.9rem; color:var(--text-muted);">
        Nemaš račun? <a href="register.php" class="link-green">Registriraj se</a>
    </div>

    <a href="index.php" class="guest-btn">
        <i class="fas fa-arrow-right me-1"></i>Nastavi kao gost
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>