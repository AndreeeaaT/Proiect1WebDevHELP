<?php
// Detalii pentru conectarea la baza de date
$servername = "mysql_db"; // Numele serverului MySQL
$username = "root"; // Numele utilizatorului MySQL
$password = "toor"; // Parola utilizatorului MySQL
$database = "spital"; // Numele bazei de date MySQL

// Crearea conexiunii
$conn = new mysqli($servername, $username, $password, $database);

// Verificarea conexiunii
if ($conn->connect_error) {
    throw new Exception("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Setare caracter set pentru conexiune
if (!mysqli_set_charset($conn, "utf8")) {
    throw new Exception("Setarea caracter set a eșuat: " . mysqli_error($conn));
}

// Returnarea conexiunii