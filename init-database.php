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
    CREATE TABLE IF NOT EXISTS shoppinglist (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        account INTEGER NOT NULL REFERENCES account (id) ON DELETE CASCADE
    );
SQL);

$db->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS shoppinglistitem (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        bought INTEGER NOT NULL,
        shoppinglist INTEGER NOT NULL REFERENCES shoppinglist (id) ON DELETE CASCADE
    );
SQL);