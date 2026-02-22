<?php
include("db__connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naziv = $_POST['nazivVozila'];
    $model = $_POST['modelVozila'];
    $cijena = $_POST['cijenaVozila'];
    $godiste = $_POST['godiste'];
    $kilometraza = $_POST['kilometraza'];
    $registracija = $_POST['registracija'];

    // Start transaction
    mysqli_begin_transaction($db);
    
    try {
        // Insert vehicle
        $queryVozilo = "INSERT INTO vozila (Naziv, Model, CijenaKoristenjaDnevno) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db, $queryVozilo);
        mysqli_stmt_bind_param($stmt, "ssd", $naziv, $model, $cijena);
        
        if (mysqli_stmt_execute($stmt)) {
            $voziloID = mysqli_insert_id($db);

            // Insert characteristics
            $queryKarakteristike = "INSERT INTO karakteristike_automobila (Godiste, Kilometraza, Registracija, VoziloID) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $queryKarakteristike);
            mysqli_stmt_bind_param($stmt, "iisi", $godiste, $kilometraza, $registracija, $voziloID);
            
            if (mysqli_stmt_execute($stmt)) {
                // Handle photo upload if exists
                if (isset($_FILES['vehicle_photo']) && $_FILES['vehicle_photo']['error'] == 0) {
                    $uploadDir = "uploads/vehicles/";
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $file = $_FILES['vehicle_photo'];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                    
                    if (in_array($fileExt, $allowed) && $file['size'] < 5000000) {
                        $fileNameNew = "vehicle_" . $voziloID . "_" . uniqid('', true) . "." . $fileExt;
                        $fileDestination = $uploadDir . $fileNameNew;
                        
                        if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
                            $photoQuery = "INSERT INTO vozila_slike (VoziloID, PutanjaSlike, JeGlavna) VALUES (?, ?, 1)";
                            $stmt = mysqli_prepare($db, $photoQuery);
                            mysqli_stmt_bind_param($stmt, "is", $voziloID, $fileDestination);
                            mysqli_stmt_execute($stmt);
                        }
                    }
                }
                
                mysqli_commit($db);
                header("Location: pregled_vozila.php?success=Vozilo uspješno dodano");
                exit();
            } else {
                throw new Exception("Greška pri unosu karakteristika vozila");
            }
        } else {
            throw new Exception("Greška pri unosu vozila");
        }
    } catch (Exception $e) {
        mysqli_rollback($db);
        echo "Greška: " . $e->getMessage();
    }

    mysqli_close($db);
} else {
    echo "Forma nije poslana.";
}
?>