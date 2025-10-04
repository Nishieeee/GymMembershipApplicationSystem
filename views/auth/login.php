<?php 
    require_once "../../App/controllers/AuthController.php";
    require_once "../../App/models/User.php";

    $user = new User();
    $Auth = new AuthController($user);

    $login = ["email" => "", "password"=> ""];
    $loginErrors = ["email" => "", "password"=> ""];

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $login["email"] = isset($_GET['email']) ? trim(htmlspecialchars($_GET['email'])) : "";
        $login["password"] = isset($_GET['password']) ? trim(htmlspecialchars($_GET['password'])) : "";

        if(empty($login['email'])) {
            $loginErrors['email'] = "Email is Required.";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loginErrors['email'] = "Email is invalid";
        }

        if(empty($login['password'])) {
            $loginErrors['password'] = "Password is Required";
        }


        if(empty(array_filter($loginErrors))) {
            $Auth->login($email, $password);
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing!</title>
</head>
<body>
    <form method="get">
        <input type="email" name="email" id="" placeholder="Email">
        <input type="password" name="password" id="" placeholder="password">
        <input type="submit" value="Login">
    </form>
</body>
</html>