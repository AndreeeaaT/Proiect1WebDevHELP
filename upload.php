<?php
include "db_conn.php";
session_start();

$uploadMessage = ""; // Mesajul de încărcare a fișierului

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificăm dacă fișierul este o imagine reală
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadMessage = "Fișierul nu este o imagine.";
        $uploadOk = 0;
    }

    // Verificăm dacă fișierul există deja
    if (file_exists($target_file)) {
        $target_file = $target_dir . uniqid() . '.' . $imageFileType; // Renumește fișierul pentru a evita conflictele de nume
    }

    // Verificăm dimensiunea fișierului
    if ($_FILES["profile_image"]["size"] > 500000) {
        $uploadMessage = "Fișierul este prea mare.";
        $uploadOk = 0;
    }

    // Permitem doar anumite formate de fișier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadMessage = "Sunt permise doar fișierele JPG, JPEG, PNG și GIF.";
        $uploadOk = 0;
    }

    // Verificăm dacă $uploadOk este setat la 0 din cauza unei erori
    if ($uploadOk == 0) {
        $uploadMessage = "Fișierul nu a fost încărcat.";
    } else {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE utilizatori SET profil_img='$target_file' WHERE id=$userId";
            if (mysqli_query($conn, $sql)) {
                $uploadMessage = "Imaginea a fost încărcată cu succes.";
            } else {
                $uploadMessage = "Eroare la actualizarea imaginii în baza de date: " . mysqli_error($conn);
            }
            mysqli_close($conn);
            header("Location: pacient_acount.php");
            exit();
        } else {
            $uploadMessage = "A apărut o eroare la încărcarea fișierului.";
        }
    }
}

echo $uploadMessage; // Afiseaza mesajul de incarcare a fisierului