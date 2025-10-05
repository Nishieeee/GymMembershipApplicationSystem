<?php 
    require_once "../../App/controllers/AuthController.php";
    require_once "../../App/models/User.php";

    $user = new User();
    $Auth = new AuthController($user);

    $login = ["email" => "", "password"=> ""];
    $loginErrors = ["email" => "", "password"=> ""];
    $loginError = "";
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $login["email"] = isset($_GET['email']) ? trim(htmlspecialchars($_GET['email'])) : "";
        $login["password"] = isset($_GET['password']) ? trim(htmlspecialchars($_GET['password'])) : "";

        if(empty($login['email'])) {
            $loginErrors['email'] = "Email is Required.";
        } else if(!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
            $loginErrors['email'] = "Email is invalid";
        }

        if(empty($login['password'])) {
            $loginErrors['password'] = "Password is Required.";
        }


        if(empty(array_filter($loginErrors))) {
            if($Auth->login($login['email'], $login['password'])) {
                header("location: ../../public/index.php");
            } else {
                $loginError = "Invalid Email or Password.";
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
    <script src="../../public/assets/js/tailwindcss/tailwindcss.js"></script>
</head>
<body>
    <main class="min-h-screen w-full bg-[var(--primary)] flex items-center justify-center font-sans">
        <div class="p-2 h-110 w-100 bg-[var(--primary)] border border-gray-500/50 shadow-md rounded-md ">
            <form method="GET" class="p-5">
                <div class="my-8 flex items-center justify-center">
                    <h1 class="text-4xl text-blue-600 font-bold ">Gymazing!</h1>
                </div>
                <div class="m-2 ">
                    <label for="email" class="text-zinc-700 text-lg font-900">Email</label>
                    <input type="email" name="email" placeholder="example@email.com" value="<?= $login['email'] ?? "" ?>" class="p-2 w-full rounded-md border border-gray-500/50 bg-gray-300/50">
                    <p class="text-red-500 text-sm m-1"><?= $loginErrors['email'] ?></p>
                </div>
                <div class="m-2">
                    <label for="password" class="text-zinc-700 text-lg">Password</label>
                    <input type="password" name="password" id="password" placeholder="********" value="<?= $login['password'] ?>" class="p-2 w-full rounded-md border border-gray-500/50 bg-gray-300/50">
                    <p class="text-red-500 text-sm m-1" ><?= $loginErrors['password'] ?></p>
                </div>
                <div class="flex items-center justify-center">
                    <input type="submit" value="Login" class="h-10 w-30 border bg-blue-600 text-white rounded-lg">
                </div>
                <!-- <div class="p-2">
                    <p class="text-red-500 text-sm m-1"><?= $loginError ?></p>
                </div> -->
                <div class="flex-1 text-center items-center justify-center">
                    <a href="#" class="text-sm text-blue-500 underline">Forgot Password?</a>
                    <p class="text-sm">Create new Account. <a href="register.php" class="text-blue-500 underline">Sign up</a></p>
                </div>
            </form>
        </div>
    </main>
    <div class="none">

    </div>
</body>
</html>