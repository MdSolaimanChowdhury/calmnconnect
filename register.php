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
    <title>Register Now</title>
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

        $patient_username = $patient_email = $patient_password = $confirm_password = "";
        $errors = array();

        if(isset($_POST["submit"])) {
            $patient_username = htmlspecialchars($_POST["patient_username"]);
            $patient_email = htmlspecialchars($_POST["patient_email"]);
            $patient_password = $_POST["patient_password"];
            $confirm_password = $_POST["confirm_password"];

            $password_hash = password_hash($patient_password, PASSWORD_DEFAULT);

            if(empty($patient_username) || empty($patient_email) || empty($patient_password) || empty($confirm_password)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($patient_password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($patient_password != $confirm_password) {
                array_push($errors, "Passwords do not match");
            }

            // Database connection
            require_once "config/db.php";

            // Existing email check
            $sql = "SELECT * FROM patient WHERE patient_email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $patient_email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) > 0) {
                    array_push($errors, "You have already registered. Please log in now");
                }
            }

            // Existing username check
            $sql = "SELECT * FROM patient WHERE patient_username = ?";
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $patient_username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) > 0) {
                    array_push($errors, "This username already exists. Please choose another username!");
                }
            }

            if(count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger text-center'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO patient (patient_username, patient_email, patient_password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $patient_username, $patient_email, $password_hash);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success text-center'>Registration completed successfully.</div>";
                        // Clear form values
                        $patient_username = $patient_email = $patient_password = $confirm_password = "";
                    } else {
                        die("Something went wrong!");
                    }
                } else {
                    die("Something went wrong!");
                }
            }

            // Close statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
        ?>

        <!-- front end -->
        <div class="row justify-content-between">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>USER REGISTRATION</strong>
                    </div>
                    <div class="panel-body">
                        <form action="register.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="patient_username" class="form-control" required="" placeholder="Username" value="<?php echo htmlspecialchars($patient_username); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="patient_email" class="form-control" required="" placeholder="Email" value="<?php echo htmlspecialchars($patient_email); ?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="patient_password" class="form-control" required="" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required="" placeholder="Confirm Password">
                            </div>
                            <input type="submit" name="submit" value="Register Now" class="btn success-btn" style="width: 100%;">
                            <p>Already have an account? <a href="login.php">Log In now</a></p>
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
