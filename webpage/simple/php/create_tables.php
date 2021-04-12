<?php
#create tables
try {
    $database->create("sessions", [
        "game_id" => [
            "INT",
            "NOT NULL",
            "AUTO_INCREMENT",
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
            "INT",
            "NOT NULL",
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
        "PRIMARY KEY (game_id,ts)"
    ]);
} catch (Exception $e) {
    $error = 1;
}