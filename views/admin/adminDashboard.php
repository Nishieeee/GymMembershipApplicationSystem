<?php 

    include_once "../../App/controllers/adminController.php";
    $admin = new Admin();

    if(!$_SESSION['role'] == 'admin') {
        header("location: /auth/login.php");
    }
    // fetch all members from db
    $members = $admin->displayAllUsers();
    
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
                                <?php foreach($members as $member) {?>
                                    <tr class="table-row border-b border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">JD</div>
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
        </div>


    </main>
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
        });
    </script>
</body>
</html>