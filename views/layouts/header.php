<?php
    $nav = [
        ["navItem" => "Home", "navLink" => "index.php"],
        ["navItem" => "About", "navLink" => "index.php"],
        ["navItem" => "Plans", "navLink" => "index.php"],
        ["navItem" => "Contact", "navLink" => "index.php"]
    ];
    if(session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params(['path' => "/"]);
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing!</title>
    <script src="../../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
</head>
<body>
    <header class="">
        <div class="font-sans bg-transparent shadow-md flex items-center justify-evenly px-8 py-4">
            <div class="w-90 flex items-center ">
                <h1 class="font-bold text-white text-lg">GYMAZING</h1>
            </div>
            <nav class="w-full flex items-center justify-evenly">
                <ul class="flex items-center justify-around">
                    <?php 
                        foreach ($nav as $nav) {     
                    ?>
                        <li><a href="<?= $nav['navLink'] ?>" class="mx-4 px-2 text-white font-semibold hover:text-slate-500 hover:border-b hover:pb-1 border-slate-500 transition-all duration-100 ease-in"><?= $nav['navItem'] ?></a></li>
                    <?php } ?>
                   
                </ul>
                
            </nav>
            <div class="flex items-center justify-between">
                <a href="login.php" class="bg-slate-600/70 hover:bg-slate-800 text-white mx-2 px-4 py-2 text-blue-600 font-semibold rounded-sm shadow-md transition-all duration-250 ease-in">Login</a>
                <a href="login.php" class="bg-slate-600/70 hover:bg-slate-800 text-white mx-2 px-4 py-2 text-blue-600 font-semibold rounded-sm shadow-md transition-all duration-250 ease-in">Register</a>
            </div>
        </div>
        
    </header>
</body>
</html>

