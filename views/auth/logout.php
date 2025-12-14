<?php

require_once "../../App/controllers/AuthController.php";
require_once "../../App/models/User.php";

$user = new User();
$Auth = new AuthController($user);

$Auth->logout();
header("location: ../../index.php");

?>