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
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Feil email eller passord.'])); 
    }

    $accountId = $result['id'];
    $hashedPassword = $result['hashed_password'];

    if (!password_verify($password, $hashedPassword)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Feil email eller passord.']));
    }

    // Rehash password if current hash uses an old algorithm/options

    if (password_needs_rehash($hashedPassword, PASSWORD_DEFAULT)) {
        $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement = $db->prepare(<<<SQL
            UPDATE account
            SET hashed_password = ?
            WHERE id = ?;
        SQL);
        $statement->execute([$newHashedPassword, $accountId]);
    }

    // Sign in to account

    session_start();

    $_SESSION['account'] = [
        'id' => $accountId
    ];

    exit(json_encode(['success' => true]));
}