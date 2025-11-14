

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <p class="text-red-500 text-sm"><?= $registerError['first_name'] ?></p>
                    </div>
                    <div class="w-full md:w-33">
                        <label for="last_name" class="text-sm text-zinc-700">Last Name</label>
                        <input type="text" name="last_name" placeholder="Doe" value="<?= $register['last_name'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm ">
                        <p class="text-red-500 text-sm"><?= $registerError['last_name'] ?></p>
                    </div>
                    <div class="w-full md:w-33" >
                        <label for="middle_name" class="text-sm text-zinc-700">M.I.</label>
                        <input type="text" name="middle_name" placeholder="S." value="<?= $register['middle_name']?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm ">
                        <p class="text-red-500 text-sm"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="w-50 flex flex-col">
                        <label for="age" class="text-sm text-zinc-700">Date Of Birth</label>
                        <input type="date" name="date_of_birth" id="age" placeholder="18" value="<?= $register['date_of_birth'] ?>" class="w-25 md:w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                        <p class="text-red-500 text-sm"><?= $registerError['date_of_birth'] ?></p>
                    </div>
                    <div>
                        <label for="gender" class="text-sm text-zinc-700">Gender</label>
                        <div class=" flex items-center gap-4">
                            <div>
                                <input type="radio" name="gender" id="" value="male" <?= $register['gender'] == "male" ? "checked" : ""; ?>>
                                <label for="male" class="text-sm text-zinc-700">Male</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="" value="female" <?= $register['gender'] == "female" ? "checked" : ""; ?>>
                                <label for="female" class="text-sm text-zinc-700">Female</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="" value="Other" <?= $register['gender'] == "Other" ? "checked" : ""; ?>>
                                <label for="Other" class="text-sm text-zinc-700">Others</label>
                            </div>
                        </div>
                        <p class="text-red-500 text-sm"><?= $registerError['gender'] ?></p>
                    </div>

                </div>
                <div class="w-full my-1">
                    <label for="email" class="text-zinc-700 text-sm ">Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?= $register['email'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p class="text-red-500 text-sm"><?= $registerError['email'] ?></p>
                </div>
                <div class="w-full mb-1">
                    <label for="password" class="text-zinc-700 text-sm ">Password</label>
                    <input type="password" name="password" placeholder="Password" value="<?= $register['password'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p class="text-red-500 text-sm"><?= $registerError['password'] ?></p>
                </div>
                <div class="w-full mb-1">
                    <label for="cPassword" class="text-zinc-700 text-sm ">Confirm Password</label>
                    <input type="password" name="cPassword" placeholder="Confirm Password" value="<?= $register['cPassword'] ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    <p class="text-red-500 text-sm"><?= $registerError['cPassword'] ?></p>
                </div>
                <div class="mx-2 my-4 text-sm">
                    <input type="checkbox" name="agreement" id="agreement">
                    <label for="agreement">Agree to <a href="#" class="text-blue-500 underline">User Agreement</a> and <a href="#" class="text-blue-500 underline">Privacy Terms</a></label>
                </div>
                <div class="w-full flex flex-col items-center justify-center">
                    <input type="submit" value="Register" class="w-80 p-1 text-white bg-blue-600 border border-blue-500 shadow-md rounded-md ">
                    <p class="text-red-500 text-sm"><?= $registerError['register'] ?></p>
                </div>
                <div class="w-full text-sm flex items-center justify-center my-2">
                    <p>Already have an account? <a href="login.php" class="text-blue-600 underline">Sign in</a></p>
                </div>
            </form>
        </div>
   </main>
</body>
</html>