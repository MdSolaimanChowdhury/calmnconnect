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
    <title>PSYCHOLOGIST REGISTRATION</title>
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

        if(isset($_POST["submit"])) {
            $doctor_username = htmlspecialchars($_POST["doctor_username"]);
            $doctor_name = htmlspecialchars($_POST["doctor_name"]);
            $doctor_email = htmlspecialchars($_POST["doctor_email"]);
            $doctor_mobile = htmlspecialchars($_POST["doctor_mobile"]);
            $doctor_institution = htmlspecialchars($_POST["doctor_institution"]);
            $doctor_password = $_POST["doctor_password"];
            $confirm_password = $_POST["confirm_password"];

            $password_hash = password_hash($doctor_password, PASSWORD_DEFAULT);

            $errors = array();

            if(empty($doctor_username) || empty($doctor_name) || empty($doctor_email) || empty($doctor_mobile) || empty($doctor_institution) || empty($doctor_password) || empty($confirm_password)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($doctor_email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($doctor_password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($doctor_password != $confirm_password) {
                array_push($errors, "Password does not match");
            }

            // Database connection
            require_once "config/db.php";
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Existing email check
            $sql = "SELECT * FROM doctor WHERE doctor_email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $doctor_email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);
                if($rowCount > 0) {
                    array_push($errors, "You have already registered. Please log in now");
                }
            }

            // Existing username check
            $sql = "SELECT * FROM doctor WHERE doctor_username = ?";
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $doctor_username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);
                if($rowCount > 0) {
                    array_push($errors, "This username already exists. Please choose another username!");
                }
            }

            if(count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger text-center'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO doctor (doctor_username, doctor_name, doctor_email, doctor_mobile, doctor_institution, doctor_password) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $doctor_username, $doctor_name, $doctor_email, $doctor_mobile, $doctor_institution, $password_hash);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success text-center'>Registration completed successfully.</div>";
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

        <div class="row justify-content-between">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>PSYCHOLOGIST REGISTRATION</strong>
                    </div>
                    <div class="panel-body">
                        <form action="register_doctor.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="doctor_username" class="form-control" required="" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="doctor_name" class="form-control" required="" placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="doctor_email" class="form-control" required="" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="tel" name="doctor_mobile" class="form-control" required="" placeholder="Mobile No.">
                            </div>
                            <div class="form-group">
                                <label>Institution</label>
                                <input type="text" name="doctor_institution" class="form-control" required="" placeholder="Institution">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="doctor_password" class="form-control" required="" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required="" placeholder="Confirm Password">
                            </div>
                            <br>

                            <input type="submit" name="submit" value="Register Now" class="btn success-btn" style="width: 100%;">
                            <p>Already registered as Psychologist? <a href="login_doctor.php">Log In now</a></p>
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

