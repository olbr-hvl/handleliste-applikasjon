<?php

if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    header("Content-Type: application/json");

    require '../../../../../config.php';
    require '../../../../api-functions.php';

    // Data validation

    $data = getRequestBodyJson();

    // Check if $data is an array and contains only integers
    if (!is_array($data) || count(array_filter($data, 'is_int')) !== count($data)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Manglende data.']));
    }

    $shoppingListItemIds = $data;

    session_start();

    if (!array_key_exists('account', $_SESSION)) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'error' => 'Du må være logget inn for å endre rekkefølgen på handlelistene.']));
    }

    $accountId = $_SESSION['account']['id'];

    $db = new PDO(PDO_DSN);

    if (!accountHasAccessToShoppingListItems($db, $accountId, $shoppingListItemIds)) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Handlelistene eksisterer ikke eller du har ikke tilgang til disse handlelistene.']));
    }

    // Edit shopping list item order

    $valuesClause = implode(",\n           ", array_fill(0, count($shoppingListItemIds), '(?, ?)'));
    $params = array_merge(...array_map(fn($sortOrder, $shoppingListItemId) => [$sortOrder, $shoppingListItemId], array_keys($shoppingListItemIds), $shoppingListItemIds));

    $statement = $db->prepare(<<<SQL
        INSERT INTO shopping_list_item_order (sort_order, shopping_list_item)
        VALUES $valuesClause;
    SQL);
    $statement->execute($params);

    // Response

    exit(json_encode(['success' => true]));
}