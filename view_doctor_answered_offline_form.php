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
    <title>View Offline Form</title>
    <style>
        .form-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 30px 40px 30px 40px;
        }

        .form-header {
            border-bottom: 2px solid #137547;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .form-header h2 {
            margin-bottom: 0;
        }

        .form-details p {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .form-label {
            font-weight: bold;
            color: #343a40;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .form-container {
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
            padding: 10px 20px;
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

    <div class="container">
        <div class="form-container">
            <?php
            // Enable error reporting
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Database connection
            require_once 'config/db.php';

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Ensure doctor is logged in
            if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
                header("Location: login_doctor.php");
                exit;
            }

            // Get the form ID from the URL
            if (isset($_GET['form_id'])) {
                $form_id = intval($_GET['form_id']);

                // Fetch the form data from the database
                $sql = "SELECT * FROM offline_form WHERE offline_form_id = ? AND doctor_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $form_id, $_SESSION['doctor_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $form = $result->fetch_assoc();

                if ($form) {
                    // Display the form details
                    echo '
                    <div class="form-header">
                        <h3>Offline Form ID: ' . htmlspecialchars($form['offline_form_id']) . '</h3>
                    </div>
                    <div class="form-details">
                        <p><span class="form-label">Name:</span> ' . htmlspecialchars($form['patient_name']) . '</p>
                        <p><span class="form-label">Mobile no.:</span> ' . htmlspecialchars($form['patient_mobile']) . '</p>
                        <p><span class="form-label">Gender:</span> ' . htmlspecialchars($form['patient_gender']) . '</p>
                        <p><span class="form-label">Age:</span> ' . htmlspecialchars($form['patient_age']) . '</p>
                        <p><span class="form-label">Marital Status:</span> ' . htmlspecialchars($form['patient_marry']) . '</p>
                        <p><span class="form-label">Submitted on:</span> ' . htmlspecialchars($form['created_at']) . '</p>
                        <p><span class="form-label">Patient Message:</span> ' . nl2br(htmlspecialchars($form['patient_message'])) . '</p>
                        <p><span class="form-label">Confirmation of Psychologist:</span> ' . nl2br(htmlspecialchars($form['doctor_confirmation'])) . '</p>
                    </div>';
                } else {
                    echo '<p class="text-center">Form not found or you do not have permission to view it.</p>';
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();
            } else {
                echo '<p class="text-center">No form ID provided.</p>';
            }
            ?>
        </div>

        <div class="back-btn">
            <a href="doctor_offline_form_history.php">Back to Offline Forms History</a>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php'; ?>
</body>
</html>