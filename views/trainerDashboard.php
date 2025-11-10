<?php

// if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
//     header("location: login.php");
//     exit;
// }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard - Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../public/assets/icons/fontawesome/css/all.min.css"></link>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
        }
        /* Tab styles */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-button {
            color: #9ca3af; /* text-gray-400 */
        }

        .tab-button.active {
            color: #ffffff; /* text-white */
            border-bottom: 3px solid #3b82f6; /* Optional: blue underline for active tab */
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

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-scheduled { background-color: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .status-completed { background-color: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .status-cancelled { background-color: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .status-pending { background-color: rgba(251, 146, 60, 0.2); color: #fb923c; }
        .status-active { background-color: rgba(34, 197, 94, 0.2); color: #22c55e; }

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
    </style>
</head>
<body class="gradient-bg min-h-screen">

    <?php include __DIR__ . "/layouts/navbar.php" ?>
    

    <main class="container mx-auto pt-24 px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-neutral-900 rounded-xl p-6 border border-gray-700">
                <div class="text-4xl mb-3"><i class="fa-regular fa-user text-blue-400"></i></div>
                <p class="text-gray-400 text-sm">Assigned Members</p>
                <p class="text-3xl font-bold text-white"><?= $stats['total_members'] ?></p>
            </div>
            <div class="bg-neutral-900 rounded-xl p-6 border border-gray-700">
                <div class="text-4xl mb-3"><i class="fa-regular fa-calendar text-red-400"></i></div>
                <p class="text-gray-400 text-sm">Upcoming Sessions</p>
                <p class="text-3xl font-bold text-white"><?= $stats['upcoming_sessions'] ?></p>
            </div>
            <div class="bg-neutral-900 rounded-xl p-6 border border-gray-700">
                <div class="text-4xl mb-3"><i class="fa-regular fa-calendar-check text-yellow-400"></i></div>
                <p class="text-gray-400 text-sm">Completed This Month</p>
                <p class="text-3xl font-bold text-white"><?= $stats['completed_sessions'] ?></p>
            </div>
            <div class="bg-neutral-900 rounded-xl p-6 border border-gray-700">
                <div class="text-4xl mb-3"><i class="fa-regular fa-hourglass text-orange-400"></i></div>
                <p class="text-gray-400 text-sm">Pending Requests</p>
                <p class="text-3xl font-bold text-white"><?= $stats['pending_requests'] ?></p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-8">
            <div class="bg-neutral-900 rounded-t-xl border-b border-gray-700 flex items-center">
                <button class="tab-button active px-6 py-4 text-white font-semibold hover:text-blue-400 transition-colors" data-tab="members">
                    <i class="fa-regular fa-user text-blue-400"></i> Assigned Members
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="sessions">
                    <i class="fa-regular fa-calendar text-red-400"></i> Sessions
                </button>
                <button class="tab-button px-6 py-4 text-gray-400 font-semibold hover:text-blue-400 transition-colors" data-tab="requests">
                    <i class="fa-regular fa-bell text-yellow-400"></i> Requests
                </button>
            </div>

            <!-- Assigned Members Tab -->
            <div id="members" class="tab-content active bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">My Members</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-white">
                        <thead class="border-b border-gray-700 bg-gray-800">
                            <tr>
                                <th class="text-left px-6 py-3">Member Name</th>
                                <th class="text-left px-6 py-3">Email</th>
                                <th class="text-left px-6 py-3">Assigned Date</th>
                                <th class="text-left px-6 py-3">Status</th>
                                <th class="text-center px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($assignedMembers as $member): ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-800">
                                <td class="px-6 py-4"><?= htmlspecialchars($member['name']) ?></td>
                                <td class="px-6 py-4 text-gray-300"><?= htmlspecialchars($member['email']) ?></td>
                                <td class="px-6 py-4 text-gray-300"><?= $member['assigned_date'] ?></td>
                                <td class="px-6 py-4">
                                    <span class="status-badge status-<?= $member['status'] ?>"><?= ucfirst($member['status']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="btn-schedule-session text-blue-400 hover:text-blue-300" data-user-id="<?= $member['user_id'] ?>" data-name="<?= htmlspecialchars($member['name']) ?>">
                                        Schedule Session
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sessions Tab -->
            <div id="sessions" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Upcoming Sessions</h2>
                </div>
                
                <div class="space-y-4">
                    <?php foreach($upcomingSessions as $session): ?>
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-2"><?= htmlspecialchars($session['member_name']) ?></h3>
                                <div class="text-sm text-gray-400 space-y-1">
                                    <p><i class="fa-regular fa-calendar text-red-400"></i> <?= date('M d, Y', strtotime($session['session_date'])) ?> at <?= date('h:i A', strtotime($session['session_date'])) ?></p>
                                    <p><i class="fa-regular fa-id-card text-blue-400"></i> Session ID: <?= $session['session_id'] ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span class="status-badge status-<?= $session['status'] ?>"><?= ucfirst($session['status']) ?></span>
                                <?php if($session['status'] === 'scheduled'): ?>
                                <div class="flex space-x-2">
                                    <button class="btn-complete-session px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm" data-session-id="<?= $session['session_id'] ?>">
                                        Complete
                                    </button>
                                    <button class="btn-cancel-session px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm" data-session-id="<?= $session['session_id'] ?>">
                                        Cancel
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Requests Tab -->
            <div id="requests" class="tab-content bg-neutral-900 rounded-b-xl border border-t-0 border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white">Member Assignment Requests</h2>
                </div>
                
                <div class="space-y-4" id="requestsList">
                    <!-- Requests will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </main>

    <!-- Schedule Session Modal -->
    <div id="scheduleSessionModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700">
            <button class="session-modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Schedule Session</h3>
            
            <form id="scheduleSessionForm">
                <input type="hidden" id="schedule_user_id" name="user_id">
                <input type="hidden" name="trainer_id" value="<?= $trainerId ?>">
                
                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">Member</label>
                    <input type="text" id="schedule_member_name" readonly class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">Session Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="session_date" required class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Session details or goals"></textarea>
                </div>

                <div id="scheduleMessage" class="hidden mb-4"></div>

                <div class="flex space-x-4">
                    <button type="button" class="session-modal-close flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" id="btnScheduleSession" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                        Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            
            // Tab switching
            $('.tab-button').on('click', function() {
                const tabName = $(this).data('tab');
                
                // Remove active from all tab buttons
                $('.tab-button').removeClass('active');
                
                // Add active to clicked button
                $(this).addClass('active');
                
                // Hide all tab content
                $('.tab-content').removeClass('active');
                
                // Show selected tab content
                $('#' + tabName).addClass('active');
                if(tabName === 'requests') {
                    loadRequests();
                }
                
            });

            // Schedule session
            $('.btn-schedule-session').on('click', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).data('name');
                
                $('#schedule_user_id').val(userId);
                $('#schedule_member_name').val(userName);
                $('#scheduleSessionModal').addClass('show');
                $('body').css('overflow', 'hidden');
            });

            $('.session-modal-close').on('click', function() {
                $('#scheduleSessionModal').removeClass('show');
                $('body').css('overflow', 'auto');
            });

            $('#scheduleSessionForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = $('#btnScheduleSession');
                const originalText = submitBtn.text();
                
                submitBtn.html('<span class="loading"></span>').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: 'index.php?controller=Trainer&action=createSession',
                    data: formData,
                    processData: false,
                    contentType: false,
                    // Remove dataType: 'json' temporarily
                    success: function(response) {
                        console.log('Raw response:', response);
                        console.log('Response type:', typeof response);
                        
                        const data = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if(data.success) {
                            showMessage('scheduleMessage', '✓ ' + data.message, 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showMessage('scheduleMessage', data.message, 'error');
                            submitBtn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('XHR:', xhr);
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response Text:', xhr.responseText);
                        showMessage('scheduleMessage', 'An error occurred', 'error');
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // Complete session
            $('.btn-complete-session').on('click', function() {
                const sessionId = $(this).data('session-id');
                if(confirm('Mark this session as completed?')) {
                    updateSessionStatus(sessionId, 'completed');
                }
            });

            // Cancel session
            $('.btn-cancel-session').on('click', function() {
                const sessionId = $(this).data('session-id');
                if(confirm('Cancel this session?')) {
                    updateSessionStatus(sessionId, 'cancelled');
                }
            });

            function updateSessionStatus(sessionId, status) {
                $.ajax({
                    type: 'POST',
                    url: 'index.php?controller=Trainer&action=updateSessionStatus',
                    data: { session_id: sessionId, status: status },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }

            // Load requests
            function loadRequests() {
                $.ajax({
                    type: 'GET',
                    url: 'index.php?controller=Trainer&action=getPendingRequests',
                    dataType: 'json',
                    success: function(response) {
                        if(response.success && response.data) {
                            let html = '';
                            response.data.forEach(req => {
                                html += `
                                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-bold text-white">${req.member_name}</h3>
                                                <p class="text-gray-400 text-sm">${req.email}</p>
                                                <p class="text-gray-400 text-sm mt-2">Requested: ${req.request_date}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button class="btn-accept-request px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg" data-request-id="${req.request_id}">
                                                    Accept
                                                </button>
                                                <button class="btn-reject-request px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg" data-request-id="${req.request_id}">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            $('#requestsList').html(html || '<p class="text-gray-400 text-center py-8">No pending requests</p>');
                        }
                    }
                });
            }

            // Accept/Reject requests
            $(document).on('click', '.btn-accept-request', function() {
                handleRequest($(this).data('request-id'), 'accepted');
            });

            $(document).on('click', '.btn-reject-request', function() {
                handleRequest($(this).data('request-id'), 'rejected');
            });

            function handleRequest(requestId, action) {
                $.ajax({
                    type: 'POST',
                    url: 'index.php?controller=Trainer&action=handleRequest',
                    data: { request_id: requestId, action: action },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            loadRequests();
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }

            function showMessage(elementId, message, type) {
                const messageDiv = $('#' + elementId);
                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                messageDiv.html(`<div class="p-3 rounded-lg ${bgColor} text-white text-sm">${message}</div>`).removeClass('hidden');
                if(type !== 'error') setTimeout(() => messageDiv.addClass('hidden'), 3000);
            }
        });
    </script>
</body>
</html>
