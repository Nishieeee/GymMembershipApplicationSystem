<?php 
    require_once "../../App/controllers/AuthController.php";
    require_once "../../App/models/User.php";

    $user = new User();
    $Auth = new AuthController($user);
    
    $user = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "password" => ""];
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $user["first_name"] = trim(htmlspecialchars($_POST['first_name']));
        $user["last_name"] = trim(htmlspecialchars($_POST['last_name']));
        $user["middle_name"] = isset($_POST['middle_name']) ? trim(htmlspecialchars($_POST['middle_name'])) : "";
        $user["email"] = trim(htmlspecialchars($_POST['email']));
        $user["password"] = trim(htmlspecialchars($_POST['password']));

        $Auth->Register($user);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="text" name="first_name" placeholder="Fname">
        <input type="text" name="last_name" placeholder="Lname">
        <input type="text" name="middle_name" placeholder="Mname">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Pass">
        <input type="password" name="Confirmpassword" placeholder="ConfrmPass">
        <input type="submit" value="Register">
    </form>
</body>
</html>