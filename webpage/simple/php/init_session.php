<?php
use Hashids\Hashids;
$hashids = new Hashids('m00dcards', 20);
$nums = range(1, $CARD_COUNT);
session_start();
function generateNewGame()
{
    global $hashids;
    global $database;
    global $CARD_COUNT;
    global $nums;
    shuffle($nums);
    $database->insert("sessions", ["indices" => json_encode($nums)]);
    $dbGameID = $database->id();
    return $hashids->encode($dbGameID);
}
function getDbGameID($gameID){
    global $hashids;
    return $hashids->decode($gameID);
}
function validateGameId($gameID){
    global $database;
    $dbGameID = getDbGameID($gameID);
    return $database->count("sessions", ["game_id" => $dbGameID]) > 0;
}
function getGameInit($gameID){
    global $database;
    $init_state = $database->select("sessions", "indices", ["game_id" => getDbGameID($gameID)]);
    $nums = json_decode($init_state[0]);
    return $nums;
}
function redirectToGame($gameID){
    $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $current_uri = explode("?", $current_uri)[0];
    $current_uri = substr($current_uri,0,-1);
    header("Location: " . $current_uri . "/?gameID=" . htmlspecialchars($gameID));
    exit;
}
if (isset($_GET["gameID"]) && validateGameId(htmlspecialchars_decode($_GET["gameID"]))) {
    #valid gameID in url
    $gameID = htmlspecialchars_decode($_GET["gameID"]);
    $_SESSION['gameID'] = $gameID;
    $nums = getGameInit($gameID);
} 
else {
    $gameID = generateNewGame();
    $_SESSION['gameID'] = $gameID;
    redirectToGame($gameID);
}