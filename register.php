<!doctype html>
<?php
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error        = '';
$registered   = false;
$registeredName  = '';
$registeredEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime      = trim($_POST['ime'] ?? '');
    $prezime  = trim($_POST['prezime'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$ime || !$prezime || !$email || !$password) {
        $error = 'Sva označena polja su obavezna.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Unesite ispravnu email adresu.';
    } elseif (strlen($password) < 6) {
        $error = 'Lozinka mora imati najmanje 6 znakova.';
    } elseif ($password !== $confirm) {
        $error = 'Lozinke se ne podudaraju.';
    } else {
        include 'db__connection.php';
        $stmt = mysqli_prepare($db, "SELECT id FROM accounts WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Račun s ovim emailom već postoji.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($db, "INSERT INTO accounts (ime, prezime, email, password_hash) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssss', $ime, $prezime, $email, $hash);

            if (mysqli_stmt_execute($stmt)) {
                $registered      = true;
                $registeredName  = $ime . ' ' . $prezime;
                $registeredEmail = $email;
            } else {
                $error = 'Greška pri kreiranju računa. Pokušaj ponovo.';
            }
        }
    }
}
?>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registracija — Rent-a-Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        :root { --bg:#E8EDE7;--white:#ffffff;--green:#68896B;--sage:#8FA67E;--light:#C8D5B9;--text:#3d4a3e;--muted:#6B7B6E; }
        body { background:var(--bg);font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem; }
        .auth-card { background:var(--white);border-radius:20px;border:1px solid var(--light);padding:2.5rem;width:100%;max-width:460px;box-shadow:0 4px 24px rgba(0,0,0,.07); }
        .brand { font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:700;color:var(--text);text-align:center;margin-bottom:.25rem; }
        .brand i { color:var(--sage);margin-right:8px; }
        .brand-sub { text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2rem; }
        .form-label { font-weight:600;font-size:.88rem;color:var(--text); }
        .form-control { border-radius:10px;border:1px solid var(--light);color:var(--text);padding:.6rem .9rem; }
        .form-control:focus { border-color:var(--sage);box-shadow:0 0 0 4px rgba(143,166,126,.12); }
        .btn-primary { background:var(--green);border:none;border-radius:10px;padding:.65rem;font-weight:600;width:100%; }
        .btn-primary:hover { background:var(--sage); }
        .btn-outline-green { display:block;text-align:center;background:none;border:1px solid var(--light);color:var(--green);border-radius:10px;padding:.65rem;font-weight:600;width:100%;text-decoration:none;transition:all .2s; }
        .btn-outline-green:hover { background:var(--bg);color:var(--green); }
        .link-green { color:var(--green);font-weight:600;text-decoration:none; }
        .link-green:hover { color:var(--sage); }
        .alert-danger { background:#f4ddd4;border:1px solid #C48B7C;border-radius:10px;color:var(--text);font-size:.9rem; }
        .input-icon { position:relative; }
        .input-icon i { position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.9rem; }
        .input-icon .form-control { padding-left:2.3rem; }
        .pw-hint { font-size:.78rem;color:var(--muted);margin-top:.3rem; }
        /* Success */
        .success-icon { width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#68896B,#8FA67E);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;animation:popIn .5s cubic-bezier(.175,.885,.32,1.275) both; }
        .success-icon i { color:white;font-size:2rem; }
        @keyframes popIn { 0%{transform:scale(0);opacity:0}100%{transform:scale(1);opacity:1} }
        .success-title { font-family:'Outfit',sans-serif;font-size:1.5rem;font-weight:700;color:var(--text);text-align:center;margin-bottom:.5rem; }
        .success-sub { text-align:center;color:var(--muted);font-size:.92rem;margin-bottom:1.75rem;line-height:1.6; }
        .info-box { background:var(--bg);border:1px solid var(--light);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem; }
        .info-row { display:flex;align-items:center;gap:.6rem;font-size:.88rem;color:var(--text); }
        .info-row+.info-row { margin-top:.5rem; }
        .info-row i { color:var(--sage);width:16px; }
        .mail-badge { display:inline-flex;align-items:center;gap:.4rem;background:#e8f4ec;border:1px solid #88B49A;border-radius:8px;padding:.45rem .9rem;font-size:.83rem;color:#3d6b47;margin-top:1rem;width:100%;justify-content:center; }
        .mail-badge.failed { background:#fff8f0;border-color:#D4A574;color:#7a5a30; }
    </style>
</head>
<body>
<div class="auth-card">
<?php if ($registered): ?>
    <div class="success-icon"><i class="fas fa-check"></i></div>
    <div class="success-title">Registracija uspješna!</div>
    <div class="success-sub">Tvoj račun je kreiran. Možeš se odmah prijaviti i početi koristiti Rent-a-Car.</div>
    <div class="info-box">
        <div class="info-row"><i class="fas fa-user"></i><span><?= htmlspecialchars($registeredName) ?></span></div>
        <div class="info-row"><i class="fas fa-envelope"></i><span><?= htmlspecialchars($registeredEmail) ?></span></div>
    </div>
    <div style="height:1.5rem;"></div>
    <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Prijavi se</a>
    <a href="index.php" class="btn-outline-green mt-2">Nastavi kao gost</a>
<?php else: ?>
    <div class="brand"><i class="fas fa-car"></i>Rent-a-Car</div>
    <div class="brand-sub">Kreiraj novi račun</div>
    <?php if ($error): ?><div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
        <div class="row mb-3">
            <div class="col-6">
                <label class="form-label">Ime <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ime" maxlength="25" required value="<?= htmlspecialchars($_POST['ime'] ?? '') ?>" >
            </div>
            <div class="col-6">
                <label class="form-label">Prezime <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="prezime" maxlength="25" required value="<?= htmlspecialchars($_POST['prezime'] ?? '') ?>" >
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" >
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Lozinka <span class="text-danger">*</span></label>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" name="password"  required>
            </div>
            <div class="pw-hint">Najmanje 6 znakova</div>
        </div>
        <div class="mb-4">
            <label class="form-label">Potvrdi lozinku <span class="text-danger">*</span></label>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" name="confirm"  required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Kreiraj račun</button>
    </form>
    <div class="text-center mt-3" style="font-size:.9rem;color:var(--muted);">
        Već imaš račun? <a href="login.php" class="link-green">Prijavi se</a>
    </div>
<?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>