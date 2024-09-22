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
    <title>Psychologist Log In</title>
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

        $doctor_username = $doctor_password = '';

        session_start();
        
        if (isset($_POST["submit"])) {
            $doctor_username = htmlspecialchars($_POST["doctor_username"]);
            $doctor_password = $_POST["doctor_password"];

            // Database connection
            require_once 'config/db.php';

            // Fetch user data
            $sql = "SELECT * FROM doctor WHERE doctor_username = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $doctor_username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if ($user) {
                    if (password_verify($doctor_password, $user["doctor_password"])) {
                        // Set session variables
                        $_SESSION['doctor_logged_in'] = true;
                        $_SESSION['doctor_username'] = $doctor_username;
                        $_SESSION['doctor_id'] = $user['doctor_id'];
                        
                        header("Location: doctor_dashboard.php");
                        die();
                    } else {
                        echo "<div class='alert alert-danger text-center'>Password does not match!</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-center'>Unknown Username!</div>";
                }

                // Close statement and connection
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            } else {
                echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
            }
        }
        ?>

        <!-- Front end -->
        <div class="row justify-content-between">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>PSYCHOLOGIST LOGIN</strong>
                    </div>
                    <div class="panel-body">
                        <form action="login_doctor.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="doctor_username" class="form-control" required="" placeholder="Username" value="<?php echo htmlspecialchars($doctor_username); ?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="doctor_password" class="form-control" required="" placeholder="Password" value="<?php echo htmlspecialchars($doctor_password); ?>">
                            </div>
                            <input type="submit" name="submit" value="Login Now" class="btn success-btn" style="width: 100%;">
                            <p>Psychologist, Don't you registered yet? <a href="register.php">Register Now</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php'; ?>
</body>
</html>
