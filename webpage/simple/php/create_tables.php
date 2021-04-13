<?php
#create tables
try {
    $database->create("sessions", [
        "game_id" => [
            "INT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY"
        ],
        "init_state" => [
            "TEXT",
            "DEFAULT NULL"
        ],
        "last_state" => [
            "TEXT",
            "DEFAULT NULL"
        ],
        "selected" =>[
            "TEXT",
            "DEFAULT NULL"
        ],
        "ts" => [
            "TIMESTAMP",
            "DEFAULT CURRENT_TIMESTAMP"
        ],
    ]);
} catch (Exception $e) {
    $error = 1;
}