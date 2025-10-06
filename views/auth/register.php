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
    <title>Gymazing!</title>
    <script src="../../public/assets/js/tailwindcss/tailwindcss.js"></script>
</head>
<body>
   <main class="min-h-screen p-10 flex items-center justify-center">
        <div class="border rounded-sm px-3 py-4 border-gray-500/50 ">
            <h1 class="text-blue-600 text-4xl font-bold mb-2">Register</h1>
            <form method="post">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div class="w-full md:w-33">
                        <label for="first_name" class="text-sm text-zinc-700">First Name</label>
                        <input type="text" name="first_name" placeholder="John" value="<?= $register['first_name']?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm ">
                        <p><?= $registerError['first_name'] ?></p>
                    </div>
                    <div class="w-full md:w-33">
                        <label for="last_name" class="text-sm text-zinc-700">Last Name</label>
                        <input type="text" name="last_name" placeholder="Doe" value="<?= $register['last_name'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm ">
                        <p><?= $registerError['last_name'] ?></p>
                    </div>
                    <div class="w-full md:w-33" >
                        <label for="middle_name" class="text-sm text-zinc-700">M.I.</label>
                        <input type="text" name="middle_name" placeholder="S." value="<?= $register['middle_name']?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm ">
                        <p></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="w-50 flex flex-col">
                        <label for="age" class="text-sm text-zinc-700">Age</label>
                        <input type="number" name="age" id="age" placeholder="18" class="w-25 md:w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    </div>
                    <div>
                        <label for="gender" class="text-sm text-zinc-700">Gender</label>
                        <div class=" flex items-center gap-4">
                            <div>
                                <input type="radio" name="gender" id="" value="male">
                                <label for="male" class="text-sm text-zinc-700">Male</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="" value="female">
                                <label for="female" class="text-sm text-zinc-700">Female</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="w-full my-1">
                    <label for="email" class="text-zinc-700 text-sm ">Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?= $register['email'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p><?= $registerError['email'] ?></p>
                </div>
                <div class="w-full mb-1">
                    <label for="password" class="text-zinc-700 text-sm ">Password</label>
                    <input type="password" name="password" placeholder="Pass" value="<?= $register['password'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p><?= $registerError['password'] ?></p>
                </div>
                <div class="w-full mb-1">
                    <label for="cPassword" class="text-zinc-700 text-sm ">Confirm Password</label>
                    <input type="password" name="cPassword" placeholder="Confirm Password" value="<?= $register['cPassword'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p><?= $registerError['cPassword'] ?></p>
                </div>
                <div class="mx-2 my-4 text-sm">
                    <input type="checkbox" name="agreement" id="agreement">
                    <label for="agreement">Agree to <a href="#" class="text-blue-500 underline">User Agreement</a> and <a href="#" class="text-blue-500 underline">Privacy Terms</a></label>
                </div>
                <div class="w-full flex items-center justify-center">
                    <input type="submit" value="Register" class="w-80 p-1 text-white bg-blue-600 border border-blue-500 shadow-md rounded-md ">
                    <p><?= $registerError['register'] ?></p>
                </div>
                <div class="w-full text-sm flex items-center justify-center my-2">
                    <p>Already have an account? <a href="login.php" class="text-blue-600 underline">Sign in</a></p>
                </div>
            </form>
        </div>
   </main>
</body>
</html>