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
                    <a href="dashboard.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">Dashboard</a>
                    <a href="classes.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">Classes</a>
                    <a href="classes.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">My Payments</a>
                    <a href="profile.php" class="nav-link text-white hover:text-blue-400 font-medium transition-colors">Profile</a>
                </nav>

                <!-- User Menu & Mobile Hamburger -->
                <div class="flex items-center space-x-4">
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
                        <button class="text-gray-400 hover:text-white transition-colors p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-700">
                            <a href="profile.php" class="block px-4 py-3 text-white hover:bg-gray-700 rounded-t-lg transition-colors">
                                <span class="inline-block mr-2"></span> Edit Profile
                            </a>
                            <a href="settings.php" class="block px-4 py-3 text-white hover:bg-gray-700 transition-colors">
                                <span class="inline-block mr-2"></span> Settings
                            </a>
                            <a href="billing.php" class="block px-4 py-3 text-white hover:bg-gray-700 transition-colors">
                                <span class="inline-block mr-2"></span> Billing
                            </a>
                            <hr class="border-gray-600">
                            <a href="../auth/logout.php" class="block px-4 py-3 text-red-400 hover:bg-gray-700 rounded-b-lg transition-colors">
                                <span class="inline-block mr-2"></span> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="sidebar-menu lg:hidden" id="sidebarMenu">
                <nav class="py-4 border-t border-gray-700 space-y-1">
                    <a href="dashboard.php" class="block px-4 py-3 text-white hover:bg-gray-800 hover:text-blue-400 rounded transition-colors font-medium">
                        <span class="inline-block mr-2"></span> Dashboard
                    </a>
                    <a href="classes.php" class="block px-4 py-3 text-white hover:bg-gray-800 hover:text-blue-400 rounded transition-colors font-medium">
                        <span class="inline-block mr-2"></span> Classes
                    </a>
                    <a href="profile.php" class="block px-4 py-3 text-white hover:bg-gray-800 hover:text-blue-400 rounded transition-colors font-medium">
                        <span class="inline-block mr-2"></span> Profile
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
        });
    </script>
</body>
</html>