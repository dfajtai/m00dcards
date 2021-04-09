<?php
    session_start();

    function get_new_ID(){
        global $database;
        $new_id = uniqid("game_",true);
        echo "<script> console.log('". $new_id ."') </script>";
        $database->insert("sessions",["game_id"=>$new_id,"indices"=>""]);
        return $new_id; 
    }

    if(!htmlspecialchars($_GET["gameID"])){
        #no gameID in url
        echo "<script> console.log('no gameID in url') </script>";
        if(!isset($_COOKIE["gameID"])){
            #no gameID in cookies
            echo "<script> console.log('no gameID in cookies') </script>";
            $gameID = get_new_ID();
            setcookie("gameID",$gameID);
        }
        else{
            $gameID = $_COOKIE["gameID"];
            if ($database -> count("sessions",["game_id"=>$gameID])==0){
                #id unknown to database
                echo "<script> console.log('gameID stored in cookies unknown to database') </script>";
                $gameID = get_new_ID();
            }
        }

        $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        header("Location: " . $current_uri . "/?gameID=".$gameID);
        exit;
    }
    else{
        $gameID = htmlspecialchars($_GET["gameID"]);
        if ($database -> count("sessions",["game_id"=>$gameID])==0){
            #id unknown to database
            echo "<script> console.log('gameID stored in url unknown to database') </script>";
            $gameID = get_new_ID();
            $current_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $current_uri = explode("?",$current_uri)[0];
            header("Location: " . $current_uri . "/?gameID=".$gameID);
            exit;
        }
        setcookie("gameID",$gameID);
    }
?>