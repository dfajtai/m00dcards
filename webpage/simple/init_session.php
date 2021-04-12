<?php
function generateNewGame()
{
    global $database;
    $new_id = uniqid("game_", true);
    //echo "<script> console.log('". $new_id ."') </script>";
    $database->insert("sessions", ["game_id" => $new_id, "indices" => ""]);
    return $new_id;
}
function hasGameId($gameID){
    $dbGameID ;
    
}

if (!htmlspecialchars($_GET["gameID"])) {
    #no gameID in url
    //echo "<script> console.log('no gameID in url') </script>";
    if (!isset($_COOKIE["gameID"])) {
        #no gameID in cookies
        //echo "<script> console.log('no gameID in cookies') </script>";
        $gameID = get_new_ID();
    } else {
        $gameID = $_COOKIE["gameID"];
        if ($database->count("sessions", ["game_id" => $gameID]) == 0) {
            #id unknown to database
            //echo "<script> console.log('gameID stored in cookies unknown to database') </script>";
            setcookie("gameID","", 0);
            $gameID = get_new_ID();
        }
    }
    setcookie("gameID", $gameID, time()+30*24*60*60);
    $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: " . $current_uri . "/?gameID=" . $gameID);
    exit;
} else {
    $gameID = htmlspecialchars($_GET["gameID"]);
    if ($database->count("sessions", ["game_id" => $gameID]) == 0) {
        #id unknown to database
        $gameID = get_new_ID();
        $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $current_uri = explode("?", $current_uri)[0];
        $current_uri = substr($current_uri,0,-1);
        header("Location: " . $current_uri . "/?gameID=" . $gameID);
        exit;
    }
}