<?php 

    session_start();

    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header("location: /auth/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <style href="../public/assets/css/style.css">
    /* Custom gradient background */
    .gradient-bg {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
    }

    /* Animated gradient for hero */
    .hero-gradient {
        background: linear-gradient(135deg, rgba(29, 78, 216, 0.1) 0%, rgba(30, 58, 138, 0.2) 100%);
    }

    /* Smooth transitions */
    * {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Custom scrollbar */
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

    /* Card hover effects */
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(29, 78, 216, 0.3);
    }

    .plan-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    /* Button pulse animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    .btn-primary:hover {
        animation: pulse 2s infinite;
    }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <?php include_once __DIR__ . "/layouts/navbar.php" ?>
    <main class="min-h-screen">
        
    </main>

</body>
</html>