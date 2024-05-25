<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
    // Include fișierul de conexiune la baza de date
    include "db_conn.php";

    // Verifică dacă datele au fost trimise prin metoda POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifică existența cheilor în array-ul $_POST
        $username = isset($_POST['username']) ? validate($_POST['username']) : null;
        $password = isset($_POST['password']) ? validate($_POST['password']) : null;
        $user_type = isset($_POST['user_type']) ? validate($_POST['user_type']) : null;

        if ($username && $password && $user_type) {
            // Inserează datele în baza de date fără a cripta parola
            $sql = "INSERT INTO utilizatori (user_name, parola, tip_utilizator) VALUES ('$username', '$password', '$user_type')";

            if (mysqli_query($conn, $sql)) {
                echo "Utilizatorul a fost înregistrat cu succes!";
                
                
            } else {
                echo "Eroare la înregistrarea utilizatorului: " . mysqli_error($conn);
            }
        } else {
            echo "Toate câmpurile sunt obligatorii!";
        }
    }

    // Funcție pentru validarea datelor introduse
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <!-- Selector pentru tipul de utilizator -->
        <label for="user_type">User Type:</label>
        <select name="user_type" id="user_type">
            <option value="pacient">Pacient</option>
            <option value="doctor">Doctor</option>
            <option value="administrator">Administrator</option>
        </select><br><br>
        
        <!-- Recaptcha -->
        <div class="g-recaptcha" data-sitekey="6Lf06dYpAAAAAMCFAnnNtq0A5lkTrCu60-tXpkqn" style="text-align: center"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        
        <button type="submit">Register</button>
    </form>

    <a href="register.php">Back to login page</a>
</body>
</html>
<?php
mysqli_close($conn);
?>
