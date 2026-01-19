<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header("Content-Type: application/json");

    require '../../../../config.php';
    require '../../../api-functions.php';

    // Data validation

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }
    
    $shoppingListId = $_GET['id'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å hente ut en oversikt over alle varene i handlelisten.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingList($db, $accountId, $shoppingListId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Handlelisten eksisterer ikke eller du har ikke tilgang til denne handlelisten.']));
    }

    // Get shoppinglist items

    $statement = $db->prepare(<<<SQL
        SELECT id, name, bought
        FROM shoppinglistitem
        WHERE shoppinglist = ?;
    SQL);
    $statement->execute([$shoppingListId]);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Response

    exit(json_encode(['success' => true, 'data' => $result]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    require '../../../../config.php';
    require '../../../api-functions.php';

    $data = getRequestBodyJson();

    // Data validation

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
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å legge til vare.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingList($db, $accountId, $shoppingListId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Handlelisten eksisterer ikke eller du har ikke tilgang til denne handlelisten.']));
    }

    // Create shoppinglist item

    $statement = $db->prepare(<<<SQL
        INSERT INTO shoppinglistitem (name, bought, shoppinglist)
        VALUES (?, ?, ?);
    SQL);
    $statement->execute([$name, 0, $shoppingListId]);

    // Response
    
    $shoppingListItemId = $db->lastInsertId();

    http_response_code(201);
    exit(json_encode(['success' => true, 'data' => ['id' => $shoppingListItemId]]));
}

if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    header("Content-Type: application/json");

    require '../../../../config.php';
    require '../../../api-functions.php';

    // Data validation

    $data = getRequestBodyJson();

    $editableKeys = ['name', 'bought'];
    $editableData = array_intersect_key($data, array_flip($editableKeys));

    if (count($editableData) === 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $shoppingListItemId = $_GET['id'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å endre varen.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingListItem($db, $accountId, $shoppingListItemId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Varen eksisterer ikke eller du har ikke tilgang til denne varen.']));
    }

    // Edit shoppinglist item

    $setClause = implode(",\n    ", array_map(fn($column) => "$column = ?", array_keys($editableData)));

    $statement = $db->prepare(<<<SQL
        UPDATE shoppinglistitem
        SET $setClause
        WHERE id = ?;
    SQL);
    $statement->execute([...array_values($editableData), $shoppingListItemId]);

    // Response

    exit(json_encode(['success' => true]));
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    header("Content-Type: application/json");

    require '../../../../config.php';
    require '../../../api-functions.php';

    // Data validation

    if (!isset($_GET['id'])) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $shoppingListItemId = $_GET['id'];

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å slette varen.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingListItem($db, $accountId, $shoppingListItemId)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Varen eksisterer ikke eller du har ikke tilgang til denne varen.']));
    }

    // Delete shoppinglist item

    $statement = $db->prepare(<<<SQL
        DELETE FROM shoppinglistitem
        WHERE id = ?;
    SQL);
    $statement->execute([$shoppingListItemId]);

    // Response

    exit(json_encode(['success' => true]));
}