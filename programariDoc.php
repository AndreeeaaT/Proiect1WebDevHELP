<?php
include "db_conn.php";
session_start();

// Verificăm dacă utilizatorul este autentificat și este doctor
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'doctor') {
    // Dacă nu este autentificat sau nu este un doctor, redirecționăm către pagina de login
    header("Location: login.php");
    exit();
}

// Obținem numele doctorului autentificat
$username = $_SESSION['username'];

// Obținem detaliile doctorului din baza de date
$sql = "SELECT * FROM doctori WHERE nume='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $doctorId = $row['id'];
    $doctorName = $row['nume'];
    $specialization_name = $row['specializare'];

    // Verificăm dacă tabelul 'sectiuni' există
    $resultCheckTable = mysqli_query($conn, "SHOW TABLES LIKE 'sectiuni'");
    if (mysqli_num_rows($resultCheckTable) == 0) {
        die("Eroare: Tabela 'sectiuni' nu există în baza de date.");
    }

    // Obținem id-ul și numele specializării
    $sqlSpecialization = "SELECT id, nume FROM sectiuni WHERE nume='$specialization_name'";
    $resultSpecialization = mysqli_query($conn, $sqlSpecialization);
    if (!$resultSpecialization) {
        die("Eroare la interogarea specializării: " . mysqli_error($conn));
    }
    $rowSpecialization = mysqli_fetch_assoc($resultSpecialization);
    $specialization_id = $rowSpecialization['id'];
    $specialization = $rowSpecialization['nume'];

    // Obținem programările pentru acest doctor
    $sqlAppointments = "SELECT programari.id, programari.data_programare, utilizatori.user_name AS nume_pacient 
                        FROM programari 
                        JOIN utilizatori ON programari.user_id = utilizatori.id 
                        WHERE programari.sectiune_id='$specialization_id'";
    $resultAppointments = mysqli_query($conn, $sqlAppointments);
    if (!$resultAppointments) {
        die("Eroare la interogarea programărilor: " . mysqli_error($conn));
    }
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
    <title>Doctor Appointments</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h2>Programările tale, <?php echo $doctorName; ?> (<?php echo $specialization; ?>)!</h2>
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
    
    <div class="container">
        <h3>Programările tale:</h3>
        <?php if (mysqli_num_rows($resultAppointments) > 0) { ?>
            <table>
                <tr>
                    <th>ID Programare</th>
                    <th>Data Programare</th>
                    <th>Pacient</th>
                    <!-- Alte coloane pentru detalii suplimentare despre programare -->
                </tr>
                <?php while ($row = mysqli_fetch_assoc($resultAppointments)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['data_programare']; ?></td>
                        <td><?php echo $row['nume_pacient']; ?></td>
                        <!-- Afișați alte detalii despre programare, dacă este cazul -->
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>Nu există programări.</p>
        <?php } ?>
    </div>
</body>
</html>
