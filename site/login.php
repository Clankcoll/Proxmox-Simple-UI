<!DOCTYPE html>
<html>
<head>
    <title>PS-UI Login</title>
    <link rel="stylesheet" type="text/css" href="./style/style.css">
</head>
<body>
    <?php
    $errorMessage = '';
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Add this line if you want to bypass SSL certificate verifications

        $response = json_decode(curl_exec($ch));
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!empty($response->data->ticket)) {
            session_start();
            $_SESSION["loggedIn"] = true;
            $_SESSION["CSRFPreventionToken"] = $response->data->CSRFPreventionToken;
            $_SESSION["ticket"] = $response->data->ticket;
            header("Location: management.php");
            exit;
        } else {
             // Here you can add a switch statement to display error messages based on the httpCode
             switch($httpCode) {
                 case 401:
                     $errorMessage = 'Authentication failed. Please check your username and password.';
                     break;
                 case 404:
                     $errorMessage = 'The requested URL was not found on the server.';
                     break;
                 default:
                     $errorMessage = 'An error occurred. Please try again.';
             }
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
                  <label class="form-label" for="username">Username</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                  <input type="password" id="password" name="password" class="form-control" />
                  <label class="form-label" for="password">Password</label>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">Log In</button>

                <?php if(!empty($errorMessage)) : ?>
                  <div class="alert alert-danger mt-4">
                      <?php echo $errorMessage; ?>
                  </div>
                <?php endif; ?>

              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</body>
</html>
