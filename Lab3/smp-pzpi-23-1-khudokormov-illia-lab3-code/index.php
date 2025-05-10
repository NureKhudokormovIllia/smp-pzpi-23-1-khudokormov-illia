<?php
session_start();

require_once 'functions.php';

$products = getAllProducts();

$errorMessage = '';

if (isset($_POST['submit'])) {
    $hasErrors = false;
    $itemsAdded = false;
    
    foreach ($products as $id => $product) {
        $productKey = 'product_' . $id;
        if (isset($_POST[$productKey]) && is_numeric($_POST[$productKey]) && $_POST[$productKey] > 0) {
            $quantity = (int)$_POST[$productKey];
            $itemsAdded = true;
            addToCart($id, $quantity);
        }
    }
    
    if (!$itemsAdded) {
        $errorMessage = 'Please, add at least one item to cart.';
    } else {
        header('Location: cart.php');
        exit;
    }
}

include 'tpl/header.phtml';
?>
<div class="container">
    <div class="row">
        <div class="column">
            <div class="centered-form-container">
                <?php if (!empty($errorMessage)): ?>
                <div class="error-message">
                    <?= $errorMessage ?>
                </div>
                <?php endif; ?>
                <form method="POST" action="index.php">
                    <table class="u-full-width">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $id => $product): ?>
                            <tr>
                                <td>
                                    <div class="container-image">
                                        <img class="item-image" src="<?= $product['imageName'] ?>">
                                    </div>
                                </td>
                                <td><strong><?= $product['title'] ?></strong></td>
                                <td><?= $product['description'] ?></td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                                <td>
                                    <input type="number" name="product_<?= $id ?>" min="0" value="0" class="count-input">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <input type="submit" name="submit" value="Add to cart" class="button-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'tpl/footer.phtml'; ?>