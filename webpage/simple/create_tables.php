<?php
    #create tables
    try {
        $database->create("sessions", [
            "session_id" => [
                "VARCHAR(30)",
                "NOT NULL",
                "PRIMARY KEY"
            ],
            "indices" => [
                "TEXT",
                "NOT NULL"
            ],
        ]);
    
        $database->create("states", [
            "session_id" => [
                "VARCHAR(30)",
                "NOT NULL",
                "PRIMARY KEY"
            ],
            "state" => [
                "TEXT",
                "NOT NULL"
            ],
            "selected" => [
                "INT",
                "NOT NULL",
                "DEFAULT -1"
            ],
        ]);

    } catch (Exception $e) {
        $error = 1;
    }
    
?>