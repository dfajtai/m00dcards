<?php
$CARD_COUNT = 62; #number of cards in deck
$init_state = range(1, $CARD_COUNT);
session_start();
require_once '../vendor/autoload.php';

use Hashids\Hashids;
$hashids = new Hashids('m00dcards', 20);

require_once 'init_functions.php';
include 'connect.php';
include 'create_tables.php';

$current_state = array();
$state_counter = 0;
$selected_index = -1;
$gameID = generateNewGame();

$selected_index = 10;
$_POST["new_state"] = $selected_index;
$_POST['gameID'] = $gameID;
$_POST['current_state'] = $current_state;

include "get_state.php";
include 'post_state.php';