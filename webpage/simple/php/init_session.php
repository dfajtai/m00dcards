<?php
use Hashids\Hashids;
$hashids = new Hashids('m00dcards', 20);
session_start();
function generateNewGame()
{
    global $hashids;
    global $database;
    $database->insert("sessions", ["indices" => ""]);
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
} 
else {
    $gameID = generateNewGame();
    $_SESSION['gameID'] = $gameID;
    redirectToGame($gameID);
}