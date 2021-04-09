<?php
    $init_state = $database->select("sessions","indices",["game_id"=>$gameID]);
    if($init_state[0] != "" ){
        //echo "<script> console.log('session found.') </script>";
        $nums = unserialize($init_state[0]);
        //echo "<script> console.log('".serialize($nums) ."') </script>";
    }
    else{
        //echo "<script> console.log('session not found, initializing new session.') </script>";
        $nums = range(1, $CARD_COUNT);
        shuffle($nums);
        //$database->insert("sessions",["game_id"=>$gameID,"indices"=>serialize($nums)]);
        $database->update("sessions",["indices"=>serialize($nums)],["game_id"=>$gameID]);
    }
?>