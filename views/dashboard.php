<?php 
     
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
        ["label" => "Classes Attended", "value" => 24, "icon" => '<i class="fa-regular fa-rectangle-list text-yellow-400"></i>'],
        ["label" => "Workouts This Month", "value" => 12, "icon" => '<i class="fa-regular fa-hand text-orange-400"></i>'],
        ["label" => "Days Remaining", "value" => 30, "icon" => '<i class="fa-regular fa-calendar text-red-400"></i>'],
        ["label" => "Personal Trainer", "value" => "Yes", "icon" => '<i class="fa-regular fa-user text-blue-400"></i>']
    ];

    $upcoming_classes = [
        ["name" => "CrossFit Training", "time" => "10:00 AM - 11:00 AM", "trainer" => "Coach Mike", "capacity" => "15/20"],
        ["name" => "Strength Building", "time" => "2:00 PM - 3:30 PM", "trainer" => "Coach Sarah", "capacity" => "12/15"],
        ["name" => "Cardio Blast", "time" => "5:00 PM - 6:00 PM", "trainer" => "Coach Alex", "capacity" => "18/25"],
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing! | Dashboard</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../public/assets/icons/fontawesome/css/all.min.css"></link>
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
    
    <?php include __DIR__ . "/layouts/navbar.php" ?>

    <!-- Main Content -->
    <main class="pt-24 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- hero Section -->
            <div id="dashboard" class="dashboard-section mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-8 lg:p-12 text-white shadow-xl">
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">Welcome back, <?= $user['name'] ?>!</h1>
                    <p class="text-blue-100 text-lg">
                        <span class="font-semibold">Status:</span> 
                        <span class="inline-block px-3 py-1 <?=  $userPlan['status'] == 'active' ? 'bg-green-500' : 'bg-red-500' ?>  rounded-full text-sm font-bold ml-2">
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
                                    <p class="<?= $userPlan['status'] == 'active' ? 'text-green-500' : 'text-red-500' ?> text-lg mt-2"><?= $userPlan['status'] ?></p>
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
                            <a href="index.php?controller=Plan&action=viewPlans"><?= isset($userPlan['status']) ? "Upgrade Plan" : "Subscribe" ?></a>
                        </button>
                        <button class="mt-2 w-full px-6 py-3 bg-gray-500/50 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-300">
                            <a href="index.php?controller=Subscribe&action=CancelSubscription">Cancel Plan</a>
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
                            <?php if(isset($userPlan)) { foreach ($mySessions as $session) { ?>
                                <div class="class-card rounded-lg p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-white mb-2"><?= $user['name'] ?></h3>
                                            <div class="space-y-1 text-sm text-gray-400">
                                                <p><i class="fa-regular fa-alarm-clock text-red-400"></i> <?= $session['session_date'] ?></p>
                                                <p><i class="fa-regular fa-user text-blue-400"></i> Trainer: <?= $session['trainer_name'] ?></p>
                                              
                                            </div>
                                        </div>
                                        <!-- <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                            Book
                                        </button> -->
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
                            <button id="openRequestTrainer" class="w-full px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                Request a Trainer
                            </button>
                            <a href="#" class="block px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                View Progress
                            </a>
                            <!-- <a href="" class="block px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 text-center">
                                Download Receipt
                            </a> -->
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="dashboard-section bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-8 border border-blue-500 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-3">Need Help?</h3>
                        <p class="text-blue-100 text-sm mb-4">Contact our support team for any assistance</p>
                        <div class="space-y-2 text-sm text-blue-100">
                            <p><i class="fa-regular fa-comment-dots text-red-400"></i> +63 123 456 7890</p>
                            <p><i class="fa-regular fa-envelope text-blue-400"></i> support@gymazing.com</p>
                            <p><i class="fa-regular fa-alarm-clock text-yellow-400"></i> Available 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . "/layouts/footer.php" ?>                            
    <!-- Request Trainer Modal -->
    <div id="bookTrainerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-neutral-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 relative">
            <button id="closeBookTrainer" class="absolute top-3 right-3 text-gray-400 hover:text-white">&times;</button>
            <h3 class="text-2xl font-bold text-white mb-4">Request a Trainer</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Trainer</label>
                    <select id="trainerSelect" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg p-3 focus:border-blue-500">
                        <option value="">Loading...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Notes (optional)</label>
                    <textarea id="sessionNotes" rows="3" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg p-3 focus:border-blue-500" placeholder="Any preferences or goals"></textarea>
                </div>
                <div id="bookTrainerMessage" class="text-sm"></div>
                <button id="submitBookTrainer" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-3 transition">Send Request</button>
            </div>
        </div>
    </div>

    <script src="../public/assets/js/dashboard.js"></script>
    <script>
        $(function() {
            const $modal = $('#bookTrainerModal');
            const $trainerSelect = $('#trainerSelect');
            const $sessionNotes = $('#sessionNotes');
            const $submitBtn = $('#submitBookTrainer');
            const $messageBox = $('#bookTrainerMessage');

            function showModal() {
                $modal.removeClass('hidden').addClass('flex');
                $messageBox.text('').removeClass('text-green-400 text-red-400');
                loadTrainers();
            }

            function hideModal() {
                $modal.addClass('hidden').removeClass('flex');
            }

            function loadTrainers() {
                $trainerSelect.html('<option value=\"\">Loading...</option>');
                $.ajax({
                    url: 'index.php?controller=Dashboard&action=listTrainers',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (!data.success || !data.trainers) {
                            $trainerSelect.html('<option value=\"\">No trainers available</option>');
                            return;
                        }
                        let opts = '<option value=\"\">Select a trainer</option>';
                        data.trainers.forEach(t => {
                            opts += `<option value=\"${t.trainer_id}\">${t.name} (${t.specialization ?? 'Trainer'})</option>`;
                        });
                        $trainerSelect.html(opts);
                    },
                    error: function() {
                        $trainerSelect.html('<option value=\"\">Error loading trainers</option>');
                    }
                });
            }

            function setMessage(text, success = false) {
                $messageBox
                    .text(text)
                    .removeClass('text-green-400 text-red-400')
                    .addClass(success ? 'text-green-400' : 'text-red-400');
            }

            function submitRequest() {
                const trainerId = $trainerSelect.val();
                const notesVal = $sessionNotes.val().trim();
                if (!trainerId) {
                    setMessage('Please select a trainer.');
                    return;
                }
                $submitBtn.prop('disabled', true);
                setMessage('');

                const formData = new FormData();
                formData.append('trainer_id', trainerId);
                formData.append('notes', notesVal);

                $.ajax({
                    url: 'index.php?controller=Dashboard&action=requestTrainer',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR) {
                        if (jqXHR.status !== 200 || !data.success) {
                            setMessage(data.message || 'Unable to send request.');
                            return;
                        }
                        setMessage('Request sent!', true);
                        setTimeout(hideModal, 800);
                    },
                    error: function(xhr) {
                        let msg = 'Network error, please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        setMessage(msg);
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false);
                    }
                });
            }

            $('#openRequestTrainer').on('click', showModal);
            $('#closeBookTrainer').on('click', hideModal);
            $modal.on('click', function(e) {
                if (e.target === this) hideModal();
            });
            $submitBtn.on('click', submitRequest);
        });
    </script>
</body>
</html>