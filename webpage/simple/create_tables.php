<?php
#create tables
try {
    $database->create("sessions", [
        "game_id" => [
            "VARCHAR(30)",
            "NOT NULL",
            "PRIMARY KEY",
        ],
        "indices" => [
            "TEXT",
            "DEFAULT NULL",
        ],
        "ts" => [
            "TIMESTAMP",
            "DEFAULT CURRENT_TIMESTAMP",
        ],
    ]);
    $database->create("states", [
        "game_id" => [
            "VARCHAR(30)",
            "NOT NULL",
            "PRIMARY KEY",
        ],
        "state" => [
            "TEXT",
            "NOT NULL",
        ],
        "selected" => [
            "INT",
            "NOT NULL",
            "DEFAULT -1",
        ],
        "ts" => [
            "TIMESTAMP",
            "DEFAULT CURRENT_TIMESTAMP",
        ],
    ]);
} catch (Exception $e) {
    $error = 1;
}