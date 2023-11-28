<!DOCTYPE html>
<html>
<head>
    <title>PS-UI Setup</title>
</head>
<body>
    <?php
    if(file_exists('./config/config.ini')) {
        // Redirect to login page
        header("Location: login.php");
        exit;
      }      

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $server = $_POST['server'];

        $data = [
            "server" => $server,
        ];
        $content = '';
        foreach ($data as $key => $value) {
            $content .= "{$key} = \"{$value}\"\n";
        }
        file_put_contents('./config/config.ini', $content);

        header("Location: login.php");
        exit;
    }
   ?>

    <form method="post" action="index.php">
        <label for="server">Server IP/Domain:</label><br>
        <input type="text" id="server" name="server" required><br>
        <input type="submit" value="Submit">  
    </form>
</body>
</html>
