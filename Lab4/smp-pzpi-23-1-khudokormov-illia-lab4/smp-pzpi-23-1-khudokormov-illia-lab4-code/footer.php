<style>
.footer-container {
    border-top: 1px solid black;
}
.footer-content {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 0;
    background-color: #f8f8f8;
    gap: 20px;
    height: 40px;
    font-family: Arial, sans-serif;
}
.footer-link {
    text-decoration: none;
    color: black;
    padding: 0 5px;
}
.footer-separator {
    color: #ccc;
}
</style>
<div class="footer-container">
    <footer class="footer-content">
        <nav>
            <a href="index.php" class="footer-link">Home</a>
        </nav>
        <span class="footer-separator">|</span>
        <nav>
            <a href="index.php" class="footer-link">Products</a>
        </nav>
        <span class="footer-separator">|</span>
        <nav>
            <a href="index.php?item_bought=true" class="footer-link">Cart</a>
        </nav>
        <span class="footer-separator">|</span>
        <nav>
            <a href="#" class="footer-link">About us</a>
        </nav>
    </footer>
</div>