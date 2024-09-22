<?php
// Check if doctor_id is passed via POST
if (isset($_POST['doctor_id'])) {
    // Include the database configuration file
    require_once "config/db.php";

    // Sanitize the input
    $doctor_id = intval($_POST['doctor_id']);

    // Prepare the SQL query to fetch the doctor's availability
    $sql = "SELECT available_sunday, available_monday, available_tuesday, available_wednesday, available_thursday, available_friday, available_saturday 
            FROM doctor 
            WHERE doctor_id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        // Bind the doctor_id parameter
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Get the result set
        $result = mysqli_stmt_get_result($stmt);

        // Check if a record was found
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Output the availability in a formatted box
            echo "<div class='box'>";
            echo "<p><strong>  Sunday:</strong> " . htmlspecialchars($row['available_sunday']) . "</p>";
            echo "<p><strong>  Monday:</strong> " . htmlspecialchars($row['available_monday']) . "</p>";
            echo "<p><strong>  Tuesday:</strong> " . htmlspecialchars($row['available_tuesday']) . "</p>";
            echo "<p><strong>  Wednesday:</strong> " . htmlspecialchars($row['available_wednesday']) . "</p>";
            echo "<p><strong>  Thursday:</strong> " . htmlspecialchars($row['available_thursday']) . "</p>";
            echo "<p><strong>  Friday:</strong> " . htmlspecialchars($row['available_friday']) . "</p>";
            echo "<p><strong>  Saturday:</strong> " . htmlspecialchars($row['available_saturday']) . "</p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-info'>No availability found for the selected doctor.</div>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Failed to prepare the SQL statement.</div>";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "<div class='alert alert-danger'>Doctor ID is missing.</div>";
}
?>