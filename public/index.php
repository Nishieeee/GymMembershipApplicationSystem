<?php 
    session_start();
    include_once "../App/models/Plan.php";
    $planObj = new Plan();

    $plans = $planObj->getAllPlans();

    $testimonies = [
        ["name"=>"John Doe","rating"=>"4","comment"=>"Amazing place to shape your body!"],
        ["name"=>"Jane Smith","rating"=>"5","comment"=>"Gymazing is the best place to go if you're looking for high quality gym!"],
        ["name"=>"John McCaine","rating"=>"4","comment"=>"Great Ambience and Knowledgable Trainers"]
    ];
    $services = [
        ["service"=>"CrossFit Group Classes", "image"=>""],
        ["service"=>"Strength Training", "image"=>""],
        ["service"=>"Personal Training", "image"=>""],
        ["service"=>"Member Only Events", "image"=>""]
    ];

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing!</title>
    <!-- <link rel="stylesheet" href="../public/assets/css/bs/bootstrap.min.css"> -->
     <!-- <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0D6EFD',
                        accent: "#20C997",
                        background: "#F8F9FA",
                        text: "#212529",
                    },
                },
            },
        }
    </script> -->
    <script src="../public/assets/js/tailwindcss/tailwindcss.js">
    </script>
    
</head>
<body class="bg-linear-to-r from-neutral-950 via-neutral-800 to-neutral-600">
    
    <!-- header -->
    <?php include_once '../views/layouts/header.php'?>

    <!-- main content -->
    <main class="min-h-screen m-1">
        <section class="hero-section ">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 px-6 py-10">
                <div class="p-6 text-white flex flex-col items-start justify-center">
                    <div class="m-2 mt-5">
                        <h1 class="font-bold text-6xl">Build Perfect Body <br> With Clean Mind</h1>
                    </div>
                    <div class="m-2 mb-5">
                        <p class="text-lg">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iure, facilis itaque? Porro vitae eveniet illo dignissimos a. Maxime rerum aperiam consequuntur commodi ducimus quam beatae aliquam cum nisi! Dolores, dolore?</p>
                    </div>
                    <div class="m-2">
                        <button class="px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold shadow-sm rounded-sm">Get Started</button>
                    </div>
                    <div class="mt-12 mx-2 flex items-center justify-evenly w-50">
                        <div><a href="">FB</a></div>
                        <div><a href="">Twitter</a></div>
                        <div><a href="">Instagram</a></div>
                    </div>
                </div>
                <div class="h-full flex items-center justify-center">
                    <img src="assets/images/cafelayan-4.jpg" alt="" class="h-100">
                </div>
            </div>
        </section>
        <section class="about p-6 h-screen bg-neutral-900 text-white">
            <div class="px-7 h-full flex flex-col items-center justify-evenly">
                <div class="text-4xl font-bold text-center ">
                    We Offer Something For Everybody
                </div>
                <div class="px-8 py-6 h-full w-full grid grid-cols-1 grid-cols-2 gap-4">
                    <?php foreach ($services as $service) {?>
                        <div class="bg-slate-500/25 w-full rounded-md shadow-md flex items-center justify-center hover:scale-102 transition-all duration-250 ease-in-out">
                            <h2 class="text-xl font-bold"><?= $service['service'] ?></h2>
                        </div>
                    <?php }?>
                </div>
            </div>
        </section>
        <section class="memberships h-screen p-6 bg-zinc-800">
            <div class="h-full">
                <div class="text-white m-2 mb-6 flex flex-col items-center justify-center">
                   <h1 class="text-4xl font-bold">Your Fitness Journey Starts Here</h1>
                   <p clas="text-xl m-2">choose a plan that suits you best</p>
                </div>
                <div class="h-110 grid items-center justify-center grid-cols-1 md:grid-cols-3 p-6 gap-4">
                    <?php foreach($plans as $plan) { ?>
                        <div class="h-full w-full bg-white text-neutral-900 border border-graya-700 flex flex-col items-center justify-center text-center rounded-xl shadow-lg hover:scale-105 transition-all duration-150 ease-in-out">
                            <div class="m-4  mb-6">
                               <h1 class="text-4xl text-slate-900 font-bold"><?= $plan['plan_name'] ?></h1>
                               <p class="text-2xl text-slate-900 font-bold m-2 "><?= "Php " . $plan['price'] ?><span class="text-sm text-neutral-900">/mo</span></p>
                            </div>
                            <div class="mb-6 p-3 h-40 w-80 font-semibold text-center">
                                <?= $plan['description'] ?>
                            </div>
                            <div class="m-4 flex flex-col items-center">
                                <p class="text-sm text-gray-600 w-50 ">Charges every month unless you cancel</p>
                                <button class="px-3 py-2 m-2 bg-neutral-900 text-white font-semibold rounded-xl hover:scale-102 transitioon-all duration-250 ease-in-out"> 
                                    Start 3 Day Free Trial Now
                                </button>
                            </div>
                        </div>    
                    <?php }?>
                </div>
            </div>
        </section>
        <section class="plans">

        </section>
        <section class="contact">

        </section>
    </main>

    <!-- footer -->
    <?php include '../views/layouts/footer.php' ?>
    <!-- bootstrap -->
    <!-- <script src="../assets/public/js/bs/bootstrap.bundle.min.js"></script> -->
    <!-- JQUERY -->
    <script src="../assets/public/js/jquery/juery-3.7.1.min.js"></script>
    <!-- custom js -->
</body>
</html>