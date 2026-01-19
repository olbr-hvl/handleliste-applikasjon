<?php

function getMissingKeys($array, $keys) {
    $missingKeys = array_diff($keys, array_keys($array));

    return count($missingKeys) !== 0 ? $missingKeys : null;
}

function getRequestBodyJson() {
    $requestBody = file_get_contents('php://input');
    return json_decode($requestBody, true);
}

function accountHasAccessToShoppingList($db, $accountId, $shoppingListId) {
    $statement = $db->prepare(<<<SQL
        SELECT EXISTS (
            SELECT 1
            FROM shoppinglist
            WHERE id = ? AND account = ?
        );
    SQL);
    $statement->execute([$shoppingListId, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}

function accountHasAccessToShoppingListItem($db, $accountId, $shoppingListItemId) {
    $statement = $db->prepare(<<<SQL
        SELECT EXISTS (
            SELECT 1
            FROM shoppinglistitem
            LEFT JOIN shoppinglist ON shoppinglistitem.shoppinglist = shoppinglist.id
            WHERE shoppinglistitem.id = ? AND shoppinglist.account = ?
        );
    SQL);
    $statement->execute([$shoppingListItemId, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}