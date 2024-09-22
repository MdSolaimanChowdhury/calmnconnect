<?php
session_start(); // Ensure session starts

// Database connection
require_once 'config/db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the doctor is logged in
if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
    header("Location: login_doctor.php");
    exit;
}

// Get the logged-in doctor's username
$doctor_username = $_SESSION['doctor_username'];

if (isset($_POST["submit"])) {
    // Fetch and sanitize user input
    $available_saturday = htmlspecialchars($_POST["available_saturday"]);
    $available_sunday = htmlspecialchars($_POST["available_sunday"]);
    $available_monday = htmlspecialchars($_POST["available_monday"]);
    $available_tuesday = htmlspecialchars($_POST["available_tuesday"]);
    $available_wednesday = htmlspecialchars($_POST["available_wednesday"]);
    $available_thursday = htmlspecialchars($_POST["available_thursday"]);
    $available_friday = htmlspecialchars($_POST["available_friday"]);

    // Prepare and execute the update query
    $sql = "UPDATE doctor SET available_saturday = ?, available_sunday = ?, available_monday = ?, available_tuesday = ?, available_wednesday = ?, available_thursday = ?, available_friday = ? WHERE doctor_username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $available_saturday, $available_sunday, $available_monday, $available_tuesday, $available_wednesday, $available_thursday, $available_friday, $doctor_username);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success text-center'>Online times updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
    }
}

// Fetch current available times
$sql = "SELECT available_saturday, available_sunday, available_monday, available_tuesday, available_wednesday, available_thursday, available_friday FROM doctor WHERE doctor_username = ?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $doctor_username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $times = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
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
    <title>Available Online Time</title>
</head>
<body>
    <!-- Header -->
    <?php require_once 'include/header_doctor.php'; ?>

    <!-- Form -->
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>UPDATE ONLINE TIME</strong>
                    </div>
                    <div class="panel-body">
                        <form action="available_online_time.php" method="post">
                            <div class="panel panel-info">
                                <ul class="custom-list">
                                    <li>
                                        <strong>Please Follow this rules to fill up this section of form: </strong>
                                    </li>
                                    <li>
                                        <p>If you don't have any free time in that particular day, type 'Not Free'. [without the quotes]</p>
                                    </li>
                                    <li>
                                        <p>If you are free from 6PM to 8PM, type '6PM - 8PM' [without the quotes]</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label>Saturday</label>
                                <input type="text" name="available_saturday" class="form-control" placeholder="Available times for Saturday" value="<?php echo htmlspecialchars($times['available_saturday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Sunday</label>
                                <input type="text" name="available_sunday" class="form-control" placeholder="Available times for Sunday" value="<?php echo htmlspecialchars($times['available_sunday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Monday</label>
                                <input type="text" name="available_monday" class="form-control" placeholder="Available times for Monday" value="<?php echo htmlspecialchars($times['available_monday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Tuesday</label>
                                <input type="text" name="available_tuesday" class="form-control" placeholder="Available times for Tuesday" value="<?php echo htmlspecialchars($times['available_tuesday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Wednesday</label>
                                <input type="text" name="available_wednesday" class="form-control" placeholder="Available times for Wednesday" value="<?php echo htmlspecialchars($times['available_wednesday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Thursday</label>
                                <input type="text" name="available_thursday" class="form-control" placeholder="Available times for Thursday" value="<?php echo htmlspecialchars($times['available_thursday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Friday</label>
                                <input type="text" name="available_friday" class="form-control" placeholder="Available times for Friday" value="<?php echo htmlspecialchars($times['available_friday']); ?>">
                            </div>
                            <input type="submit" name="submit" value="Update Times" class="btn success-btn" style="width: 100%;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php require_once 'include/footer.php'; ?>
    <?php mysqli_close($conn); ?>
</body>
</html>