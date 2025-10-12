<?php 
    session_start();
    include_once "../App/models/User.php";
    include_once "../App/models/Plan.php";
    $userObj = new User();
    $planObj = new Plan();

    echo $_SESSION['user_id'];
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $userInfo = $userObj->getMember($user_id);
        $userPlan = $planObj->getUserPlan($user_id);
    } else {
        header("location: ./auth/login.php");
    }

    //user data (needs work)
     
    $user = [
        "name" => "",
        "created_at" => "",
        "plan_name" => "",
        "end_date" => "",
        "status" => "",
    ];
    

    $user['name'] = $userInfo['name'];
    $user['created_at'] = $userInfo['created_at'];
    $user['plan_name'] = isset($userPlan['plan_name']) ? $userPlan['plan_name'] : "";
    $user['end_date'] = isset($userPlan['end_date']) ? $userPlan['end_date'] : "";
    $user['status'] = isset($userPlan['status']) ? $userPlan['status'] : "";
    $stats = [
        ["label" => "Classes Attended", "value" => 24, "icon" => "📚"],
        ["label" => "Workouts This Month", "value" => 12, "icon" => "💪"],
        ["label" => "Days Remaining", "value" => 30, "icon" => "📅"],
        ["label" => "Personal Trainer", "value" => "Yes", "icon" => "👨‍🏫"]
    ];

    $upcoming_classes = [
        ["name" => "CrossFit Training", "time" => "10:00 AM - 11:00 AM", "trainer" => "Coach Mike", "capacity" => "15/20"],
        ["name" => "Strength Building", "time" => "2:00 PM - 3:30 PM", "trainer" => "Coach Sarah", "capacity" => "12/15"],
        ["name" => "Cardio Blast", "time" => "5:00 PM - 6:00 PM", "trainer" => "Coach Alex", "capacity" => "18/25"],
    ];

    $achievements = [
        ["name" => "First Class", "date" => "2024-01-15", "icon" => "🏆"],
        ["name" => "100 Workouts", "date" => "2024-06-20", "icon" => "💯"],
        ["name" => "Consistency King", "date" => "2024-08-10", "icon" => "👑"],
        ["name" => "Personal Best", "date" => "2024-09-05", "icon" => "⭐"],
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
        }

        * {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1e40af;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.1) 0%, rgba(30, 58, 138, 0.2) 100%);
            border: 1px solid rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(29, 78, 216, 0.2);
            border-color: #1e40af;
        }

        .class-card {
            background: linear-gradient(to right, rgba(29, 78, 216, 0.05), rgba(30, 58, 138, 0.1));
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }

        .class-card:hover {
            transform: translateX(8px);
            box-shadow: 0 10px 25px rgba(29, 78, 216, 0.15);
        }

        .achievement-badge {
            transition: all 0.3s ease;
        }

        .achievement-badge:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .sidebar {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .sidebar.active {
            max-height: 500px;
        }

        .hamburger span {
            transition: all 0.3s ease-in-out;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .dashboard-section {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <?php include_once __DIR__ . "/layouts/navbar.php" ?>

    <!-- Main Content -->
    <main class="pt-24 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- hero Section -->
            <div id="dashboard" class="dashboard-section mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-8 lg:p-12 text-white shadow-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Welcome back, <?= $user['name'] ?>! 👋</h1>
                    <p class="text-blue-100 text-lg">
                        <span class="font-semibold">Status:</span> 
                        <span class="inline-block px-3 py-1 <?=  isset($userPlan) ? "bg-green-500" : "bg-gray-800" ?>  rounded-full text-sm font-bold ml-2">
                            <?= isset($userPlan) ? $user['status'] : "No Active Plan"; ?></span> 
                    </p>
                    <?php if(isset($userPlan)) {?>
                        <p class="text-blue-100 text-lg mt-2">Your <?= $user['plan_name'] ?> expires in <span class="font-bold">30 days</span></p>
                    <?php }?>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="dashboard-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <?php foreach ($stats as $stat) { ?>
                    <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                        <div class="text-4xl mb-3"><?= $stat['icon'] ?></div>
                        <p class="text-gray-400 text-sm font-medium mb-1"><?= $stat['label'] ?></p>
                        <p class="text-3xl font-bold text-white"><?= $stat['value'] ?></p>
                    </div>
                <?php } ?>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Membership & Classes -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Membership Info -->
                    <div class="dashboard-section bg-neutral-900 rounded-xl p-8 border border-gray-700 shadow-lg">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Your Membership</span>
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <?php
                                    if(isset($userPlan)) {

                                ?>
                                    <p class="text-gray-400 text-sm mb-1">Plan Type</p>
                                    <p class="text-2xl font-bold text-white"><?= $user['plan_name'] ?></p>
                                    <p class="text-green-400 text-sm mt-2">✓ Active</p>
                                <?php } else {?>
                                    <p class="text-gray-400 text-sm mb-1">No Active Plan</p>   
                                <?php }?>

                            </div>
                            <div class="bg-gray-800 rounded-lg p-6">                             
                                <?php if(isset($userPlan)) { ?>
                                    <p class="text-gray-400 text-sm mb-1">Expiry Date</p>
                                    <p class="text-2xl font-bold text-white"><?= date('M d, Y', strtotime($user['end_date'])) ?></p>
                                    <p class="text-blue-400 text-sm mt-2">Renew before expiry</p>
                                <?php } else {?>
                                    <p class="text-gray-400 text-sm mb-1">No Active Plan</p>   
                                <?php }?>
                            </div>
                            <div class="bg-gray-800 rounded-lg p-6">
                                <p class="text-gray-400 text-sm mb-1">Member Since</p>
                                <p class="text-2xl font-bold text-white"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                                <p class="text-yellow-400 text-sm mt-2">Great job staying with us!</p>
                            </div>
                            <div class="bg-gray-800 rounded-lg p-6">
                                
                                <?php if(isset($userPlan)) { ?>
                                    <p class="text-gray-400 text-sm mb-1">Next Billing</p>
                                    <p class="text-2xl font-bold text-white"><?= date('M d, Y', strtotime('+30 days')) ?></p>
                                    <a href="#" class="text-blue-400 text-sm mt-2 hover:text-blue-300">Manage Billing</a>
                                <?php } else {?>
                                    <p class="text-gray-400 text-sm mb-1">No Active Plan</p>   
                                <?php }?>

                            </div>
                        </div>
                        <button class="mt-6 w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                            <a href="plans.php"><?=  isset($userPlan) ? "Upgrade Plan" : "Subscribe" ?></a>
                        </button>
                    </div>

                    <!-- Upcoming Classes -->
                    <div class="dashboard-section bg-neutral-900 rounded-xl p-8 border border-gray-700 shadow-lg">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Upcoming Classes Today</span>
                        </h2>
                        <div class="space-y-4">
                            <?php if(isset($userPlan)) { foreach ($upcoming_classes as $class) { ?>
                                <div class="class-card rounded-lg p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-white mb-2"><?= $class['name'] ?></h3>
                                            <div class="space-y-1 text-sm text-gray-400">
                                                <p>⏰ <?= $class['time'] ?></p>
                                                <p>👨‍🏫 Trainer: <?= $class['trainer'] ?></p>
                                                <p>👥 Capacity: <?= $class['capacity'] ?></p>
                                            </div>
                                        </div>
                                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                            Book
                                        </button>
                                    </div>
                                </div>
                            <?php } } else {?>
                                <div class="p-3">
                                    <h2 class="text-lg text-gray-500">No Upcoming Classes</h2>
                                    <p class="text-lg text-gray-500">Subscribe to a plan first</p>
                                    <a href="" class="inline-block mt-6 text-lg text-blue-400 hover:text-blue-300 font-semibold">Plans →</a>
                                </div>
                            <?php }?>
                        </div>
                        <?php 
                            if(isset($userPlan)) {
                        ?>
                        <a href="#" class="inline-block mt-6 text-blue-400 hover:text-blue-300 font-semibold">View All Classes →</a>
                        <?php } ?>
                    </div>
                </div>

                <!-- Right Column - Achievements & Quick Links -->
                <div class="space-y-8">
                    
                    <!-- Achievements -->
                    <div class="dashboard-section bg-neutral-900 rounded-xl p-8 border border-gray-700 shadow-lg">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center space-x-2">
                            <span class="text-2xl">🏆</span>
                            <span>Achievements</span>
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <?php foreach ($achievements as $achievement) { ?>
                                <div class="achievement-badge bg-gray-800 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-700">
                                    <div class="text-3xl mb-2"><?= $achievement['icon'] ?></div>
                                    <p class="text-white text-sm font-semibold"><?= $achievement['name'] ?></p>
                                    <p class="text-gray-400 text-xs mt-1"><?= date('M d', strtotime($achievement['date'])) ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="dashboard-section bg-neutral-900 rounded-xl p-8 border border-gray-700 shadow-lg">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Quick Actions</span>
                        </h2>
                        <div class="space-y-3">
                            <a href="profile.php" class="block px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                Edit Profile
                            </a>
                            <a href="#" class="block px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                Book a Trainer
                            </a>
                            <a href="#" class="block px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                View Progress
                            </a>
                            <a href="#" class="block px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                Download Receipt
                            </a>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="dashboard-section bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-8 border border-blue-500 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-3">Need Help?</h3>
                        <p class="text-blue-100 text-sm mb-4">Contact our support team for any assistance</p>
                        <div class="space-y-2 text-sm text-blue-100">
                            <p>📞 +63 123 456 7890</p>
                            <p>📧 support@gymazing.com</p>
                            <p>⏰ Available 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . "/layouts/footer.php" ?>                            
    <script src="../public/assets/js/dashboard.js"></script>
</body>
</html>