<?php 
    require_once "../../App/controllers/AuthController.php";
    require_once "../../App/models/User.php";

    $user = new User();
    $Auth = new AuthController($user);
    
    $register = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "password" => "", "cPassword" => ""];
    $registerError = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "password" => "", "cPassword" => "", "register"=>""];
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $register["first_name"] =  trim(htmlspecialchars($_POST['first_name']));
        $register["last_name"] = trim(htmlspecialchars($_POST['last_name']));
        $register["middle_name"] = isset($_POST['middle_name']) ? trim(htmlspecialchars($_POST['middle_name'])) : "";
        $register["email"] =  trim(htmlspecialchars($_POST['email']));
        $register["password"] = trim(htmlspecialchars($_POST['password']));
        $register["cPassword"] = trim(htmlspecialchars($_POST['cPassword']));

        if(empty($register['first_name'])) {
            $registerError['first_name'] = "Please provide your first name";
        }
        if(empty($register['last_name'])) {
            $registerError['last_name'] = "Please provide your last name";
        }
        if(empty($register['email'])) {
            $registerError['email'] = "Please  provide a valid email";
        } else if(!filter_var($register['email'], FILTER_VALIDATE_EMAIL)) {
            $registerError['email'] = "Please provide a valid email address";
        }

        if(empty($register['password'])) {
            $registerError['password'] = "Password should not be empty.";
        } else if(strlen($register['password']) < 8) {
            $registerError['password'] = "Password should not be less than 8 characters";
        } else if($register['password'] != $register['cPassword']) {
            $registerError['password'] = "Passwords do not match";
            $registerError['cPassword'] = "Passwords do not match";
        }

        if(empty($register['cPassword'])) {
            $registerError['cPassword'] = "Please enter you password again.";
        }

        if(empty(array_filter($registerError))) {
            if(!$user->findByEmail($register['email'])) {
                if($Auth->Register($register)) {
                   header("location: login.php"); 
                } else {
                    $registerError['register'] = "Error registering user. Please try again";
                }
            } else {
                $registerError['register'] = "Account already exist.";
            }
            
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing!</title>
    <style>
        p{
            color: red;
        }
    </style>
</head>
<body>
    <form method="post">
        <input type="text" name="first_name" placeholder="Fname" value="<?= $register['first_name']?>">
        <p><?= $registerError['first_name'] ?></p>
        <input type="text" name="last_name" placeholder="Lname" value="<?= $register['last_name'] ?>">
        <p><?= $registerError['last_name'] ?></p>
        <input type="text" name="middle_name" placeholder="Mname" value="<?= $register['middle_name']?>">
        <p></p>
        <input type="email" name="email" placeholder="Email" value="<?= $register['email'] ?>">
        <p><?= $registerError['email'] ?></p>
        <input type="password" name="password" placeholder="Pass" value="<?= $register['password'] ?>">
        <p><?= $registerError['password'] ?></p>
        <input type="password" name="cPassword" placeholder="Confirm Password" value="<?= $register['cPassword'] ?>">
        <p><?= $registerError['cPassword'] ?></p>
        <input type="submit" value="Register">
        <p><?= $registerError['register'] ?></p>
    </form>
</body>
</html>