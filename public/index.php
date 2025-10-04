<?php 
    session_set_cookie_params(['path' => '/']);
    session_start();
    require_once __DIR__ . "../../App/models/User.php";
    
    $userObj = new User();
    

    $member = $userObj->getMember($_SESSION['user_id']);
    echo $member['first_name'] . " ";
    echo $member['last_name'] . " ";
    echo $member['email'] . " ";


?>