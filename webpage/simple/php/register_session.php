<?php
$init_state = $database->select("sessions", "indices", ["game_id" => getDbGameID($gameID)]);
if ($init_state[0] != "") {
    $nums = json_decode($init_state[0]);
} else {
    $nums = range(1, $CARD_COUNT);
    shuffle($nums);
    $database->update("sessions", ["indices" => json_encode($nums)], ["game_id" => getDbGameID($gameID)]);
}