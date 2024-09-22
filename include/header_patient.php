<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid" style="padding-left: 25px;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Calm and Connect</a>
                <ul class="menubar">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                </ul>
            </div>

            <?php
            // Ensure a session is started
            session_start();
            $patient_username = $_SESSION['patient_username'];
            ?>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <?php echo htmlspecialchars($patient_username); ?> <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="patient_dashboard.php">Dashboard</a></li>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>