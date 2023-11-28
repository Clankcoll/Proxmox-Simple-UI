<!DOCTYPE html>
<html>
<head>
    <title>PS-UI Login</title>
    <link rel="stylesheet" type="text/css" href="./style/style.css">
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

<section class=" text-center text-lg-start">
  <div class="card mb-3">
    <div class="row g-0 d-flex align-items-center">
      <div class="col-lg-4 d-none d-lg-flex">
        <img src="./style/pictures/proxmox-logo-stacked-color.svg" alt="Proxmox Logo"
          class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
      </div>
      <div class="col-lg-8">
        <div class="card-body py-5 px-md-5">

          <form method="post" action="login.php">
            <!-- Username Input -->
            <div class="form-outline mb-4">
              <input type="text" id="username" name="username" class="form-control" />
              <label class="form-label" for="Username">Username</label>
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
              <input type="password" id="form2Example2" name="password" class="form-control" />
              <label class="form-label" for="password">Password</label>
            </div>
            <!-- Submit button -->
            <button type="button" class="btn btn-primary btn-block mb-4">Log In</button>

          </form>

        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
