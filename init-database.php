<?php

// sette opp database
// denne filen er tenkt å bli kjørt av dem som skal drifte applikasjonen for å initialisere databasen.

require 'config.php';

$db = new PDO(PDO_DSN);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS account (
        id INTEGER PRIMARY KEY,
        email TEXT NOT NULL UNIQUE,
        hashed_password TEXT NOT NULL
    );
SQL);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS shopping_list (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        account INTEGER NOT NULL REFERENCES account (id) ON DELETE CASCADE
    );
SQL);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS shopping_list_item (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        bought INTEGER NOT NULL DEFAULT FALSE,
        shopping_list INTEGER NOT NULL REFERENCES shopping_list (id) ON DELETE CASCADE
    );
SQL);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS shopping_list_order (
        id INTEGER PRIMARY KEY,
        sort_order INTEGER NOT NULL,
        shopping_list INTEGER NOT NULL UNIQUE ON CONFLICT REPLACE REFERENCES shopping_list (id) ON DELETE CASCADE
    );
SQL);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS shopping_list_item_order (
        id INTEGER PRIMARY KEY,
        sort_order INTEGER NOT NULL,
        shopping_list_item INTEGER NOT NULL UNIQUE ON CONFLICT REPLACE REFERENCES shopping_list_item (id) ON DELETE CASCADE
    );
SQL);