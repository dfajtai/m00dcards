<?php
    session_start();
    #echo '<script type="text/javascript"> console.log("'. session_id() . '") </script>';
    session_regenerate_id();
    $new_sid = session_id();
    #echo '<script type="text/javascript"> console.log("'. session_id() . '") </script>';
    echo $new_sid;
?>