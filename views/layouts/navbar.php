<?php 
    $navList = [
        ["navItem" => "Home", "navLink" => "/home"],
        ["navItem" => "Apply", "navLink" => "/apply"],
        ["navItem" => "About us", "navLink" => "/about"],
    ];

    foreach ($navList as $nav) {
        $navItem = $nav['navItem'];
        $navLink = $nav['navLink'];

        echo "
            <a href='$navLink'>$navItem</a>
        ";
    }
?>