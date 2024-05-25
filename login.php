<?php
include "db_conn.php";
include "functions.php"; // Include fisierul functions.php care contine functiile de criptare/decriptare
session_start();

// Functia de criptare si decriptare
function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'my_secret_key';
    $secret_iv = 'my_secret_iv';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

// Verificare cookie-uri si setare sesiune daca utilizatorul nu este deja autentificat
if (isset($_COOKIE['username']) && isset($_COOKIE['user_type']) && isset($_COOKIE['password']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['user_type'] = $_COOKIE['user_type'];
    
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    $user_type = $_COOKIE['user_type'];

    $sql = "SELECT * FROM utilizatori WHERE user_name='$username' AND parola='$password' AND tip_utilizator='$user_type'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        if ($_SESSION['user_type'] == 'administrator') {
            header("Location: admin_page.php");
            exit();
        } elseif ($_SESSION['user_type'] == 'pacient') {
            header("Location: pacient_acount.php");
            exit();
        } elseif ($_SESSION['user_type'] == 'doctor') {
            header("Location: doctor_account.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}

$error_message = "";

if (isset($_POST['login'])) {
    $username = $_POST['uname'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "SELECT * FROM utilizatori WHERE user_name='$username' AND parola='$password' AND tip_utilizator='$user_type'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user_type;

        if (!empty($_POST['remember'])) {
            $encrypted_password = encrypt_decrypt('encrypt', $password);

            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('password', $encrypted_password, time() + (86400 * 30), "/");
            setcookie('user_type', $user_type, time() + (86400 * 30), "/");
        } else {
            setcookie('username', '', time() - 3600, "/");
            setcookie('password', '', time() - 3600, "/");
            setcookie('user_type', '', time() - 3600, "/");
        }

        if ($user_type == 'administrator') {
            header("Location: admin_page.php");
            exit();
        } elseif ($user_type == 'pacient') {
            header("Location: pacient_acount.php");
            exit();
        } elseif ($user_type == 'doctor') {
            header("Location: doctor_account.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        $error_message = "Nume de utilizator sau parolă incorectă!";
    }
}

if (!empty($error_message)) {
    echo "<p>$error_message</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    

    <!-- Formularul de login -->
    <form action="login.php" method="post">
        <label for="uname">Nume de utilizator:</label>
        <input type="text" id="uname" name="uname" required><br>

        <label for="password">Parola:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="user_type">Tip utilizator:</label>
        <select id="user_type" name="user_type" required>
            <option value="administrator">Administrator</option>
            <option value="pacient">Pacient</option>
            <option value="doctor">Doctor</option>
        </select><br>

        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Remember Me</label><br>

        <input type="submit" name="login" value="Login">
    </form>
    <p>Nu ai cont? <br> <a href="register1.php">Înregistrează-te aici</a></p>
</body>
</html>
