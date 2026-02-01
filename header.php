<header>
    <nav class="navbar">
        <a href="home.php" class="logo">Velvet Vogue</a>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="mens.php">Men</a></li>
            <li><a href="womens.php">Women</a></li>
            <li><a href="kids.php">Kids</a></li>
            <li><a href="cart.php">Cart</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>

        </ul>
    </nav>
</header>