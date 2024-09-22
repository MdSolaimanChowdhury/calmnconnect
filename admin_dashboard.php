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
    <title>ADMIN DASHBOARD</title>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header_admin.php' ?>

    <?php
    // Database connection
    require_once 'config/db.php';

    session_start();

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: ccadmin.php");
        exit;
    }
    ?>

    <div class="container">
        <div class="col-md-3">
            <h1>Admin Sidebar</h1>
        </div>
        <div class="col-md-9">
            <h1>ADMIN DASHBOARD</h1>
        </div>
    </div>

    <!-- footer -->
     <?php require_once 'include/footer.php' ?>
</body>
</html>