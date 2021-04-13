<?php
function get_last_state($gameID)
{
    global $database;
    $state = $database->select("sessions", "*", ["game_id" => getDbGameID($gameID)]);
    //return $state;
    $last_state = json_decode($state[0]["last_state"]);
    $selected = json_decode($state[0]["selected"]);
    $state_counter = count($last_state);
    return array("last_state"=>$last_state,"selected"=>$selected, "state_counter"=>$state_counter);
}
if (isset($_GET['get_status'])) {
    #get current status with ajax
    echo get_last_state($gameID);
}