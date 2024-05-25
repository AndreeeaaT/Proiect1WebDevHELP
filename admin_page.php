<?php
include "db_conn.php";
include "functions.php";
session_start();

// Verificăm dacă utilizatorul este autentificat și este administrator
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'administrator') {
    header("Location: login.php");
    exit();
}

// Funcționalitate pentru adăugarea doctorilor
if (isset($_POST['add_doctor'])) {
    $doctorName = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    // Puteți adăuga aici și verificări suplimentare ale datelor introduse

    // Inserăm noul doctor în tabela doctori
    $insert_sql = "INSERT INTO doctori (nume, specializare) VALUES ('$doctorName', '$specialization')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "Doctorul a fost adăugat cu succes.";
    } else {
        echo "Eroare la adăugarea doctorului: " . mysqli_error($conn);
    }
}

// Funcționalitate pentru ștergerea utilizatorilor
if (isset($_GET['delete_user_id'])) {
    $userId = $_GET['delete_user_id'];
    $deleteUserSql = "DELETE FROM utilizatori WHERE id = $userId";
    if (mysqli_query($conn, $deleteUserSql)) {
        echo "Utilizatorul a fost șters cu succes.";
    } else {
        echo "Eroare la ștergerea utilizatorului: " . mysqli_error($conn);
    }
}

// Funcționalitate pentru ștergerea programărilor
if (isset($_GET['delete_appointment_id'])) {
    $appointmentId = $_GET['delete_appointment_id'];
    $deleteAppointmentSql = "DELETE FROM programari WHERE id = $appointmentId";
    if (mysqli_query($conn, $deleteAppointmentSql)) {
        echo "Programarea a fost ștearsă cu succes.";
    } else {
        echo "Eroare la ștergerea programării: " . mysqli_error($conn);
    }
}

// Obținem lista utilizatorilor din baza de date
$sqlUsers = "SELECT id, user_name, tip_utilizator, profil_img FROM utilizatori";
$resultUsers = mysqli_query($conn, $sqlUsers);

// Obținem lista programărilor din baza de date
$sqlAppointments = "SELECT p.id, u.user_name, p.data_programare FROM programari p JOIN utilizatori u ON p.user_id = u.id";
$resultAppointments = mysqli_query($conn, $sqlAppointments);

// Funcționalitate pentru ștergerea mesajelor de feedback
if (isset($_GET['delete_feedback_id'])) {
    $feedbackId = $_GET['delete_feedback_id'];
    $deleteFeedbackSql = "DELETE FROM feedback WHERE id = $feedbackId";
    if (mysqli_query($conn, $deleteFeedbackSql)) {
        echo "Mesajul de feedback a fost șters cu succes.";
    } else {
        echo "Eroare la ștergerea mesajului de feedback: " . mysqli_error($conn);
    }
}

// Obținem lista utilizatorilor din baza de date
$sqlUsers = "SELECT id, user_name, tip_utilizator, profil_img FROM utilizatori";
$resultUsers = mysqli_query($conn, $sqlUsers);

// Obținem lista programărilor din baza de date
$sqlAppointments = "SELECT p.id, u.user_name, p.data_programare FROM programari p JOIN utilizatori u ON p.user_id = u.id";
$resultAppointments = mysqli_query($conn, $sqlAppointments);

// Obținem lista feedback-urilor din baza de date
$sqlFeedback = "SELECT id, doctor_id, patient_name, feedback_text FROM feedback";
$resultFeedback = mysqli_query($conn, $sqlFeedback);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function confirmDeleteUser(userId) {
            if (confirm('Sunteți sigur că doriți să ștergeți acest utilizator?')) {
                window.location.href = 'admin_page.php?delete_user_id=' + userId;
            }
        }

        function confirmDeleteAppointment(appointmentId) {
            if (confirm('Sunteți sigur că doriți să ștergeți această programare?')) {
                window.location.href = 'admin_page.php?delete_appointment_id=' + appointmentId;
            }
        }

        function confirmDeleteFeedback(feedbackId) {
            if (confirm('Sunteți sigur că doriți să ștergeți acest feedback?')) {
                window.location.href = 'admin_page.php?delete_feedback_id=' + feedbackId;
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Page</h2>

        <!-- Formular pentru adăugarea de doctori -->
        <form action="admin_page.php" method="post">
            <h3>Adăugare Doctor</h3>
            <div class="form-group">
                <label for="doctor_name">Nume Doctor:</label>
                <input type="text" class="form-control" id="doctor_name" name="doctor_name" required>
            </div>
            <div class="form-group">
                <label for="specialization">Specializare:</label>
                <input type="text" class="form-control" id="specialization" name="specialization" required>
            </div>
            <!-- Alte câmpuri pentru adăugarea de informații suplimentare, cum ar fi imaginea de profil -->
            <button type="submit" class="btn btn-primary" name="add_doctor">Adaugă Doctor</button>
        </form>

        <!-- Restul codului pentru afișarea utilizatorilor și programărilor -->
        <h3>Utilizatori</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Profile Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultUsers) > 0) {
                    while ($row = mysqli_fetch_assoc($resultUsers)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_name'] . "</td>";
                        echo "<td>" . $row['tip_utilizator'] . "</td>";
                        echo "<td><img src='" . $row['profil_img'] . "' alt='Profile Image' width='50'></td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmDeleteUser(" . $row['id'] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Programări</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Data Programare</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultAppointments) > 0) {
                    while ($row = mysqli_fetch_assoc($resultAppointments)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_name'] . "</td>";
                        echo "<td>" . $row['data_programare'] . "</td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmDeleteAppointment(" . $row['id'] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No appointments found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h3>Feedback</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Doctor ID</th>
                    <th>Patient Name</th>
                    <th>Feedback Text</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultFeedback) > 0) {
                    while ($row = mysqli_fetch_assoc($resultFeedback)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['doctor_id'] . "</td>";
                        echo "<td>" . $row['patient_name'] . "</td>";
                        echo "<td>" . $row['feedback_text'] . "</td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmDeleteFeedback(" . $row['id'] . ")'>Delete</button></td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No feedback found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>

</body>
</html>

<?php
mysqli_close($conn);
?>
