<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    require '../../../config.php';
    require '../../api-functions.php';
    
    $data = getRequestBodyJson();

    // Data validation

    if (getMissingKeys($data, ['email', 'password'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $email = $data['email'];
    $password = $data['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Ugyldig e-postadresse.']));
    }

    if (strlen($password) < 16) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Passordet er for svakt, passordet må bestå av minst 16 tegn.']));
    }

    // Create account

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        INSERT OR IGNORE INTO account (email, hashed_password)
        VALUES (?, ?);
    SQL);
    $statement->execute([$email, $hashedPassword]);

    // Sign in to account

    $accountId = $db->lastInsertId();

    if ($accountId !== '0') {
        session_start();

        $_SESSION['account'] = [
            'id' => $accountId
        ];
    }

    // Response

    exit(json_encode(['success' => true]));
}