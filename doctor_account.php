<?php
include "db_conn.php";
include "functions.php";
session_start();
// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'doctor') {
    // Dacă nu este autentificat sau nu este un pacient, redirecționăm către pagina de login
    header("Location: login.php");
    exit();
}



// Obțineți detaliile pacientului din baza de date
$username = $_SESSION['username'];
$sql = "SELECT * FROM doctori WHERE nume='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
    $userName = $row['nume'];
    // Aici puteți obține și afișa alte detalii ale pacientului din baza de date
} else {
    echo "Utilizatorul nu există.";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Account</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h2>Bun venit in contul tau, <?php echo $userName; ?>!</h2>
        <nav>
    <table>
        <tr>
            <td><a href="profilDoc.php">Profil</a></td>
            <td><a href="programariDoc.php">Programari</a></td>
            <td><a href="logout.php">Logout</a></td>
        </tr>
    </table>
</nav>

    </header>
    
    
</body>
</html>
