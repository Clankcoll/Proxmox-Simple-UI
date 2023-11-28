<!DOCTYPE html>
<html>
<head>
    <title>Management</title>
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
        // Redirect to login page
        header("Location: login.php");
        exit;
    }

    //access config.ini to fetch server IP/domain
    $ini_array = parse_ini_file("./config/config.ini");
    $server = $ini_array["server"];
    ?>

    <button onclick="location.href='vm_creation.php'" type="button">VM Creation</button>
    <button onclick="location.href='https://<?php echo $server?>:8006/'" type="button">VM Management</button> 
    <button onclick="location.href='https://<?php echo $server?>:8006/'" type="button">Settings</button> 
</body>
</html>
