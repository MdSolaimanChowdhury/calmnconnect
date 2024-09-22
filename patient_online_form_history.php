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
    <title>Online Forms History</title>
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
    <?php require_once 'include/header_patient.php' ?>

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

    if (!isset($_SESSION['patient_logged_in']) || $_SESSION['patient_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }

    // Ensure that the patient_id is set in the session
    if (!isset($_SESSION['patient_id'])) {
        echo "Patient ID is not set in the session.";
        exit;
    }

    $patient_id = $_SESSION['patient_id'];

    // Fetch the anonymous forms submitted by the logged-in patient
    $sql = "SELECT online_form_id, patient_age, patient_marry, patient_message, doctor_confirmation, created_at 
            FROM online_form 
            WHERE patient_id = ?
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container">
        <h2 class="text-center my-4">Online Forms History</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="form-card">
                    <h4>Online Form ID: <?php echo htmlspecialchars($row['online_form_id']); ?></h4>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($row['patient_age']); ?></p>
                    <p><strong>Submitted on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <p><strong>Psychologist's Confirmation:</strong> <?php echo htmlspecialchars($row['doctor_confirmation']); ?></p>
                    <a href="patient_view_online_form.php?form_id=<?php echo urlencode($row['online_form_id']); ?>">View Full Form</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No History of Anonymous Forms.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>

        <div class="back-btn">
            <a href="patient_dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php'; ?>
</body>
</html>