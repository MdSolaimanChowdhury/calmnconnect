<?php
// Check if doctor_id is passed via POST
if (isset($_POST['doctor_id'])) {
    // Include the database configuration file
    require_once "config/db.php";

    // Sanitize the input
    $doctor_id = intval($_POST['doctor_id']);

    // Prepare the SQL query to fetch the doctor's availability
    $sql = "SELECT offline_saturday, offline_sunday, offline_monday, offline_tuesday, offline_wednesday, offline_thursday, offline_friday, location_saturday, location_sunday, location_monday, location_tuesday, location_wednesday, location_thursday, location_friday 
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
            echo "<p><strong>Saturday:</strong> " . htmlspecialchars($row['offline_saturday']) . "</p>";
            echo "<p><strong>Location for Saturday:</strong> " . htmlspecialchars($row['location_saturday']) . "</p>";

            echo "<p><strong>Sunday:</strong> " . htmlspecialchars($row['offline_sunday']) . "</p>";
            echo "<p><strong>Location for Sunday:</strong> " . htmlspecialchars($row['location_sunday']) . "</p>";

            echo "<p><strong>Monday:</strong> " . htmlspecialchars($row['offline_monday']) . "</p>";
            echo "<p><strong>Location for Monday:</strong> " . htmlspecialchars($row['location_monday']) . "</p>";

            echo "<p><strong>Tuesday:</strong> " . htmlspecialchars($row['offline_tuesday']) . "</p>";
            echo "<p><strong>Location for Tuesday:</strong> " . htmlspecialchars($row['location_tuesday']) . "</p>";

            echo "<p><strong>Wednesday:</strong> " . htmlspecialchars($row['offline_wednesday']) . "</p>";
            echo "<p><strong>Location for Wednesday:</strong> " . htmlspecialchars($row['location_wednesday']) . "</p>";

            echo "<p><strong>Thursday:</strong> " . htmlspecialchars($row['offline_thursday']) . "</p>";
            echo "<p><strong>Location for Thursday:</strong> " . htmlspecialchars($row['location_thursday']) . "</p>";

            echo "<p><strong>Friday:</strong> " . htmlspecialchars($row['offline_friday']) . "</p>";
            echo "<p><strong>Location for Friday:</strong> " . htmlspecialchars($row['location_friday']) . "</p>";
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