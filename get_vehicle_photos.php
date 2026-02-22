<?php
include("db__connection.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $voziloID = intval($_GET['id']);
    
    $query = "SELECT IDSlika, VoziloID, PutanjaSlike, JeGlavna 
              FROM vozila_slike 
              WHERE VoziloID = ? 
              ORDER BY JeGlavna DESC, DatumDodavanja DESC";
    
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $voziloID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $photos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $photos[] = $row;
    }
    
    echo json_encode($photos);
} else {
    echo json_encode([]);
}

mysqli_close($db);
?>