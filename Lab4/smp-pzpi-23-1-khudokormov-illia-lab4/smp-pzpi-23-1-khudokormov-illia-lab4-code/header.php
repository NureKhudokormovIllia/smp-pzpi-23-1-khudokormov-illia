<style>
.header-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 0;
    height: 40px;
    background-color: #f8f8f8;
    font-family: Arial, sans-serif;
    border-bottom: 1px solid black;
}
.header-nav {
    display: flex;
    align-items: center;
    gap: 8px;
}
.header-icon {
    width: 30px;
    height: 30px;
}
.header-link {
    text-decoration: none;
    color: black;
}
.header-separator {
    color: #000;
    padding: 0 15px;
}
</style>
<header class="header-container">
    <nav class="header-nav">
        <img src="images/home_icon.png" class="header-icon">
        <a href="index.php" class="header-link">Home</a>
    </nav>
    <span class="header-separator">|</span>

    <nav class="header-nav">
        <img src="images/menu_icon.png" class="header-icon">
        <a href="index.php" class="header-link">Products</a>
    </nav>
    <span class="header-separator">|</span>

    <?php if (isset($_SESSION['customer_id'])): ?>
    <nav class="header-nav">
        <img src="images/shop_cart_icon.png" class="header-icon">
        <a href="index.php?item_bought" class="header-link">Cart</a>
    </nav>
    <span class="header-separator">|</span>

    <nav class="header-nav">
        <img src="images/profile_icon.png" class="header-icon">
        <a href="index.php?account" class="header-link">Profile</a>
    </nav>
    <span class="header-separator">|</span>
    <?php endif; ?>

    <nav class="header-nav">
        <img src="<?php echo isset($_SESSION['customer_id']) ? 'images/logout.png' : 'images/login.png' ?>" class="header-icon">
        <a href="index.php?signin" class="header-link">
            <?php echo isset($_SESSION['customer_id']) ? 'Logout' : 'Login' ?>
        </a>
    </nav>
</header>