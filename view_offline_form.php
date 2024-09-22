<?php
// Start of the script
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once 'config/db.php';

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
    header("Location: login_doctor.php");
    exit;
}

// Process the solution submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_solution']) && isset($_GET['form_id'])) {
    $form_id = intval($_GET['form_id']);
    $solution = htmlspecialchars($_POST['solution']);
    $update_sql = "UPDATE offline_form SET doctor_confirmation = ? WHERE offline_form_id = ? AND doctor_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sii', $solution, $form_id, $_SESSION['doctor_id']);
    if ($update_stmt->execute()) {
        // Redirect to prevent resubmission on page refresh
        header("Location: view_offline_form.php?form_id=$form_id&solution_submitted=true");
        exit;
    } else {
        $error_message = "Failed to submit confirmation. Please try again.";
    }
    $update_stmt->close();
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
    $stmt->close();
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

        .solution-form {
            margin-top: 30px;
        }

        .solution-form label {
            font-size: 18px;
        }

        .submit-btn {
            background-color: #137547;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
        }

        .submit-btn:hover {
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
            if (isset($form)) {
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
                    <p><span class="form-label">Message:</span> ' . nl2br(htmlspecialchars($form['patient_message'])) . '</p>
                </div>';
                
                // Check if the form has already been solved
                if ($form['doctor_confirmation'] === 'Yet to be confirmed!') {
                    // Display the solution form if not already solved
                    echo '
                    <div class="solution-form">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="solution">Submit Your Confirmation:</label>
                                <textarea class="form-control" id="solution" name="solution" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="submit_solution" class="submit-btn">Submit Confirmation</button>
                        </form>
                    </div>';
                } else {
                    echo '<p class="text-center">Confirmation has already been submitted for this form.</p>';
                }
            } else {
                echo '<p class="text-center">Form not found or you do not have permission to view it.</p>';
            }

            if (isset($error_message)) {
                echo '<p class="text-center text-danger">' . $error_message . '</p>';
            }
            ?>
        </div>

        <div class="back-btn">
            <a href="doctor_offline_form_unread.php">Back to Unread Forms</a>
        </div>
    </div>

    <!-- footer -->
    <?php require_once 'include/footer.php'; ?>
</body>
</html>