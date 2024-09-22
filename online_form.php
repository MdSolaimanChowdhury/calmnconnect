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
    <title>Online Appointment Form</title>

    <script>
        // jQuery to handle doctor selection and fetch availability
        $(document).ready(function(){
            $('select[name="doctor_id"]').on('change', function(){
                var doctor_id = $(this).val();
                if(doctor_id) {
                    $.ajax({
                        url: "fetch_doctor_availability.php",
                        method: "POST",
                        data: {doctor_id: doctor_id},
                        success: function(data) {
                            $('#doctor_availability').html(data);  // Update the availability box
                        }
                    });
                } else {
                    $('#doctor_availability').html('');  // Clear availability box if no doctor selected
                }
            });
        });
    </script>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header_patient.php'; ?>

    <div class="container">
        <?php
        session_start();

        // Database connection
        require_once "config/db.php";

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

        // Fetch patient data to pre-fill the form if available
        $patient_gender = $patient_age = $patient_marry = $patient_name = $patient_mobile = "";

        $sql = "SELECT patient_gender, patient_age, patient_marry FROM patient WHERE patient_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {  //line 62
            mysqli_stmt_bind_param($stmt, "i", $patient_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $patient_gender, $patient_age, $patient_marry);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }

        if (isset($_POST["submit"])) {
            // Sanitize and validate inputs
            $patient_gender = isset($_POST["patient_gender"]) ? htmlspecialchars($_POST["patient_gender"]) : '';
            $patient_age = isset($_POST["patient_age"]) ? htmlspecialchars($_POST["patient_age"]) : '';
            $patient_marry = isset($_POST["patient_marry"]) ? htmlspecialchars($_POST["patient_marry"]) : '';
            $patient_message = isset($_POST["patient_message"]) ? htmlspecialchars($_POST["patient_message"]) : '';
            $doctor_id = isset($_POST["doctor_id"]) ? htmlspecialchars($_POST["doctor_id"]) : '';

            // Insert the patient data into the Anonymous_Form table
            if (!empty($patient_id)) {
                $sql = "INSERT INTO online_Form (patient_id, patient_gender, patient_age, patient_marry, patient_message, doctor_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "issssi", $patient_id, $patient_gender, $patient_age, $patient_marry, $patient_message, $doctor_id);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success text-center'>Form submitted successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger text-center'>Something went wrong with form submission!</div>";
                    }
                    mysqli_stmt_close($stmt);
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

                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "sssi", $patient_gender, $patient_age, $patient_marry, $patient_id);
                    if (!mysqli_stmt_execute($update_stmt)) {
                        echo "<div class='alert alert-danger text-center'>Failed to update patient information.</div>";
                    }
                    mysqli_stmt_close($update_stmt);
                }
            }

            mysqli_close($conn);
        }
        ?>

        <div class="row justify-content-between">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                        <strong>Online Appointment Form</strong>
                    </div>
                    <div class="panel-body">
                        <form action="online_form.php" method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="patient_username" class="form-control" readonly value="<?php echo htmlspecialchars($patient_username); ?>">
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <select name="patient_gender" class="form-control" required="">
                                    <option value="">Select Your Gender</option>
                                    <option value="Male" <?php echo ($patient_gender == "Male") ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($patient_gender == "Female") ? 'selected' : ''; ?>>Female</option>
                                    <option value="Others" <?php echo ($patient_gender == "Others") ? 'selected' : ''; ?>>Others</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Age</label>
                                <select name="patient_age" class="form-control" required="">
                                    <option value="">Select Your Age</option>
                                    <?php
                                        for ($i = 10; $i <= 110; $i++) {
                                            echo "<option value='$i'" . (($i == $patient_age) ? ' selected' : '') . ">$i</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Marital Status</label>
                                <select name="patient_marry" class="form-control" required="">
                                    <option value="">Select Marital Status</option>
                                    <option value="Married" <?php echo ($patient_marry == "Married") ? 'selected' : ''; ?>>Married</option>
                                    <option value="Unmarried" <?php echo ($patient_marry == "Unmarried") ? 'selected' : ''; ?>>Unmarried</option>
                                </select>
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

                            <!-- Doctor Availability Box -->
                            <div id="doctor_availability" class="form-group">
                                <!-- Availability will be loaded dynamically here -->
                            </div>

                            <div class="form-group">
                                <label>Provide your preferred time based on the psychologist's availability.</label>
                                <textarea name="patient_message" class="form-control" rows="3" placeholder="Mention your date and time clearly. It's crucial. (e.g. 12th September, Friday at 5:30 PM)" required=""></textarea>
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