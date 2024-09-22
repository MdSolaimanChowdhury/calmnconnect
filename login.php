<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$patient_username = $patient_password = '';

// Display a login message if set
if (isset($_SESSION['login_message'])) {
    echo "<div class='alert alert-warning text-center' style='font-size: 24px; margin-top: 20px;'>{$_SESSION['login_message']}</div>";
    unset($_SESSION['login_message']);
}

if (isset($_POST["submit"])) {
    $patient_username = htmlspecialchars($_POST["patient_username"]);
    $patient_password = $_POST["patient_password"];

    // Database connection
    require_once 'config/db.php';

    // Fetch user data
    $sql = "SELECT * FROM patient WHERE patient_username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $patient_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($patient_password, $user["patient_password"])) {
                // Set session variables
                $_SESSION['patient_logged_in'] = true;
                $_SESSION['patient_username'] = $user['patient_username'];
                $_SESSION['patient_id'] = $user['patient_id'];  // Set the patient ID in session

                // Redirect to the patient dashboard
                header("Location: patient_dashboard.php");
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
        <!-- Front end -->
        <div class="row justify-content-between">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>USER LOGIN</strong>
                    </div>
                    <div class="panel-body">
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="patient_username" class="form-control" required="" placeholder="Username" value="<?php echo htmlspecialchars($patient_username); ?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="patient_password" class="form-control" required="" placeholder="Password" value="<?php echo htmlspecialchars($patient_password); ?>">
                            </div>
                            <input type="submit" name="submit" value="Login Now" class="btn success-btn" style="width: 100%;">
                            <p>Don't have an account? <a href="register.php">Register Now</a></p>
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