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
    <title>Psychologist Dashboard</title>
    <style>
        /* Styling the headers */
        .section-header {
            background-color: #137547;
            color: white;
            padding: 15px;
            text-align: center;
            margin: 25px auto;
            border-radius: 5px;
            font-size: 22px;
            max-width: 700px;
        }

        /* Styling the buttons */
        .dashboard-button {
            background-color: #003135;
            color: white;
            font-size: 18px;
            margin: 15px auto;
            padding: 20px;
            border-radius: 10px;
            border: none;
            text-align: center;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 600px;
            display: block;
        }

        .dashboard-button:hover {
            font-size:20px;
            transition: all 500ms;
            color: orange;
        }

        .dashboard-button i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header_doctor.php' ?>

    <?php
    // Database connection
    require_once 'config/db.php';

    session_start();

    if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
        header("Location: login_doctor.php");
        exit;
    }
    ?>

    <div class="container">
        <h2 class="text-center">Welcome to Your Dashboard, Psychologist!</h2>

        <!-- Appointments Section -->
        <div class="section-header">New Appointments</div>
        
        <div class="row">
            <div class="col-12">
                <a href="doctor_anonymous_form_unread.php" class="btn dashboard-button">
                    <i class="fas fa-user-secret"></i>Anonymous Appointments
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="doctor_online_form_unread.php" class="btn dashboard-button">
                    <i class="fas fa-video"></i>Online Appointments
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="doctor_offline_form_unread.php" class="btn dashboard-button">
                    <i class="fas fa-clinic-medical"></i>Offline Appointments
                </a>
            </div>
        </div>

        <!-- Appointments History Section -->
        <div class="section-header">Appointments History</div>

        <div class="row">
            <div class="col-12">
                <a href="doctor_anonymous_form_history.php" class="btn dashboard-button">
                    <i class="fas fa-history"></i>Anonymous Appointments History
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="doctor_online_form_history.php" class="btn dashboard-button">
                    <i class="fas fa-history"></i>Online Appointments History
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="doctor_offline_form_history.php" class="btn dashboard-button">
                    <i class="fas fa-history"></i>Offline Appointments History
                </a>
            </div>
        </div>

        <!-- Update Time & Profile Section -->
        <div class="section-header">Update Time & Profile</div>

        <div class="row">
            <div class="col-12">
                <a href="available_online_time.php" class="btn dashboard-button">
                    <i class="fas fa-clock"></i>Update Online Appointment Time
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="available_offline_time.php" class="btn dashboard-button">
                    <i class="fas fa-clock"></i>Update Offline Appointment Time
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="update_profile.php" class="btn dashboard-button">
                    <i class="fas fa-user-cog"></i>Update Profile
                </a>
            </div>
        </div>

    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php' ?>
</body>
</html>