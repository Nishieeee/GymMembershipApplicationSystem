<?php
include_once "./../App/models/User.php";
if(session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(['path' => "/"]);
    session_start();
}
$userobj = new User();

$me = $userobj->getMember($_SESSION['user_id']);
$user_name = $me['first_name'];
$user_initial = substr($user_name, 0, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
        .header-blur {
            backdrop-filter: blur(10px);
            background: rgba(23, 23, 23, 0.8);
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

        .sidebar-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .sidebar-menu.active {
            max-height: 600px;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: #3b82f6;
        }

        .nav-link.active::after {
            width: 100%;
        }

        .dropdown-toggle {
            transition: all 0.3s ease;
        }

        .dropdown-toggle.active {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .submenu.active {
            max-height: 300px;
        }

        /* Notification styles */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .notification-item {
            animation: slideIn 0.3s ease-out;
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        #notificationDropdown {
            max-height: 500px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-blur fixed top-0 left-0 right-0 z-50 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h1 class="font-bold text-white text-xl hidden sm:block">
                        GYM<span class="text-blue-400">AZING</span>
                    </h1>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center space-x-6">
                    <a href="../public/index.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">Home</a>
                    <a href="classes.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">Classes</a>
                    <a href="index.php?controller=Payment&action=planPayment" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">My Payments</a>
                </nav>

                <!-- User Menu & Notifications -->
                <div class="flex items-center space-x-4">
                    <!-- NOTIFICATION BELL - NEW -->
                    <div class="relative" id="notificationContainer">
                        <button onclick="toggleNotifications()" class="relative p-2 hover:bg-gray-700 rounded-full transition">
                            <!-- Bell Icon -->
                            <svg class="w-6 h-6 text-gray-300 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Badge -->
                            <span id="notificationBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center notification-badge">
                                0
                            </span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-gray-800 rounded-lg shadow-xl border border-gray-700 z-50">
                            <!-- Header -->
                            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                                <h3 class="font-semibold text-lg text-white">Notifications</h3>
                                <button onclick="markAllAsRead()" class="text-sm text-blue-400 hover:text-blue-300 transition">
                                    Mark all as read
                                </button>
                            </div>
                            
                            <!-- List -->
                            <div id="notificationList" class="max-h-96 overflow-y-auto">
                                <div class="p-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p>Loading...</p>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="p-3 border-t border-gray-700 text-center">
                                <a href="notifications.php" class="text-sm text-blue-400 hover:text-blue-300 transition">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="hidden md:flex items-center space-x-3">
                        <span class="text-white font-medium"><?= $user_name ?></span>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            <?= $user_initial ?>
                        </div>
                    </div>
                    
                    <!-- Mobile Hamburger Button -->
                    <button class="lg:hidden hamburger flex flex-col space-y-1.5 p-2" id="mobileMenuBtn">
                        <span class="w-5 h-0.5 bg-white block"></span>
                        <span class="w-5 h-0.5 bg-white block"></span>
                        <span class="w-5 h-0.5 bg-white block"></span>
                    </button>

                    <!-- Desktop Dropdown Menu -->
                    <div class="relative group hidden md:block">
                        <button id="account" class="text-gray-400 hover:text-white transition-colors p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                       <div id="account_menu" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl transition-all duration-200 border border-gray-700">
                            <a href="profile.php" class="block px-4 py-3 text-white hover:bg-gray-700 rounded-t-lg transition-colors">
                                <span class="inline-block mr-2">👤</span> Edit Profile
                            </a>
                            <a href="settings.php" class="block px-4 py-3 text-white hover:bg-gray-700 transition-colors">
                                <span class="inline-block mr-2">⚙️</span> Settings
                            </a>
                            <a href="billing.php" class="block px-4 py-3 text-white hover:bg-gray-700 transition-colors">
                                <span class="inline-block mr-2">💳</span> Billing
                            </a>
                            <hr class="border-gray-600">
                            <a href="../views/auth/logout.php" class="block px-4 py-3 text-red-400 hover:bg-gray-700 rounded-b-lg transition-colors">
                                <span class="inline-block mr-2">🚪</span> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="sidebar-menu lg:hidden" id="sidebarMenu">
                <nav class="py-4 border-t border-gray-700 space-y-1">
                    <a href="dashboard.php" class="block px-4 py-3 text-white hover:bg-gray-800 hover:text-blue-400 rounded transition-colors font-medium">
                        <span class="inline-block mr-2">📊</span> Dashboard
                    </a>

                    <!-- Mobile Submenu: More Options -->
                    <div class="px-4 py-2 mt-2">
                        <button class="w-full flex items-center justify-between text-white hover:text-blue-400 font-medium transition-colors" id="mobileMoreBtn">
                            <span class="flex items-center">
                                <span class="inline-block mr-2">⋯</span> More Options
                            </span>
                            <svg class="w-4 h-4 dropdown-toggle" id="mobileMoreToggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </button>
                        <div class="submenu" id="mobileMoreMenu">
                            <a href="settings.php" class="block px-4 py-2 ml-2 text-gray-400 hover:text-blue-400 rounded transition-colors text-sm">Settings</a>
                            <a href="billing.php" class="block px-4 py-2 ml-2 text-gray-400 hover:text-blue-400 rounded transition-colors text-sm">Billing</a>
                            <a href="../auth/logout.php" class="block px-4 py-2 ml-2 text-red-400 hover:text-red-300 rounded transition-colors text-sm">Logout</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <script>
        $(document).ready(function() {
            // Existing account menu toggle
            $('#account').click((e) => {
                e.stopPropagation();
                $('#account_menu').toggleClass('hidden');
            });

            // Close account menu when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('#account, #account_menu').length) {
                    $('#account_menu').addClass('hidden');
                }
            });

            // Mobile menu toggle
            $('#mobileMenuBtn').click(function() {
                $(this).toggleClass('active');
                $('#sidebarMenu').toggleClass('active');
            });

            // Mobile submenu toggle
            $('#mobileMoreBtn').click(function() {
                $('#mobileMoreToggle').toggleClass('active');
                $('#mobileMoreMenu').toggleClass('active');
            });

            // Close mobile menu when clicking a link
            $('#sidebarMenu a').click(function() {
                if (!$(this).attr('id')) {
                    $('#mobileMenuBtn').removeClass('active');
                    $('#sidebarMenu').removeClass('active');
                }
            });

            // Set active nav link based on current page
            var currentPage = window.location.pathname.split('/').pop();
            $('.nav-link').each(function() {
                var href = $(this).attr('href');
                if (href === currentPage || (currentPage === '' && href === 'dashboard.php')) {
                    $(this).addClass('active');
                }
            });

            // Initialize notification system
            updateUnreadCount();
        });

        // ============================================
        // NOTIFICATION FUNCTIONS
        // ============================================
        let notificationDropdownOpen = false;

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            notificationDropdownOpen = !notificationDropdownOpen;
            
            if(notificationDropdownOpen) {
                dropdown.classList.remove('hidden');
                loadNotifications();
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.getElementById('notificationContainer');
            if(container && !container.contains(event.target)) {
                document.getElementById('notificationDropdown').classList.add('hidden');
                notificationDropdownOpen = false;
            }
        });

        function loadNotifications() {
            fetch('index.php?controller=notification&action=getNotifications')
                .then(response => response.json())
                .then(notifications => {
                    displayNotifications(notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notificationList').innerHTML = 
                        '<div class="p-8 text-center text-gray-400"><p>Error loading notifications</p></div>';
                });
        }

        function displayNotifications(notifications) {
            const list = document.getElementById('notificationList');
            
            if(notifications.length === 0) {
                list.innerHTML = `
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No notifications</p>
                    </div>
                `;
                return;
            }
            
            list.innerHTML = notifications.map(notif => `
                <div class="notification-item p-4 border-b border-gray-700 hover:bg-gray-700 cursor-pointer ${notif.is_read == 0 ? 'bg-gray-750' : ''}" 
                     onclick="markAsRead(${notif.notification_id}, '${notif.link || '#'}')">
                    <div class="flex items-start">
                        <div class="mr-3 mt-1">
                            ${getNotificationIcon(notif.type)}
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-sm ${notif.is_read == 0 ? 'text-white' : 'text-gray-300'}">${notif.title}</h4>
                                ${notif.is_read == 0 ? '<span class="w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}
                            </div>
                            <p class="text-sm text-gray-400 mt-1">${notif.message}</p>
                            <p class="text-xs text-gray-500 mt-1">${timeAgo(notif.created_at)}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getNotificationIcon(type) {
            const icons = {
                success: '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                warning: '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
                info: '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
            };
            return icons[type] || icons.info;
        }

        function markAsRead(notificationId, link) {
            fetch('index.php?controller=notification&action=markAsRead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    updateUnreadCount();
                    if(link && link !== '#' && link !== 'null') {
                        window.location.href = link;
                    } else {
                        loadNotifications(); // Refresh the list
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllAsRead() {
            fetch('index.php?controller=notification&action=markAllAsRead', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    loadNotifications();
                    updateUnreadCount();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateUnreadCount() {
            fetch('index.php?controller=notification&action=getUnreadCount')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if(data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };
            
            for(let interval in intervals) {
                const count = Math.floor(seconds / intervals[interval]);
                if(count >= 1) {
                    return count + ' ' + interval + (count > 1 ? 's' : '') + ' ago';
                }
            }
            
            return 'Just now';
        }

        // Auto-refresh every 30 seconds
        setInterval(updateUnreadCount, 30000);
    </script>
</body>
</html>