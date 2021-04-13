<?php
use Medoo\Medoo;
function merge_states($local_state, $remote_state, $selectedIndex)
{
    $unstaged = array_diff($remote_state, $local_state);
    $state_counter = count($remote_state);
    if($selectedIndex!=-1 && end($remote_state)!=$selectedIndex)
    {
        $current_state = $remote_state;
        $current_state[$state_counter] = $selectedIndex;
        $state_counter+=1;
        return array("state_counter"=>$state_counter, "current_state"=>$current_state,"unstaged"=>$unstaged);
    }
    return array("state_counter"=>$state_counter, "current_state"=>$remote_state,"unstaged"=>$unstaged);
}
function post_new_state($selectedIndex)
{
    global $database;
    global $current_state;
    global $state_counter;
    global $gameID;
    //update local state
    $db_state = get_last_state($gameID);
    $merged = merge_states($current_state,$db_state["last_state"],$selectedIndex);
    $unstaged = $merged["unstaged"];
    if($merged["current_state"]!=$current_state);{
        //db has unstaged remote change -> merge states, return unstaged changes
        $current_state = $merged["current_state"];
        $state_counter = $merged["state_counter"];
        $database -> update("sessions",["selected"=>$selectedIndex,"last_state"=>json_encode($current_state),"ts"=>Medoo::raw('NOW()')],["game_id"=>getDbGameID($gameID)]);
    }
    return array("unstaged" =>$unstaged, "current_state"=>$current_state);
}
function post_selection($selectedIndex)
{
    global $database;
    global $current_state;
    global $state_counter;
    global $gameID;
    //update local state
    $db_state = get_last_state($gameID);
    $merged = merge_states($current_state,$db_state["last_state"],$selectedIndex);
    $unstaged = $merged["unstaged"];
    if($merged["current_state"]!=$current_state){
        //db has unstaged remote change -> merge states, return unstaged changes
        $current_state = $merged["current_state"];
        $state_counter = $merged["state_counter"];
    }
    if($selectedIndex!=-1) $database ->update("sessions",["selected"=>$selectedIndex,"ts"=>Medoo::raw('NOW()')],["game_id"=>getDbGameID($gameID)]);
    return array("unstaged" =>$unstaged, "current_state"=>$current_state);
}
if (isset($_POST['new_state']) && isset($_POST['gameID']) && isset($_POST['current_state'])) {
    if(!function_exists("get_last_state")) require_once 'ajax_session.php';
    $gameID = $_POST['gameID'];
    $current_state = json_decode($_POST['current_state']);
    $selectedIndex = intval($_POST['new_state']);
    $res = post_new_state($selectedIndex);
    print json_encode($res);
    unset($_POST['new_state']);
}

if (isset($_POST['selection_changed'])  && isset($_POST['gameID']) && isset($_POST['current_state'])) {
    if(!function_exists("get_last_state")) require_once 'ajax_session.php';
    $gameID = $_POST['gameID'];
    $current_state = json_decode($_POST['current_state']);
    $selectedIndex = intval($_POST['selection_changed']);
    $res = post_selection($_POST['selection_changed']);
    print json_encode($res);
    unset($_POST['selection_changed']);
}