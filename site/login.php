<!DOCTYPE html>
<html>
<head>
    <title>PS-UI Login</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //access config.ini to fetch server IP/domain
        $ini_array = parse_ini_file("./config/config.ini");
        $server = $ini_array["server"];
        $username= $_POST['username'];
        $password= $_POST['password'];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://".$server."/api2/json/access/ticket");
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"username=".$username."&password=".$password);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        if (!empty($response->data->ticket)) {
            session_start();
            $_SESSION["loggedIn"] = true;
            $_SESSION["CSRFPreventionToken"] = $response->data->CSRFPreventionToken;
            $_SESSION["ticket"] = $response->data->ticket;
            header("Location: management.php");
            exit;
        }
    }
    ?>

    <form method="post" action="login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="Submit">  
    </form>
</body>
</html>
