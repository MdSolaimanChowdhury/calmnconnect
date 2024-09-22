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
    <title>Calm and Connect</title>
</head>
<body>
    <!-- header -->
    <?php require_once 'include/header.php' ?>

    <!-- banner -->
    <div class="container" style="width: 100%;">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">

            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="assets/img/homebgone.png" alt="beautiful_blue_background">
                    <div class="carousel-caption">
                        <h3 style="font-size: 45px;">Find Peace, Anonymously</h3>
                        <p style="font-size: 20px;">You can achieve mental well-being and tranquility without revealing your identity, ensuring complete privacy and confidentiality here.</p>
                    </div>
                </div>

                <div class="item">
                    <img src="assets/img/homebgtwo.png" alt="before and after situation of any mental illness">
                    <div class="carousel-caption">
                        <h3 style="font-size: 45px;">We don't need your Identity</h3>
                        <p style="font-size: 20px;">Please, Fill up the Appointment Form Freely.</p>
                    </div>
                </div>

                <div class="item">
                    <img src="assets/img/homebgthree.png" alt="Mental Health Logo">
                    <div class="carousel-caption">
                        <h3 style="font-size: 45px;">Mental Health is Immensely Important</h3>
                        <p style="font-size: 20px;">Don't Hesitate. Contact Us</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
     <?php require_once 'include/footer.php' ?>
</body>
</html>