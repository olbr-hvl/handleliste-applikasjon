<?php

header("Content-Type: application/json");

session_start();

unset($_SESSION["account"]);

exit(json_encode(['success' => true]));