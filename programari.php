<?php
include "db_conn.php";
session_start();

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'pacient') {
    // Dacă nu este autentificat sau nu este un pacient, redirecționăm către pagina de login
    header("Location: login.php");
    exit();
}

// Obțineți ID-ul utilizatorului din sesiune
$username = $_SESSION['username'];
$sql = "SELECT id FROM utilizatori WHERE user_name='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
} else {
    echo "Utilizatorul nu există.";
    exit();
}

// Obținem programările utilizatorului din baza de date
$sql = "SELECT p.id, s.nume AS nume_sectiune, p.data_programare
        FROM programari p
        JOIN sectiuni s ON p.sectiune_id = s.id
        WHERE p.user_id = $userId
        ORDER BY p.data_programare DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programările mele</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Programările mele</h3>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID Programare</th><th>Sectiune</th><th>Data Programare</th></tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['nume_sectiune'] . '</td>';
                echo '<td>' . $row['data_programare'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Nu ai programări efectuate.</p>';
        }
        ?>
        <div class="text-center my-4">
            <a href="pacient_acount.php" class="btn btn-primary">Înapoi la profil</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
