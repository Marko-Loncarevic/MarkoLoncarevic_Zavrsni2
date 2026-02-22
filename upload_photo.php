<?php
include("db__connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['vehicle_photo'])) {
    $voziloID = intval($_POST['voziloID']);
    $jeGlavna = isset($_POST['jeGlavna']) ? 1 : 0;
    
    // Create uploads directory if it doesn't exist
    $uploadDir = "uploads/vehicles/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $file = $_FILES['vehicle_photo'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Allowed extensions
    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    
    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // 5MB max
                // Generate unique filename
                $fileNameNew = "vehicle_" . $voziloID . "_" . uniqid('', true) . "." . $fileExt;
                $fileDestination = $uploadDir . $fileNameNew;
                
                // If this is set as main photo, unset others
                if ($jeGlavna) {
                    $updateQuery = "UPDATE vozila_slike SET JeGlavna = 0 WHERE VoziloID = ?";
                    $stmt = mysqli_prepare($db, $updateQuery);
                    mysqli_stmt_bind_param($stmt, "i", $voziloID);
                    mysqli_stmt_execute($stmt);
                }
                
                // Move uploaded file
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Save to database
                    $query = "INSERT INTO vozila_slike (VoziloID, PutanjaSlike, JeGlavna) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt, "isi", $voziloID, $fileDestination, $jeGlavna);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        header("Location: pregled_vozila.php?success=Slika uspješno dodana");
                        exit();
                    } else {
                        header("Location: pregled_vozila.php?error=Greška pri spremanju u bazu");
                        exit();
                    }
                } else {
                    header("Location: pregled_vozila.php?error=Greška pri uploadu slike");
                    exit();
                }
            } else {
                header("Location: pregled_vozila.php?error=Slika je prevelika (max 5MB)");
                exit();
            }
        } else {
            header("Location: pregled_vozila.php?error=Greška pri uploadu");
            exit();
        }
    } else {
        header("Location: pregled_vozila.php?error=Nepodržani format slike");
        exit();
    }
} else {
    header("Location: pregled_vozila.php");
    exit();
}
?>