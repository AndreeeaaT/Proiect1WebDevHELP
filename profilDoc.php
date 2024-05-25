<?php
include "db_conn.php";
session_start();

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'doctor') {
    // Dacă nu este autentificat sau nu este un doctor, redirecționăm către pagina de login
    header("Location: login.php");
    exit();
}

// Obțineți detaliile doctorului din baza de date
$username = $_SESSION['username'];
$sql = "SELECT * FROM doctori WHERE nume='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
    $userName = $row['nume'];
    $specializare = $row['specializare']; // Extrageți specializarea din baza de date
    
    // Verificați dacă există cheia 'profil_img' în rândul asociativ
    $profilImg = isset($row['profil_img']) ? $row['profil_img'] : ''; // Atribuiți valoarea sau un șir gol dacă cheia nu există
} else {
    echo "Utilizatorul nu există.";
    exit();
}

// Obțineți lista secțiunilor
$sectiuni_sql = "SELECT * FROM sectiuni";
$sectiuni_result = mysqli_query($conn, $sectiuni_sql);

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
    <div class="container mt-5">
        <section>
            <h3>Informatiile tale</h3>
            <ul>
                <li>User ID: <?php echo $userId; ?></li>
                <li>User Name: <?php echo $userName; ?></li>
                <li>Specializare: <?php echo $specializare; ?></li> <!-- Afișați specializarea din baza de date -->
                <?php if (!empty($profilImg)) { ?>
                    <li><img src="<?php echo $profilImg; ?>" alt="Profile Image" width="150" height="150"></li>
                <?php } ?>
            </ul>
        </section>

        <section>
            <h3>Upload Profile Image</h3>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="profile_image" required><br><br>
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
        </section>

        <div class="text-center my-4">
            <a href="doctor_account.php" class="btn btn-secondary">Inapoi la profil</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>