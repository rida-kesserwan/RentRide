<?php

function isLoggedIn() {
    return isset($_SESSION['username']);
}

?>

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Rent<span>Ride</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto" style="width:70%;">
                <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="pricing.php" class="nav-link">Pricing</a></li>
                <li class="nav-item"><a href="car.php" class="nav-link">Cars</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <?php if(isLoggedIn()): ?>
                    <li class="nav-item" style="width:100%;display:flex"><span class="nav-link user-n">Hi, <?php echo $_SESSION['username']; ?></span></li>
					<li class="nav-item" style="width:100%;display:flex"><a href="myrental.php" class="nav-link">My Rentals</a></li>
                    <li class="nav-item nav-btn-m" style="margin:0px;width:80%;"><a href="PHP/logout.php" class="nav-btn">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item nav-btn-m"><a href="login.php" class="nav-btn">Login</a></li>
                    <li class="nav-item nav-btn-m"><a href="signup.php" class="nav-btn">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>