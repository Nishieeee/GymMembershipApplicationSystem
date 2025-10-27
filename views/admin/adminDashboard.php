<?php
// $user_name = $members['first_name'];
// $user_initial = substr($user_name, 0, 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gymazing</title>
    <script src= "../public/assets/js/tailwindcss/tailwindcss.js"></script>
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
    <?php include __DIR__ . "/layouts/adminnavbar.php" ?>

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
                    <p class="text-3xl font-bold text-white"><?= $memberCount['active_member_count'] ?></p>
                    <p class="text-green-400 text-xs mt-2">↑ 12% from last month</p>
                </div>

                <div class="stat-card rounded-xl p-6 text-center cursor-pointer">
                    <div class="text-4xl mb-3"></div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-white">₱ <?= $totalEarned['total_earned'] ?></p>
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
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="trainers">
                    <span class="mr-2"></span> Trainers
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="walkins">
                    <span class="mr-2"></span> Walk Ins
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="plans">
                    <span class="mr-2"></span> Plans
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="payments">
                    <span class="mr-2"></span> Payments
                </button>
            </div>
            <!-- members tab -->
            <div id="members" class="tab-content active bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <button id="btnAddMember" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            +   Add Member
                        </button>

                    </div>
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
                                    <tr class="table-row border-b border-gray-700" data-user-id="<?= $member['user_id'] ?>">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3"><?= $user_initial ?></div>
                                                    <span><?= $member['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300"><?= $member['email'] ?></td>
                                        <td class="px-6 py-4"><?= isset($member['plan_name']) ? $member['plan_name'] : 'No Active Plan' ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $member['created_at'] ?></td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge <?= $member['status'] == 'active' ? 'status-active' : 'status-inactive'?>"><?=isset($member['status']) ?  $member['status'] : 'inactive' ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button id="viewMemberDetailsBtn" class="btn-view-member text-blue-400 hover:text-blue-300 mr-3">View</button>
                                            <button class="btn-edit-member text-green-400 hover:text-green-300 mr-3">Edit</button>
                                            <button class="btn-delete-member text-red-400 hover:text-red-300">Deactivate</button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-gray-400">Showing 1-4 of <?= $memberCount['active_member_count'] ?> members</p>
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
            <!-- trainers tab -->
            <div id="trainers" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <button id="btnAddNewTrainer" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            +   Add trainer
                        </button>

                    </div>
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
                        </select>

                        <select id="filterPlan" class="px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Specialization</option>
                            <option value="Cardio">Cardio</option>
                            <option value="Strength">Strength</option>
                            <option value="Yoga">Yoga</option>
                            <option value="CrossFit">CrossFit</option>
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
                                    <th class="text-left px-6 py-3 font-semibold">Contact Number</th>
                                    <th class="text-left px-6 py-3 font-semibold">Specialization</th>
                                    <th class="text-left px-6 py-3 font-semibold">Join Date</th>
                                    <th class="text-left px-6 py-3 font-semibold">Status</th>
                                    <th class="text-center px-6 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($trainers as $trainer) { $user_name = $trainer['name'];
                                $user_initial = substr($user_name, 0, 1); ?>
                                    <tr class="table-row border-b border-gray-700" data-trainer-id="<?= $trainer['trainer_id'] ?>">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3"><?= $user_initial ?></div>
                                                    <span><?= $trainer['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300"><?= $trainer['email'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $trainer['contact_no'] ?></td>
                                        <td class="px-6 py-4"><?= isset($trainer['specialization']) ? $trainer['specialization'] : 'No specialization' ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $trainer['join_date'] ?></td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge <?= $trainer['status'] == 'active' ? 'status-active' : 'status-inactive'?>"><?=isset($trainer['status']) ?  $trainer['status'] : 'inactive' ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button id="viewMemberDetailsBtn" class="btn-view-trainer text-blue-400 hover:text-blue-300 mr-3">View</button>
                                            <button class="btn-edit-trainer text-green-400 hover:text-green-300 mr-3">Edit</button>
                                            <button class="btn-delete-trainer text-red-400 hover:text-red-300">Deactivate</button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-gray-400">Showing 1-4 of <?= $memberCount['active_member_count'] ?> members</p>
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
            <!-- walk-ins tab -->
            <div id="walkins" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <button id="btnAddWalkIn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            +   Add Walk-in Member
                        </button>
                    </div>
                    <!-- search container -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchWalkins" placeholder="Search by name, email, or ID..." class="search-input w-full pl-10 pr-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                    <!-- walkin table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-white">
                            <thead class="border-b border-gray-700 bg-gray-800">
                                <tr>
                                    <th class="text-left px-6 py-3 font-semibold">Name</th>
                                    <th class="text-left px-6 py-3 font-semibold">Email</th>
                                    <th class="text-left px-6 py-3 font-semibold">Contact No.</th>
                                    <th class="text-left px-6 py-3 font-semibold">Session Type</th>
                                    <th class="text-left px-6 py-3 font-semibold">Start Time</th>
                                    <th class="text-left px-6 py-3 font-semibold">End Time</th>
                                    <th class="text-center px-6 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($walk_ins as $walkin) { $user_name = $walkin['name'];
                                $user_initial = substr($user_name, 0, 1); ?>
                                    <tr class="table-row border-b border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3"><?= $user_initial ?></div>
                                                    <span><?= $walkin['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300"><?= $walkin['email'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $walkin['contact_no'] ?></td>
                                        <td class="px-6 py-4"><?= isset($walkin['session_type']) ? $walkin['session_type'] . " - ₱" . $walkin['payment_amount'] : 'No Active Plan' ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $walkin['visit_time'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $walkin['end_date'] ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="btn-view-walkin text-blue-400 hover:text-blue-300 mr-3">View</button>
                                            <button class="btn-edit-walkin text-green-400 hover:text-green-300 mr-3">Edit</button>
                                            <button class="btn-delete-walkin text-red-400 hover:text-red-300">Delete</button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-gray-400">Showing 1-4 of <?= $memberCount['active_member_count'] ?> members</p>
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
            
            <!-- plans Tab -->
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
            <!-- Payments -->
            <div id="payments" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-white">Payments</h2>
                        <!-- <button id="btnAddPlan" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center">
                            <span class="mr-2">+</span> Add New Plan
                        </button> -->
                    </div>
                    <!-- search container -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchPayments" placeholder="Search by name, email, or ID..." class="search-input w-full pl-10 pr-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <select id="filterStatus" class="px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
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
                    <!-- payments table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-white">
                            <thead class="border-b border-gray-700 bg-gray-800">
                                <tr>
                                    <th class="text-left px-6 py-3 font-semibold">Name</th>
                                    <th class="text-left px-6 py-3 font-semibold">Transaction ID</th>
                                    <th class="text-left px-6 py-3 font-semibold">Plan Name</th>
                                    <th class="text-left px-6 py-3 font-semibold">Amount</th>
                                    <th class="text-left px-6 py-3 font-semibold">Payment Date</th>
                                    <th class="text-left px-6 py-3 font-semibold">Status</th>
                                    <th class="text-center px-6 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach($paymentDetails as $paymentDetail) { $user_name = $paymentDetail['name'];
                                $user_initial = substr($user_name, 0, 1); ?>
                                    <tr class="table-row border-b border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3"><?= $user_initial ?></div>
                                                    <span><?= $paymentDetail['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300"><?= $paymentDetail['transaction_id'] ?? "No Payment Yet" ?></td>
                                        <td class="px-6 py-4"><?= isset($paymentDetail['plan_name']) ? $paymentDetail['plan_name'] : 'No Active Plan' ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $paymentDetail['amount'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $paymentDetail['payment_date'] ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= $paymentDetail['status'] ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="btn-view-walkin text-blue-400 hover:text-blue-300 mr-3">View</button>
                                            <button class="btn-edit-walkin text-green-400 hover:text-green-300 mr-3">Edit</button>
                                            <button class="btn-delete-walkin text-red-400 hover:text-red-300">Delete</button>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-gray-400">Showing 1-4 of <?=  $totalPayments['total_number_of_payments'] ?> payments</p>
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
    <!-- Add Member Modal -->
    <div id="addMemberModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="add-member-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Add New Member</h3>
            
            <form id="addMemberForm" method="POST" action="add_member.php" class="space-y-4">
                <!-- Personal Information -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">First Name *</label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="John">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Last Name *</label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Doe">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Email *</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="john.doe@example.com">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Address</label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                  placeholder="Enter full address"></textarea>
                    </div>
                </div>

                <!-- Membership Details -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Membership Details</h4>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Select Plan *</label>
                        <select name="plan_id" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Choose a plan</option>
                            <!-- Plans will be populated from database -->
                        </select>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Start Date *</label>
                        <input type="date" name="start_date" required 
                               class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4 flex items-center">
                        <input type="checkbox" id="startTrial" name="start_trial" class="mr-3">
                        <label for="startTrial" class="text-white">Start with 3-day free trial</label>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Account Security</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Password *</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Confirm Password *</label>
                            <input type="password" name="confirm_password" required 
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <div id="addMemberMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="add-member-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitMember" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Walk-in Member Modal -->
    <div id="addWalkInModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-3xl w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="walkin-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">🚶 Add Walk-in Member</h3>
            
            <div class="bg-purple-900 bg-opacity-30 border border-purple-600 rounded-lg p-4 mb-6">
                <p class="text-purple-200 text-sm">
                    <strong>Note:</strong> Walk-in members pay per session and don't require a subscription plan. They have limited access to facilities.
                </p>
            </div>
            
            <form id="addWalkInForm" method="POST" action="index.php?controller=Admin&action=validateWalkin" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="John">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Middle Name</label>
                            <input type="text" name="middle_name" 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="M.">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="Doe">
                        </div>
        
                        <div>
                            <label class="block text-white font-semibold mb-2">Contact Number <span class="text-red-500">*</span></label>
                            <input type="tel" name="contact_no" required 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="+63 9XX XXX XXXX">
                        </div>
        
                        <div>
                            <label class="block text-white font-semibold mb-2">Email</label>
                            <input type="email" name="email" 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="john@example.com">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Session Type <span class="text-red-500">*</span></label>
                            <select name="session_type" id="sessionType" required 
                                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select session type</option>
                                <option value="single" data-price="20">Session Day Pass - ₱20</option>
                                <option value="day_pass" data-price="60">Basic Pass - ₱60</option>
                                <option value="weekend" data-price="200">Premium Day Pass - ₱200</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Payment Amount <span class="text-red-500">*</span></label>
                            <input type="number" name="payment_amount" id="paymentAmount" required readonly
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none"
                                   placeholder="Auto-calculated">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Visit Date <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="visit_time" id="visitTime" required 
                                   class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">End Date/Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="end_date" id="endDate" required readonly
                                   class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none"
                                   placeholder="Auto-calculated">
                        </div>
        
                        <div>
                            <label class="block text-white font-semibold mb-2">Payment Method <span class="text-red-500">*</span></label>
                            <select name="payment_method" required 
                                    class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select payment method</option>
                                <option value="cash">Cash</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="walkInMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="walkin-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitWalkIn" class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                        Register Walk-in
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Member Details Modal -->
    <div id="memberModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="member-modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Member Details</h3>
            
            <div id="memberDetails" class="space-y-4">
                <!-- Details will be populated via jQuery -->
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="button" class="member-modal-close flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Close
                </button>
                <button type="button" id="btnEditMemberFromView" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Edit Member
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div id="editMemberModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="edit-member-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Edit Member</h3>
            
            <form id="editMemberForm" method="POST" action="index.php?controller=Admin&action=updateMember" class="space-y-4">
                <input type="hidden" name="user_id" id="edit_user_id">
                
                <!-- Personal Information -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="edit_first_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="edit_last_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Middle Name</label>
                        <input type="text" name="middle_name" id="edit_middle_name" 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit_email" required 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="edit_role" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                            <option value="trainer">Trainer</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Password Change (Optional) -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Change Password (Optional)</h4>
                    <p class="text-gray-400 text-sm mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">New Password</label>
                            <input type="password" name="password" id="edit_password" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Confirm Password</label>
                            <input type="password" name="confirm_password" id="edit_confirm_password" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <div id="editMemberMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="edit-member-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnUpdateMember" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Member Modal -->
    <div id="deleteMemberModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700">
            <button class="delete-modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            <form id="deleteForm" method="POST" action="index.php?controller=Admin&action=deleteMember">
                <input type="hidden" name="user_id" id="delete_user_id">

                <h3 class="text-2xl font-bold text-white mb-6">Are you sure you want to Delete Member?</h3>
                
                <div id="deleteMemberMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="delete-modal-close flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Close
                    </button>
                    <button type="submit" id="deleteBtn" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                        Proceed
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Trainer Modal -->
    <div id="addTrainerModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="add-trainer-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Add New Trainer</h3>
            
            <form id="addTrainerForm" method="POST" action="index.php?controller=Admin&action=addTrainer">
                <!-- Personal Information -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="John">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Doe">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Middle Name</label>
                        <input type="text" name="middle_name" 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="M.">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="trainer@gymazing.com">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Contact Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="contact_no" required 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="+63 9XX XXX XXXX">
                    </div>
                </div>

                <!-- Trainer Details -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Trainer Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Specialization <span class="text-red-500">*</span></label>
                            <select name="specialization" required 
                                    class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select specialization</option>
                                <option value="Weight Training">Weight Training</option>
                                <option value="Cardio">Cardio</option>
                                <option value="CrossFit">CrossFit</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Pilates">Pilates</option>
                                <option value="Boxing">Boxing</option>
                                <option value="Personal Training">Personal Training</option>
                                <option value="Nutrition">Nutrition</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Experience (Years) <span class="text-red-500">*</span></label>
                            <input type="number" name="experience_years" required min="0" max="50"
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="5">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Bio / Description</label>
                        <textarea name="bio" rows="3" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"
                                placeholder="Describe trainer's expertise and approach..."></textarea>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Account Security</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="confirm_password" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <div id="addTrainerMessage" class="hidden mb-4"></div>

                <div class="flex space-x-4">
                    <button type="button" class="add-trainer-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnAddTrainer" class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                        Add Trainer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Trainer Modal -->
    <div id="viewTrainerModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="view-trainer-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Trainer Details</h3>
            
            <div id="trainerDetails" class="space-y-4">
                <!-- Details will be populated via jQuery -->
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="button" class="view-trainer-close flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Close
                </button>
                <button type="button" id="btnEditTrainer" class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    Edit Trainer
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Trainer Modal -->
    <div id="editTrainerModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-2xl w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="edit-trainer-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Edit Trainer</h3>
            
            <form id="editTrainerForm" method="POST" action="index.php?controller=Admin&action=updateTrainer">
                <input type="hidden" name="trainer_id" id="edit_trainer_id">
                <input type="hidden" name="user_trainer_id" id="edit_user_trainer_id">
                
                <!-- Personal Information -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="trainer_first_name" id="edit_trainer_first_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="trainer_last_name" id="edit_trainer_last_name" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Middle Name</label>
                        <input type="text" name="trainer_middle_name" id="edit_trainer_middle_name" 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="trainer_email" id="edit_trainer_email" required 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Contact Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="trainer_contact_no" id="edit_trainer_contact_no" required 
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <!-- Trainer Details -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Trainer Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Specialization <span class="text-red-500">*</span></label>
                            <select name="specialization" id="edit_specialization" required 
                                    class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select specialization</option>
                                <option value="Weight Training">Weight Training</option>
                                <option value="Cardio">Cardio</option>
                                <option value="CrossFit">CrossFit</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Pilates">Pilates</option>
                                <option value="Boxing">Boxing</option>
                                <option value="Personal Training">Personal Training</option>
                                <option value="Nutrition">Nutrition</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Experience (Years) <span class="text-red-500">*</span></label>
                            <input type="number" name="experience_years" id="edit_experience_years" required min="0" max="50"
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="trainer_status" id="edit_trainer_status" required 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- <div class="mt-4">
                        <label class="block text-white font-semibold mb-2">Bio / Description</label>
                        <textarea name="bio" id="edit_bio" rows="3" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea>
                    </div> -->
                </div>

                <!-- Password Change (Optional) -->
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Change Password (Optional)</h4>
                    <p class="text-gray-400 text-sm mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">New Password</label>
                            <input type="password" name="trainer_password" id="edit_trainer_password" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">Confirm Password</label>
                            <input type="password" name="confirm_trainer_password" id="edit_confirm_trainer_password" 
                                class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <div id="editTrainerMessage" class="hidden mb-4"></div>

                <div class="flex space-x-4">
                    <button type="button" class="edit-trainer-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnUpdateTrainer" class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                        Update Trainer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../public/assets/js/admin/admin.js"></script>
        
</body>
</html>