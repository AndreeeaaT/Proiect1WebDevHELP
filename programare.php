<?php
include "db_conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $sectiune_id = $_POST['sectiune'];
    $data_programare = $_POST['data'];
    
    // Corectăm interogarea pentru a include doar coloanele și valorile necesare
    $sql = "INSERT INTO programari (user_id, sectiune_id, data_programare) VALUES ('$user_id', '$sectiune_id', '$data_programare')";

    if (mysqli_query($conn, $sql)) {
        $message = "Appointment made successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    $message = "Invalid request method.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Account</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <p><?php echo $message; ?></p>
    <a href="profil.php">Inapoi la profil</a>
</body>
</html>
