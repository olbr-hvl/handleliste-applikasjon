<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header("Content-Type: application/json");

    require '../../../config.php';
    require '../../api-functions.php';

    // Data validation

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å hente ut en oversikt over alle handlelister.']));
    }

    $accountId = $_SESSION['account']['id'];

    if (isset($_GET['id'])) {
        $shoppingListId = $_GET['id'];

        $db = new PDO(PDO_DSN);

        if (!accountHasAccessToShoppingList($db, $accountId, $shoppingListId)) {
            http_response_code(404);
            exit(json_encode(['success' => false, 'error' => 'Handlelisten eksisterer ikke eller du har ikke tilgang til denne handlelisten.']));
        }

        // Get shopping list
    
        $statement = $db->prepare(<<<SQL
            SELECT name
            FROM shopping_list
            WHERE id = ?;
        SQL);
        $statement->execute([$shoppingListId]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        if ($result === false) {
            http_response_code(404);
            exit(json_encode(['success' => false, 'error' => 'Fant ikke handlelisten.']));
        }

        // Response
    
        exit(json_encode(['success' => true, 'data' => $result]));
    } else {
        // Get shopping lists
    
        $db = new PDO(PDO_DSN);
    
        $statement = $db->prepare(<<<SQL
            SELECT shopping_list.id, shopping_list.name
            FROM shopping_list
            LEFT JOIN shopping_list_order ON shopping_list.id = shopping_list_order.shopping_list
            WHERE shopping_list.account = ?
            ORDER BY shopping_list_order.sort_order NULLS LAST
        SQL);
        $statement->execute([$accountId]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        // Response
    
        exit(json_encode(['success' => true, 'data' => $result]));
    }
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

    // Create shopping list

    $db = new PDO(PDO_DSN);

    $db->exec('PRAGMA foreign_keys = ON;');

    $statement = $db->prepare(<<<SQL
        INSERT INTO shopping_list (name, account)
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
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å endre handlelisten.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingList($db, $accountId, $shoppingListId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Handlelisten eksisterer ikke eller du har ikke tilgang til denne handlelisten.']));
    }

    // Edit shopping list

    $statement = $db->prepare(<<<SQL
        UPDATE shopping_list
        SET name = ?
        WHERE id = ?;
    SQL);
    $statement->execute([$name, $shoppingListId]);

    // Response

    exit(json_encode(['success' => true]));
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    header("Content-Type: application/json");

    require '../../../config.php';
    require '../../api-functions.php';

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

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingList($db, $accountId, $shoppingListId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Handlelisten eksisterer ikke eller du har ikke tilgang til denne handlelisten.']));
    }

    // Delete shopping list

    $db->exec('PRAGMA foreign_keys = ON;');

    $statement = $db->prepare(<<<SQL
        DELETE FROM shopping_list
        WHERE id = ?;
    SQL);
    $statement->execute([$shoppingListId]);

    // Response

    exit(json_encode(['success' => true]));
}