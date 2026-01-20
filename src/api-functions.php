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
            FROM shopping_list
            WHERE id = ? AND account = ?
        );
    SQL);
    $statement->execute([$shoppingListId, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}

function accountHasAccessToShoppingLists($db, $accountId, $shoppingListIds) {
    $shoppingListIdsCount = count($shoppingListIds);
    $shoppingListIdsPlaceholders = implode(', ', array_fill(0, $shoppingListIdsCount, '?'));

    $statement = $db->prepare(<<<SQL
        SELECT COUNT(*) = CAST(? AS INTEGER)
        FROM shopping_list
        WHERE id IN ($shoppingListIdsPlaceholders) AND account = ?;
    SQL);
    $statement->execute([$shoppingListIdsCount, ...$shoppingListIds, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}

function accountHasAccessToShoppingListItem($db, $accountId, $shoppingListItemId) {
    $statement = $db->prepare(<<<SQL
        SELECT EXISTS (
            SELECT 1
            FROM shopping_list_item
            LEFT JOIN shopping_list ON shopping_list_item.shopping_list = shopping_list.id
            WHERE shopping_list_item.id = ? AND shopping_list.account = ?
        );
    SQL);
    $statement->execute([$shoppingListItemId, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}

function accountHasAccessToShoppingListItems($db, $accountId, $shoppingListItemIds) {
    $shoppingListItemIdsCount = count($shoppingListItemIds);
    $shoppingListItemIdsPlaceholders = implode(', ', array_fill(0, $shoppingListItemIdsCount, '?'));

    $statement = $db->prepare(<<<SQL
        SELECT COUNT(*) = CAST(? AS INTEGER)
        FROM shopping_list_item
        LEFT JOIN shopping_list ON shopping_list_item.shopping_list = shopping_list.id
        WHERE shopping_list_item.id IN ($shoppingListItemIdsPlaceholders) AND shopping_list.account = ?
    SQL);
    $statement->execute([$shoppingListItemIdsCount, ...$shoppingListItemIds, $accountId]);
    $result = $statement->fetchColumn();

    return (boolean) $result;
}