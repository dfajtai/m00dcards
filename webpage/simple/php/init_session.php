<?php
use Hashids\Hashids;
$hashids = new Hashids('m00dcards', 20);
if (isset($_GET["gameID"]) && validateGameId(htmlspecialchars_decode($_GET["gameID"]))) {
    #valid gameID in url
    $gameID = htmlspecialchars_decode($_GET["gameID"]);
    $_SESSION['gameID'] = $gameID;
    $init = getGameInit($gameID);
    $init_state = $init["init_state"];
    $current_state = $init["last_state"];
    $state_counter = count($current_state);
    $selected_index = $init["selected"];
} 
else {
    $gameID = generateNewGame();
    $_SESSION['gameID'] = $gameID;
    redirectToGame($gameID);
}