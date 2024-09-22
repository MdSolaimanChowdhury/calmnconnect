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
    <title>Anonymous Appointment Form</title>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header_patient.php' ?>

    <div class="container">
        <?php
        session_start();

        // Enable error reporting for development
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Check if the patient is logged in
        if (!isset($_SESSION['patient_logged_in']) || $_SESSION['patient_logged_in'] !== true) {
            $_SESSION['login_message'] = "You need to log in first as a patient.";
            header("Location: login.php");
            exit;
        }

        // Get the logged-in patient's username and ID
        $patient_username = $_SESSION['patient_username'];
        $patient_id = $_SESSION['patient_id']; // Assuming patient_id is stored in session after login

        if (isset($_POST["submit"])) {
            // Sanitize and validate inputs
            $patient_gender = isset($_POST["patient_gender"]) ? htmlspecialchars($_POST["patient_gender"]) : '';
            $patient_age = isset($_POST["patient_age"]) ? htmlspecialchars($_POST["patient_age"]) : '';
            $patient_marry = isset($_POST["patient_marry"]) ? htmlspecialchars($_POST["patient_marry"]) : '';
            $patient_message = isset($_POST["patient_message"]) ? htmlspecialchars($_POST["patient_message"]) : '';
            $doctor_id = isset($_POST["doctor_id"]) ? htmlspecialchars($_POST["doctor_id"]) : '';

            // Database connection
            require_once "config/db.php";

            // Insert the patient data into the Anonymous_Form table
            if (!empty($patient_id)) {
                $sql = "INSERT INTO Anonymous_Form (patient_id, patient_gender, patient_age, patient_marry, patient_message, doctor_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "issssi", $patient_id, $patient_gender, $patient_age, $patient_marry, $patient_message, $doctor_id);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success text-center'>Form submitted successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger text-center'>Something went wrong with form submission!</div>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<div class='alert alert-danger text-center'>Failed to prepare form submission query.</div>";
                }

                // Update the patient table with the new values
                $update_sql = "
                    UPDATE patient 
                    SET 
                        patient_gender = ?, 
                        patient_age = ?, 
                        patient_marry = ? 
                    WHERE 
                        patient_id = ?
                ";

                // Prepare the update statement
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "sssi", $patient_gender, $patient_age, $patient_marry, $patient_id);

                    // Execute the update statement
                    if (mysqli_stmt_execute($update_stmt)) {
                        //echo "<div class='alert alert-success text-center'>Patient information updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger text-center'>Failed to update patient information.</div>";
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    echo "<div class='alert alert-danger text-center'>Failed to prepare patient update query.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Patient ID is missing.</div>";
            }

            mysqli_close($conn);
        }
        ?>

        <div class="row justify-content-between">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>Anonymous Appointment Form</strong>
                    </div>
                    <div class="panel-body">
                        <form action="anonymous_form.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="patient_username" class="form-control" readonly value="<?php echo htmlspecialchars($patient_username); ?>">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="patient_gender" class="form-control" required="">
                                    <option value="">Select Your Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <select name="patient_age" class="form-control" required="">
                                    <option value="">Select Your Age</option>
                                    <?php
                                    for ($i = 10; $i <= 110; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Marital Status</label>
                                <select name="patient_marry" class="form-control" required="">
                                    <option value="">Select Marital Status</option>
                                    <option value="Married">Married</option>
                                    <option value="Unmarried">Unmarried</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Describe Your Problem</label>
                                <textarea name="patient_message" class="form-control" rows="8" placeholder="Describe your problem here..." required=""></textarea>
                            </div>
                            <div class="form-group">
                                <label>Preferred Doctor</label>
                                <select name="doctor_id" class="form-control" required="">
                                    <option value="">Select Your Doctor</option>
                                    <?php
                                    // Database connection
                                    require_once "config/db.php";

                                    // Fetch doctors from the database
                                    $sql = "SELECT doctor_id, doctor_name FROM doctor";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . htmlspecialchars($row['doctor_id']) . "'>" . htmlspecialchars($row['doctor_name']) . "</option>";
                                        }
                                    }

                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>

                            <input type="submit" name="submit" value="Submit" class="btn success-btn" style="width: 100%;">
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