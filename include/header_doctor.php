<?php
// Check if a session is already active before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the doctor is logged in
if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
    header("Location: login_doctor.php");
    exit;
}

// Get the logged-in doctor's username
$doctor_username = $_SESSION['doctor_username'];

// Database connection
require_once 'config/db.php';

// Ensure the connection is still valid
if ($conn instanceof mysqli) {
    // Fetch the doctor's full name
    $sql = "SELECT doctor_name FROM doctor WHERE doctor_username = ?";
    $stmt = mysqli_stmt_init($conn);  // line 21
    if ($stmt && mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $doctor_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $doctor = mysqli_fetch_assoc($result);
        $doctor_full_name = $doctor ? $doctor['doctor_name'] : 'Doctor';
        mysqli_stmt_close($stmt);
    } else {
        $doctor_full_name = 'Doctor'; // Default if something goes wrong
    }
} else {
    die("Database connection is not available.");
}

?>


<!-- Header HTML -->
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid" style="padding-left: 25px;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Calm and Connect</a>
                <ul class="menubar">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                </ul>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php echo htmlspecialchars($doctor_full_name); ?> <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="doctor_dashboard.php">Dashboard</a></li>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>