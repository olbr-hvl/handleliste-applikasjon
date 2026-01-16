<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header("Content-Type: application/json");

    require '../../../config.php';
    // require '../../api-functions.php';

    // Data validation

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å hente ut en oversikt over alle handlelister.']));
    }

    $accountId = $_SESSION['account']['id'];

    // Get shoppinglists

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        SELECT id, name
        FROM shoppinglist
        WHERE account = ?;
    SQL);
    $statement->execute([$accountId]);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Response

    exit(json_encode(['success' => true, 'data' => $result]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    require '../../../config.php';
    require '../../api-functions.php';

    $data = getRequestBodyJson();

    // Data validation

    if (getMissingKeys($data, ['name'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $name = $data['name'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å opprette en ny handleliste.']));
    }

    $accountId = $_SESSION['account']['id'];

    // Create shoppinglist

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        INSERT INTO shoppinglist (name, account)
        VALUES (?, ?);
    SQL);
    $statement->execute([$name, $accountId]);

    // Response
    
    $shoppingListId = $db->lastInsertId();

    http_response_code(201);
    exit(json_encode(['success' => true, 'data' => ['id' => $shoppingListId]]));
}

if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    header("Content-Type: application/json");

    require '../../../config.php';
    require '../../api-functions.php';

    // Data validation

    $data = getRequestBodyJson();

    if (getMissingKeys($data, ['name'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $name = $data['name'];

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $shoppingListId = $_GET['id'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å slette handlelisten.']));
    }

    $accountId = $_SESSION['account']['id'];

    // Edit shoppinglist

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        UPDATE shoppinglist
        SET name = ?
        WHERE id = ? AND account = ?;
    SQL);
    $statement->execute([$name, $shoppingListId, $accountId]);

    // Response

    exit(json_encode(['success' => true]));
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    header("Content-Type: application/json");

    require '../../../config.php';
    // require '../../api-functions.php';

    // Data validation

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $shoppingListId = $_GET['id'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å slette handlelisten.']));
    }

    $accountId = $_SESSION['account']['id'];

    // Delete shoppinglist

    $db = new PDO(PDO_DSN);

    $statement = $db->prepare(<<<SQL
        DELETE FROM shoppinglist
        WHERE id = ? AND account = ?;
    SQL);
    $statement->execute([$shoppingListId, $accountId]);

    // Response

    exit(json_encode(['success' => true]));
}