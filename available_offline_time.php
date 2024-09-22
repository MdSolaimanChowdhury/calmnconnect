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
    $offline_saturday = htmlspecialchars($_POST["offline_saturday"]);
    $offline_sunday = htmlspecialchars($_POST["offline_sunday"]);
    $offline_monday = htmlspecialchars($_POST["offline_monday"]);
    $offline_tuesday = htmlspecialchars($_POST["offline_tuesday"]);
    $offline_wednesday = htmlspecialchars($_POST["offline_wednesday"]);
    $offline_thursday = htmlspecialchars($_POST["offline_thursday"]);
    $offline_friday = htmlspecialchars($_POST["offline_friday"]);

    // Fetching the Location input
    $location_saturday = htmlspecialchars($_POST["location_saturday"]);
    $location_sunday = htmlspecialchars($_POST["location_sunday"]);
    $location_monday = htmlspecialchars($_POST["location_monday"]);
    $location_tuesday = htmlspecialchars($_POST["location_tuesday"]);
    $location_wednesday = htmlspecialchars($_POST["location_wednesday"]);
    $location_thursday = htmlspecialchars($_POST["location_thursday"]);
    $location_friday = htmlspecialchars($_POST["location_friday"]);

    // Prepare and execute the update query
    $sql = "UPDATE doctor SET offline_saturday = ?, location_saturday = ?, offline_sunday = ?, location_sunday = ?, offline_monday = ?, location_monday = ?, offline_tuesday = ?, location_tuesday = ?, offline_wednesday = ?, location_wednesday = ?, offline_thursday = ?, location_thursday = ?, offline_friday = ?, location_friday = ? WHERE doctor_username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssssssssss", $offline_saturday, $location_saturday, $offline_sunday, $location_sunday, $offline_monday, $location_monday, $offline_tuesday, $location_tuesday, $offline_wednesday, $location_wednesday, $offline_thursday, $location_thursday, $offline_friday, $location_friday, $doctor_username);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success text-center'>Offline times updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger text-center'>Something went wrong!</div>";
    }
}

// Fetch current available times
$sql = "SELECT offline_saturday, location_saturday, offline_sunday, location_sunday, offline_monday, location_monday, offline_tuesday, location_tuesday, offline_wednesday, location_wednesday, offline_thursday, location_thursday, offline_friday, location_friday FROM doctor WHERE doctor_username = ?";
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
    <title>Available Offline Time</title>
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
                        <strong>UPDATE OFFLINE TIME AND THE LOCATION</strong>
                    </div>
                    <div class="panel-body">
                        <form action="available_offline_time.php" method="post">
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
                                    <li>
                                        <p>2nd line is for specifying the appointment <strong>Location</strong> . Please fill in the location details.</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label>Saturday</label>
                                <input type="text" name="offline_saturday" class="form-control" placeholder="Available times for Saturday" value="<?php echo htmlspecialchars($times['offline_saturday']); ?>">
                                <input type="text" name="location_saturday" class="form-control" placeholder="Location for Saturday" value="<?php echo htmlspecialchars($times['location_saturday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Sunday</label>
                                <input type="text" name="offline_sunday" class="form-control" placeholder="Available times for Sunday" value="<?php echo htmlspecialchars($times['offline_sunday']); ?>">
                                <input type="text" name="location_sunday" class="form-control" placeholder="Location for Sunday" value="<?php echo htmlspecialchars($times['location_sunday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Monday</label>
                                <input type="text" name="offline_monday" class="form-control" placeholder="Available times for Monday" value="<?php echo htmlspecialchars($times['offline_monday']); ?>">
                                <input type="text" name="location_monday" class="form-control" placeholder="Location for Monday" value="<?php echo htmlspecialchars($times['location_monday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Tuesday</label>
                                <input type="text" name="offline_tuesday" class="form-control" placeholder="Available times for Tuesday" value="<?php echo htmlspecialchars($times['offline_tuesday']); ?>">
                                <input type="text" name="location_tuesday" class="form-control" placeholder="Location for Tuesday" value="<?php echo htmlspecialchars($times['location_tuesday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Wednesday</label>
                                <input type="text" name="offline_wednesday" class="form-control" placeholder="Available times for Wednesday" value="<?php echo htmlspecialchars($times['offline_wednesday']); ?>">
                                <input type="text" name="location_wednesday" class="form-control" placeholder="Location for Wednesday" value="<?php echo htmlspecialchars($times['location_wednesday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Thursday</label>
                                <input type="text" name="offline_thursday" class="form-control" placeholder="Available times for Thursday" value="<?php echo htmlspecialchars($times['offline_thursday']); ?>">
                                <input type="text" name="location_thursday" class="form-control" placeholder="Location for Thursday" value="<?php echo htmlspecialchars($times['location_thursday']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Friday</label>
                                <input type="text" name="offline_friday" class="form-control" placeholder="Available times for Friday" value="<?php echo htmlspecialchars($times['offline_friday']); ?>">
                                <input type="text" name="location_friday" class="form-control" placeholder="Location for Friday" value="<?php echo htmlspecialchars($times['location_friday']); ?>">
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