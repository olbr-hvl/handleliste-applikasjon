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

    // TODO: Confirm strength of password.

    // Create account

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $db = new PDO(PDO_DSN);

    try {
        $statement = $db->prepare(<<<SQL
            INSERT INTO account (email, hashed_password)
            VALUES (?, ?);
        SQL);
        $statement->execute([$email, $hashedPassword]);
    } catch (PDOException $exception) {
        $errorCode = $exception->getCode();

        if ($errorCode === '23000') {
            exit(json_encode(['success' => false, 'error' => 'Det finnes allerede en konto for denne emailen.']));
        }

        throw $exception;
    }

    // Sign in to account

    $accountId = $db->lastInsertId();

    session_start();

    $_SESSION['account'] = [
        'id' => $accountId
    ];
}