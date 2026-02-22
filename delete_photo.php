<?php
include("db__connection.php");

if (isset($_GET['id'])) {
    $slikaID = intval($_GET['id']);
    
    // Get photo path before deleting
    $query = "SELECT PutanjaSlike FROM vozila_slike WHERE IDSlika = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $slikaID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $photoPath = $row['PutanjaSlike'];
        
        // Delete from database
        $deleteQuery = "DELETE FROM vozila_slike WHERE IDSlika = ?";
        $stmt = mysqli_prepare($db, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $slikaID);
        
        if (mysqli_stmt_execute($stmt)) {
            // Delete physical file
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
            header("Location: pregled_vozila.php?success=Slika uspješno obrisana");
        } else {
            header("Location: pregled_vozila.php?error=Greška pri brisanju slike");
        }
    } else {
        header("Location: pregled_vozila.php?error=Slika nije pronađena");
    }
} else {
    header("Location: pregled_vozila.php");
}
exit();
?>