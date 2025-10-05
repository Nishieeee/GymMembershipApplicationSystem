<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymazing!</title>
    <!-- <link rel="stylesheet" href="../public/assets/css/bs/bootstrap.min.css"> -->
     <script>
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
    </script>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js">
    </script>
    
</head>
<body>
    
    <!-- header -->
    <?php include '../views/layouts/header.php'?>

    <!-- main content -->
    <main class="bg-primary text-text font-sans">
        <?php include '../public/index.php' ?>
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