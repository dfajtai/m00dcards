<?php
function generateNewGame()
{
    global $hashids;
    global $database;
    global $init_state;
    global $current_state;
    global $selected_index;
    shuffle($init_state);
    //$database->insert("sessions", ["init_state" => json_encode($init_state),"last_state"=>json_encode(array(0=>9,1=>6)),"selected"=>$selected_index]);
    $database->insert("sessions", ["init_state" => json_encode($init_state),"last_state"=>json_encode($current_state),"selected"=>$selected_index]);
    $dbGameID = $database->id();
    return $hashids->encode($dbGameID);
}
function getDbGameID($gameID){
    global $hashids;
    return ($hashids->decode($gameID))[0];
}
function validateGameId($gameID){
    global $database;
    $dbGameID = getDbGameID($gameID);
    return $database->count("sessions", ["game_id" => $dbGameID]) > 0;
}
function getGameInit($gameID){
    global $database;
    $state = $database->select("sessions", "*", ["game_id" => getDbGameID($gameID)]);
    $init_state = json_decode($state[0]["init_state"]);
    $last_state = json_decode($state[0]["last_state"]);
    $selected = json_decode($state[0]["selected"]);
    return array("init_state"=>$init_state,"last_state"=>$last_state,"selected"=>$selected);
}
function redirectToGame($gameID){
    $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $current_uri = explode("?", $current_uri)[0];
    $current_uri = substr($current_uri,0,-1);
    header("Location: " . $current_uri . "/?gameID=" . htmlspecialchars($gameID));
    exit;
}