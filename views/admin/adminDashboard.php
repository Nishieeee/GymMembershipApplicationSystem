<?php 

    include_once "../../App/controllers/adminController.php";
    include_once "../../App/models/User.php";
    include_once "../../App/models/Plan.php";
    $user = new User();
    $plan = new Plan();
    $admin = new Admin($user, $plan);

    if(!$_SESSION['role'] == 'admin') {
        header("location: /auth/login.php");
    }
    // fetch all members from db
    $members = $admin->displayAllUsers();
    $plans = $admin->getAllPlans();
    
    // for form subsmission
    $planData = [
        "plan_name" => "",
        "description" => "",
        "duration_months" => "",
        "price" => "",
    ];
    
    $planErrors = [
        "plan_name" => "",
        "description" => "",
        "duration_months" => "",
        "price" => "",
    ];
    
    $success = false;
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $planData['plan_name'] = trim(htmlspecialchars($_POST['plan_name'] ?? ''));
        $planData['description'] = trim(htmlspecialchars($_POST['description'] ?? ''));
        $planData['duration_months'] = trim(htmlspecialchars($_POST['duration_months'] ?? ''));
        $planData['price'] = trim(htmlspecialchars($_POST['price'] ?? ''));
    
        // Validate plan_name
        if(empty($planData['plan_name'])) {
            $planErrors['plan_name'] = "Plan name is required";
        } elseif(strlen($planData['plan_name']) < 3) {
            $planErrors['plan_name'] = "Plan name must be at least 3 characters";
        } elseif(strlen($planData['plan_name']) > 50) {
            $planErrors['plan_name'] = "Plan name must not exceed 50 characters";
        }
    
        // Validate description
        if(empty($planData['description'])) {
            $planErrors['description'] = "Description is required";
        } elseif(strlen($planData['description']) < 10) {
            $planErrors['description'] = "Description must be at least 10 characters";
        } elseif(strlen($planData['description']) > 500) {
            $planErrors['description'] = "Description must not exceed 500 characters";
        }
    
        // Validate duration_months
        if(empty($planData['duration_months'])) {
            $planErrors['duration_months'] = "Duration is required";
        } elseif(!is_numeric($planData['duration_months'])) {
            $planErrors['duration_months'] = "Duration must be a number";
        } elseif($planData['duration_months'] <= 0) {
            $planErrors['duration_months'] = "Duration must be greater than 0";
        } elseif($planData['duration_months'] > 60) {
            $planErrors['duration_months'] = "Duration must not exceed 60 months";
        }
    
        // Validate price
        if(empty($planData['price'])) {
            $planErrors['price'] = "Price is required";
        } elseif(!is_numeric($planData['price'])) {
            $planErrors['price'] = "Price must be a number";
        } elseif($planData['price'] < 0) {
            $planErrors['price'] = "Price cannot be negative";
        } elseif($planData['price'] > 9999.99) {
            $planErrors['price'] = "Price exceeds maximum allowed";
        }
    
        if(empty(array_filter($planErrors))) {
            $admin->addPlan($planData);

            //clear data form
            $planData = [
                "plan_name" => "",
                "description" => "",
                "duration_months" => "",
                "price" => "",
            ];
        }
    }


    function activeMembers($plan_id) {
        //NOTE: do this ahahha
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gymazing</title>
    <script src="../../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
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

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: rgba(29, 78, 216, 0.1);
        }

        .modal-backdrop {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-backdrop.show .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
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

        .badge {
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .status-inactive {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .status-pending {
            background-color: rgba(251, 146, 60, 0.2);
            color: #fb923c;
        }

        .plan-card {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.1), rgba(30, 58, 138, 0.2));
            border: 1px solid rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(29, 78, 216, 0.2);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <!-- Admin Navbar -->
    <?php include_once "./../layouts/adminnavbar.php" ?>

    <!--  Alerts Container -->
    <div id="alertContainer" class="fixed top-24 right-4 z-40 space-y-4"></div>

    <main class="pb-12 mt-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
                <p class="text-gray-400">Manage members, plans, and gym operations</p>
            </div>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                    <div class="text-4xl mb-3"></div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Total Members</p>
                    <p class="text-3xl font-bold text-white">1,256</p>
                    <p class="text-green-400 text-xs mt-2">↑ 12% from last month</p>
                </div>

                <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                    <div class="text-4xl mb-3"></div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-white">₱125.5K</p>
                    <p class="text-green-400 text-xs mt-2">↑ 8% from last month</p>
                </div>

                <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                    <div class="text-4xl mb-3"></div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Active Plans</p>
                    <p class="text-3xl font-bold text-white">3</p>
                    <p class="text-blue-400 text-xs mt-2">Create new plan</p>
                </div>

                <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                    <div class="text-4xl mb-3"></div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Active Trials</p>
                    <p class="text-3xl font-bold text-white">89</p>
                    <p class="text-orange-400 text-xs mt-2">5 expiring soon</p>
                </div>
            </div>
        </div>
        <!-- tab navigation -->
        <div class="mb-8">
            <div class="bg-neutral-900 rounded-t-xl border-b border-gray-700 flex items-center">
                <button class="tab-button active px-6 py-4 text-white font-semibold hover:text-blue-400 transition-colors" data-tab="members">
                    <span class="mr-2"></span> Members
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="plans">
                    <span class="mr-2"></span> Plans
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="revenue">
                    <span class="mr-2"></span> Revenue
                </button>
            </div>
            <!-- members tab -->
            <div id="members" class="tab-content active bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- search container -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchMembers" placeholder="Search by name, email, or ID..." class="search-input w-full pl-10 pr-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <select id="filterStatus" class="px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="trial">Trial</option>
                        </select>

                        <select id="filterPlan" class="px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Plans</option>
                            <option value="basic">Basic</option>
                            <option value="standard">Premium</option>
                            <option value="premium">Elite</option>
                        </select>
                        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            Search
                        </button>
                    </div>
                    <!-- members table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-white">
                            <thead class="border-b border-gray-700 bg-gray-800">
                                <tr>
                                    <th class="text-left px-6 py-3 font-semibold">Name</th>
                                    <th class="text-left px-6 py-3 font-semibold">Email</th>
                                    <th class="text-left px-6 py-3 font-semibold">Plan</th>
                                    <th class="text-left px-6 py-3 font-semibold">Join Date</th>
                                    <th class="text-left px-6 py-3 font-semibold">Status</th>
                                    <th class="text-center px-6 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($members as $member) { $user_name = $member['name'];
                                $user_initial = substr($user_name, 0, 1); ?>
                                    <tr class="table-row border-b border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3"><?= $user_initial ?></div>
                                                    <span><?= $member['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300"><?= $member['email'] ?></td>
                                        <td class="px-6 py-4"><?= $member['plan_name'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $member['created_at'] ?></td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge status-active"><?= $member['status'] ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="btn-view-member text-blue-400 hover:text-blue-300 mr-3">View</button>
                                            <button class="btn-edit-member text-green-400 hover:text-green-300 mr-3">Edit</button>
                                            <button class="btn-delete-member text-red-400 hover:text-red-300">Delete</button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-gray-400">Showing 1-4 of 1,256 members</p>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">← Previous</button>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">1</button>
                            <button class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">2</button>
                            <button class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">3</button>
                            <button class="px-4 py-2 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">Next →</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="plans" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- Add Plan Button -->
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-white">Membership Plans</h2>
                        <button id="btnAddPlan" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center">
                            <span class="mr-2">+</span> Add New Plan
                        </button>
                    </div>
                    <!-- Plans Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach($plans as $plan) { ?>
                            <div class="plan-card rounded-xl p-6 border border-gray-700">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-white"><?= $plan['plan_name'] ?></h3>
                                        <p class="text-gray-400 text-sm">Entry level plan</p>
                                    </div>
                                    <span class="px-3 py-1 <?php if($plan['status'] == 'active') { echo'bg-blue-600'; } else if($plan['status'] == 'inactive') { echo 'bg-yellow-600'; } else if($plan['status'] == "removed") { echo "bg-red-600"; } ?> text-white text-xs font-bold rounded-full"><?= $plan['status'] ?></span>
                                </div>

                                <div class="mb-6">
                                    <p class="text-4xl font-bold text-white">₱<?= $plan['price'] ?><span class="text-lg text-gray-400">/mo</span></p>
                                    <p class="text-gray-400 text-sm mt-1">89 active members</p>
                                </div>

                                <p class="text-gray-300 text-sm mb-6"><?= $plan['description'] ?></p>

                                <div class="flex space-x-2">
                                    <button class="btn-edit-plan flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Edit</button>
                                    <button class="btn-delete-plan flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">Delete</button>
                                </div>
                            </div>    
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>


    </main>
    <!-- Admin Navbar -->
    <?php include_once "./../layouts/footer.php" ?>
    <!-- Add/Edit Plan Modal -->
    <div id="planModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700">
            <button class="modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Add New Plan</h3>
            
            <form id="planForm" class="space-y-4" method="post">
                <div>
                    <label class="block text-white font-semibold mb-2">Plan Name</label>
                    <input type="text" name="plan_name" required 
                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g., Platinum" value="<?= isset($planData['plan_name']) ? htmlspecialchars($planData['plan_name']) : '' ?>">
                    <p color="red"><?= $planErrors['plan_name'] ?? '' ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Price (Monthly)</label>
                        <input type="number" name="price" step="0.01" required 
                        class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="999" value="<?= isset($planData['price']) ? htmlspecialchars($planData['price']) : '' ?>">
                        <p color="red"><?= $planErrors['price'] ?? '' ?></p>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Duration (Months)</label>
                        <input type="number" name="duration_months" required 
                        class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="1" value="<?= isset($planData['duration_months']) ? htmlspecialchars($planData['duration_months']) : '' ?>">
                        <p color="red"><?= $planErrors['duration_months'] ?? '' ?></p>
                    </div>
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Description</label>
                    <textarea name="description" rows="3" required 
                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Describe this plan"><?= isset($planData['description']) ? htmlspecialchars($planData['description']) : '' ?></textarea>
                    <p color="red"><?= $planErrors['description'] ?? '' ?></p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="isFeatured" name="is_featured" class="mr-3">
                    <label for="isFeatured" class="text-white font-semibold">Mark as featured plan</label>
                </div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="modal-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitPlanBtn" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Member Details Modal -->
    <div id="memberModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700">
            <button class="member-modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Member Details</h3>
            
            <div id="memberDetails" class="space-y-4">
                <!-- Details will be populated via jQuery -->
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="button" class="member-modal-close flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Close
                </button>
                <button type="button" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Edit Member
                </button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // ===== TAB SWITCHING =====
            $('.tab-button').on('click', function() {
                const tabName = $(this).data('tab');
                
                // Remove active class from all buttons and contents
                $('.tab-button').removeClass('active').addClass('text-gray-400');
                $('.tab-content').removeClass('active');
                
                // Add active class to clicked button and corresponding content
                $(this).addClass('active').removeClass('text-gray-400');
                $('#' + tabName).addClass('active');
            });

            // ===== PLAN MODAL =====
            $('#btnAddPlan').on('click', function() {
                $('#planForm')[0].reset();
                $('#planForm h3').text('Add New Plan');
                $('#submitPlanBtn').text('Create Plan');
                $('#planModal').addClass('show');
                $('body').css('overflow', 'hidden');
            });

            // Close plan modal
            $('.modal-close, .modal-cancel').on('click', function() {
                $(this).closest('.modal-backdrop').removeClass('show');
                $('body').css('overflow', 'auto');
            });

            // Close modal when clicking backdrop
            $('#planModal, #memberModal').on('click', function(e) {
                if ($(e.target).is(this)) {
                    $(this).removeClass('show');
                    $('body').css('overflow', 'auto');
                }
            });
        });

        // ===== PLAN ACTIONS =====
        $(document).on('click', '.btn-edit-plan', function() {
                showAlert('Edit plan functionality - Add your implementation', 'info');
            });

        $(document).on('click', '.btn-delete-plan', function() {
            const planCard = $(this).closest('.plan-card');
            if (confirm('Are you sure you want to delete this plan?')) {
                planCard.fadeOut(300, function() {
                    $(this).remove();
                    showAlert('Plan deleted successfully', 'success');
                });
            }
        });
         // Close member modal
        $('.member-modal-close').on('click', function() {
            $('#memberModal').removeClass('show');
            $('body').css('overflow', 'auto');
        });
    </script>
</body>
</html>