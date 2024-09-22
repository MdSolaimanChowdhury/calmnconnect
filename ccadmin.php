<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script type="text/javascript" src="assets/js/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    <title>LOG IN</title>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header.php'; ?>


    <div class="container">
        <?php
        // Enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        session_start();

        if (isset($_POST["submit"])) {
            $admin_username = $_POST["admin_username"];
            $admin_password = $_POST["admin_password"];

            #database
            require_once 'config/db.php';
            $sql = "SELECT * FROM adminn WHERE admin_username = '$admin_username' AND admin_password = '$admin_password'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            if($user) {
                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin_username;

                header("Location: admin_dashboard.php");
                die();
            }else {
                echo "<div class='alert alert-danger text-center'>Unknown Username or Password Doesn't match!!</div>";
            }
        }
        ?>

        <!-- Front End -->
        <div class="row justify-content-between">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>ADMIN LOGIN</strong>
                    </div>
                    <div class="panel-body">
                        <form action="ccadmin.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="admin_username" class="form-control" required="" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="admin_password" class="form-control" required="" placeholder="Password">
                            </div>
                            <input type="submit" name="submit" value="Login Now" class="btn success-btn" style="width: 100%;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php' ?>
</body>
</html>