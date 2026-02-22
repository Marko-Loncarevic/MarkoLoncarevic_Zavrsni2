<?php
include("db__connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

   
    if (empty($ime) || empty($prezime)) {
        header("Location: korisnici.php?error=Ime i prezime su obavezni");
        exit();
    }
    
    // Validate email format if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: korisnici.php?error=Neispravan format email adrese");
        exit();
    }

    // Check if email already exists
    if (!empty($email)) {
        $checkQuery = "SELECT IDKorisnici FROM korisnici WHERE KontaktKorisnika = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        
        if (mysqli_num_rows($result) > 0) {
            header("Location: korisnici.php?error=Korisnik s ovim emailom već postoji");
            exit();
        }
    }
    
    $query = "INSERT INTO korisnici (ImeKorisnika, PrezimeKorisnika, KontaktKorisnika) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);

    if (!$stmt) {
        header("Location: korisnici.php?error=Greška u pripremi upita");
        exit();
    }

  
    mysqli_stmt_bind_param($stmt, "sss", $ime, $prezime, $email);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: korisnici.php?success=Korisnik uspješno dodan");
    } else {
        header("Location: korisnici.php?error=Greška pri dodavanju korisnika: " . mysqli_error($db));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
    exit();
}


header("Location: korisnici.php");
?>