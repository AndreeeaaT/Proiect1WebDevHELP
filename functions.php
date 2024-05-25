<?php

function db() {
    // Înlocuiește aceste detalii cu detaliile reale de conectare la baza de date
    $host = 'mysql_db';
    $dbname = 'spital';
    $username = 'root';
    $password = 'toor';

    // Creează o conexiune PDO
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Setează PDO să arunce excepții în caz de erori
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Gestionează erorile de conectare
        die("Conexiunea a eșuat: " . $e->getMessage());
    }
}
function redirect_to_profile(string $user_type) {
    switch ($user_type) {
        case 'pacient':
            header("Location: pacient_acount.php", true, 301);
            break;
        case 'doctor':
            header("Location: doctor_account.php", true, 301);
            break;
        case 'admin':
            header("Location: admin_page.php", true, 301);
            break;
        
    }
    exit();
}

function redirect_with_profile(string $user_type) {
    switch ($user_type) {
        case 'pacient':
            header("Location: pacient_acount.php", true, 303);
            break;
        case 'doctor':
            header("Location: doctor_account.php", true, 303);
            break;
        case 'admin':
            header("Location: admin_page.php", true, 303);
            break;
    }
    exit();
}
function log_user_in(array $user): bool {
    // prevent session fixation attack
    if (session_regenerate_id()) {
        // set username & id in the session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        // redirect to user's profile page
        redirect_to_profile($user['type']); // Presupunând că aveți o cheie 'type' în array-ul utilizatorului care indică tipul acestuia
        return true;
    }
    return false;
}


function login(string $username, string $password, bool $remember = false): bool {
    $user = find_user_by_username($username);

    // if user found, check the password
    if ($user && password_verify($password, $user['password'])) {
        log_user_in($user);
        if ($remember) {
            remember_me($user['id']);
        }
        return true;
    }
    return false;
}

function token_is_valid(string $selector, string $validator): bool {
    $tokens = find_user_token_by_selector($selector);
    if (!$tokens) {
        return false;
    }
    return password_verify($validator, $tokens['hashed_validator']);
}

function find_user_by_token(string $token) {
    $tokens = parse_token($token);
    if (!$tokens) {
        return null;
    }

    $sql = 'SELECT utilizatori.id, user_name
            FROM utilizatori
            INNER JOIN utilizator_tokens ON user_id = utilizatori.id
            WHERE selector = :selector AND expiry > now()
            LIMIT 1';

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $tokens[0]);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function is_user_logged_in(): bool {
    // check the session
    if (isset($_SESSION['username'])) {
        return true;
    }

    // check the remember_me in cookie
    $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

    if ($token) {
        $tokenParts = parse_token($token);
        if ($tokenParts && token_is_valid($tokenParts[0], $tokenParts[1])) {
            $user = find_user_by_token($token);
            if ($user) {
                return log_user_in($user);
            }
        }
    }
    return false;
}

function find_user_by_username(string $username) {
    $sql = 'SELECT id
            FROM utilizatori
            WHERE user_name = :username
            LIMIT 1';

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function find_user_token_by_selector(string $selector) {
    $sql = 'SELECT id, selector, hashed_validator, user_id, expiry
            FROM utilizator_tokens
            WHERE selector = :selector AND expiry >= now()
            LIMIT 1';

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $selector);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function logout(): void {
    if (is_user_logged_in()) {
        // delete the user token
        delete_user_token($_SESSION['user_id']);

        // delete session
        unset($_SESSION['username'], $_SESSION['user_id']);

        // remove the remember_me cookie
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_user', null, -1);
        }

        // remove all session data
        session_destroy();

        // redirect to the login page
        redirect_to('login.php');
    }
}

function remember_me(int $user_id, int $day = 30) {
    [$selector, $validator, $token] = generate_tokens();

    // remove all existing token associated with the user id
    delete_user_token($user_id);

    // set expiration date
    $expired_seconds = time() + 60 * 60 * 24 * $day;

    // insert a token to the database
    $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', $expired_seconds);

    if (insert_user_token($user_id, $selector, $hash_validator, $expiry)) {
        setcookie('remember_me', $token, $expired_seconds);
    }
}

function delete_user_token(int $user_id): bool {
    $sql = 'DELETE FROM utilizator_tokens WHERE user_id = :user_id';
    $statement = db()->prepare($sql);
    $statement->bindValue(':user_id', $user_id);
    return $statement->execute();
}

function insert_user_token(int $user_id, string $selector, string $hashed_validator, string $expiry): bool {
    $sql = 'INSERT INTO user_tokens(user_id, selector, hashed_validator, expiry)
            VALUES(:user_id, :selector, :hashed_validator, :expiry)';

    $statement = db()->prepare($sql);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':selector', $selector);
    $statement->bindValue(':hashed_validator', $hashed_validator);
    $statement->bindValue(':expiry', $expiry);
    return $statement->execute();
}

function generate_random_bytes(int $length) {
    return bin2hex(random_bytes($length));
}

function generate_tokens(): array {
    $selector = generate_random_bytes(16);
    $validator = generate_random_bytes(32);
    return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token): ?array {
    $parts = explode(':', $token);
    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function redirect_to(string $location) {
    header($location, true, 301);
    exit();
}

function redirect_with(string $location) {
    header($location, true, 303);
    exit();
}
