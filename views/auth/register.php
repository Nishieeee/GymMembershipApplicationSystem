<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing! - Register</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
</head>
<body>
   <main class="min-h-screen p-10 flex items-center justify-center">
        <div class="border rounded-sm px-3 py-4 border-gray-500/50 bg-white shadow-md">
            <h1 class="text-blue-600 text-4xl font-bold mb-2">Register</h1>
            
            <!-- IMPORTANT: Make sure this action points to the correct controller -->
            <form method="POST" action="index.php?controller=auth&action=validateUserRegistration">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div class="w-full md:w-33">
                        <label for="first_name" class="text-sm text-zinc-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" placeholder="John" value="<?= htmlspecialchars($register['first_name'] ?? '') ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm" required>
                        <?php if(!empty($registerError['first_name'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['first_name']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="w-full md:w-33">
                        <label for="last_name" class="text-sm text-zinc-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Doe" value="<?= htmlspecialchars($register['last_name'] ?? '') ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm" required>
                        <?php if(!empty($registerError['last_name'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['last_name']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="w-full md:w-33">
                        <label for="middle_name" class="text-sm text-zinc-700">M.I.</label>
                        <input type="text" name="middle_name" id="middle_name" placeholder="S." value="<?= htmlspecialchars($register['middle_name'] ?? '') ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <div class="w-50 flex flex-col">
                        <label for="date_of_birth" class="text-sm text-zinc-700">Date Of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="<?= htmlspecialchars($register['date_of_birth'] ?? '') ?>" class="w-25 md:w-full p-1 text-sm border border-gray-500/50 rounded-sm" required>
                        <?php if(!empty($registerError['date_of_birth'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['date_of_birth']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="gender" class="text-sm text-zinc-700">Gender</label>
                        <div class="flex items-center gap-4">
                            <div>
                                <input type="radio" name="gender" id="male" value="male" <?= (isset($register['gender']) && $register['gender'] == "male") ? "checked" : "" ?> required>
                                <label for="male" class="text-sm text-zinc-700">Male</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="female" value="female" <?= (isset($register['gender']) && $register['gender'] == "female") ? "checked" : "" ?>>
                                <label for="female" class="text-sm text-zinc-700">Female</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="other" value="Other" <?= (isset($register['gender']) && $register['gender'] == "Other") ? "checked" : "" ?>>
                                <label for="other" class="text-sm text-zinc-700">Others</label>
                            </div>
                        </div>
                        <?php if(!empty($registerError['gender'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['gender']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="w-full my-2">
                    <label for="email" class="text-zinc-700 text-sm">Email</label>
                    <input type="email" name="email" id="email" placeholder="example@email.com" value="<?= htmlspecialchars($register['email'] ?? '') ?>" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm" required>
                    <?php if(!empty($registerError['email'])): ?>
                        <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['email']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="w-full mb-2">
                    <label for="password" class="text-zinc-700 text-sm">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm" required minlength="8">
                    <?php if(!empty($registerError['password'])): ?>
                        <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['password']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="w-full mb-2">
                    <label for="cPassword" class="text-zinc-700 text-sm">Confirm Password</label>
                    <input type="password" name="cPassword" id="cPassword" placeholder="Confirm Password" class="w-full p-1 text-sm border border-gray-500/50 rounded-sm" required minlength="8">
                    <?php if(!empty($registerError['cPassword'])): ?>
                        <p class="text-red-500 text-sm"><?= htmlspecialchars($registerError['cPassword']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mx-2 my-4 text-sm">
                    <input type="checkbox" name="agreement" id="agreement" required>
                    <label for="agreement">Agree to <a href="#" class="text-blue-500 underline">User Agreement</a> and <a href="#" class="text-blue-500 underline">Privacy Terms</a></label>
                </div>
                
                <div class="w-full flex flex-col items-center justify-center">
                    <input type="submit" value="Register" class="w-80 p-2 text-white bg-blue-600 border border-blue-500 shadow-md rounded-md hover:bg-blue-700 cursor-pointer transition">
                    <?php if(!empty($registerError['register'])): ?>
                        <p class="text-red-500 text-sm mt-2"><?= htmlspecialchars($registerError['register']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="w-full text-sm flex items-center justify-center my-2">
                    <p>Already have an account? <a href="index.php?controller=auth&action=login" class="text-blue-600 underline">Sign in</a></p>
                </div>
            </form>
        </div>
   </main>
</body>
</html>