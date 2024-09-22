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
    <title>Unread Online Forms</title>
    <style>
        .form-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            margin-left: 40px;
            margin-right: 40px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-card h4 {
            margin-bottom: 15px;
        }

        .form-card p {
            margin: 0;
            color: #6c757d;
        }

        .form-card a {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
        }

        .form-card a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 15px;
            }
        }

        .back-btn {
            margin-top: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .back-btn a {
            color: #fff;
            background-color: #137547;
            padding: 15px 20px 15px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn a:hover {
            background-color: #124429;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php require_once 'include/header_doctor.php'; ?>

    <!-- Php Error -->
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>

    <?php
    // Database connection
    require_once 'config/db.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
        header("Location: login_doctor.php");
        exit;
    }

    if (!isset($_SESSION['doctor_id'])) {
        echo "Doctor ID is not set in the session.";
        exit;
    }

    $doctor_id = $_SESSION['doctor_id'];

    $sql = "SELECT online_form_id, patient_gender, patient_age, patient_marry, patient_message, created_at 
            FROM online_form 
            WHERE doctor_id = ? AND doctor_confirmation = 'Yet to be Confirmed' 
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param('i', $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container">
        <h2 class="text-center my-4">Unread Online Forms</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="form-card">
                    <h4>Online Form ID: <?php echo htmlspecialchars($row['online_form_id']); ?></h4>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['patient_gender']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($row['patient_age']); ?></p>
                    <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($row['patient_marry']); ?></p>
                    <p><strong>Submitted on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <a href="view_online_form.php?form_id=<?php echo urlencode($row['online_form_id']); ?>">View Full Form</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No unread online forms found.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>

        <div class="back-btn">
            <a href="doctor_dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php'; ?>
</body>
</html>