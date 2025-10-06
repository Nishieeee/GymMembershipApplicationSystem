<?php 
    session_set_cookie_params(['path' => '/']);
    session_start();
    require_once __DIR__ . "../../App/models/User.php";
    
?>