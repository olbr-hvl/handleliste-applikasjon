<?php

function getMissingKeys($array, $keys) {
    $missingKeys = array_diff($keys, array_keys($array));

    return count($missingKeys) !== 0 ? $missingKeys : null;
}

function getRequestBodyJson() {
    $requestBody = file_get_contents('php://input');
    return json_decode($requestBody, true);
}