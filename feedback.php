<?php
include "db_conn.php"; // Include fișierul de conexiune la baza de date

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $patient_name = $_POST['name'];
    $feedback_text = $_POST['feedback'];

    // Validate input
    if (!empty($doctor_id) && !empty($patient_name) && !empty($feedback_text)) {
        // Check if doctor_id exists in the doctors table
        $checkDoctorSql = "SELECT id FROM doctori WHERE id = ?";
        $stmt = $conn->prepare($checkDoctorSql);
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Doctor ID exists, proceed with inserting feedback
            $stmt->bind_result($doctor_id);
            $stmt->fetch();
            $stmt->close();

            // Insert feedback with doctor_id
            $insertSql = "INSERT INTO feedback (doctor_id, patient_name, feedback_text) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("iss", $doctor_id, $patient_name, $feedback_text);

            if ($stmt->execute()) {
                echo "Feedback-ul a fost trimis cu succes!";
            } else {
                echo "Eroare: " . $stmt->error;
            }

            $stmt->close();
        } else {
            // Doctor ID does not exist
            echo "ID-ul doctorului nu este valid.";
        }
    } else {
        echo "Toate câmpurile sunt obligatorii.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <a href="index.php" class="btn btn-primary">Înapoi la Pagina Principală</a>
</head>
</html>