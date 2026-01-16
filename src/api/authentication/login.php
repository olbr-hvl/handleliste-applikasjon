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

    // Confirm credentials

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        SELECT id, hashed_password
        FROM account
        WHERE email = ?;
    SQL);
    $statement->execute([$email]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Feil email eller passord.'])); 
    }

    $accountId = $result['id'];
    $hashedPassword = $result['hashed_password'];

    if (!password_verify($password, $hashedPassword)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Feil email eller passord.']));
    }

    // TODO: use password_needs_rehash to check if the stored hash uses an old algorithm/options; if true update the stored hash

    // Sign in to account

    session_start();

    $_SESSION['account'] = [
        'id' => $accountId
    ];
}