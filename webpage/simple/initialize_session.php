<?php


    session_start();
    $game_session_id = session_id();
    $init_state = $database->select("sessions","indices",["session_id"=>$game_session_id]);
    if($init_state){
        echo "<script> console.log('session found.') </script>";
        $nums = unserialize($init_state[0]);
        #echo "<script> console.log('".serialize($nums) ."') </script>";
    }
    else{
        echo "<script> console.log('session not found, initializing new session.') </script>";
        $nums = range(1, $CARD_COUNT);
        shuffle($nums);
        $database->insert("sessions",["session_id"=>$game_session_id,"indices"=>serialize($nums)]);
    }
?>