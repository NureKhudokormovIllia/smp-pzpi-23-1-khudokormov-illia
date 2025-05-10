<?php
session_start();

require_once 'functions.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'remove' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        removeCartItem($id);
        header('Location: cart.php');
        exit;
    } elseif ($_GET['action'] === 'clear') {
        clearCart();
        header('Location: cart.php');
        exit;
    }
}

$cartItems = getCartItems();

include 'tpl/header.phtml';
?>
<div>
    <div class="container">
        <div class="row">
            <div class="column">
                <?php if (empty($cartItems)): ?>
                    <div class="empty-cart">
                        <p>Your cart is empty</p>
                        <p><a href="index.php">Go to buy menu</a></p>
                    </div>
                <?php else: ?>
                    <table class="u-full-width">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="text-align: center;">Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php 
                                $totalSum = 0;
                                foreach ($cartItems as $item): 
                                    $itemSum = $item['price'] * $item['count'];
                                    $totalSum += $itemSum;
                                ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><div class="container-image">
                                        <img class="item-image" src="<?= htmlspecialchars($item['imageName']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div></td>
                                    <td><?= $item['name'] ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td><?= $item['count'] ?></td>
                                    <td>$<?= number_format($itemSum, 2) ?></td>
                                    <td>
                                        <a href="cart.php?action=remove&id=<?= $item['id'] ?>" class="button button-remove">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right;"><strong>Total price:</strong></td>
                                <td colspan="3"><strong>$<?= number_format($totalSum, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="cart-actions">
                        <a href="index.php" class="button">Continue buying</a>
                        <a href="cart.php?action=clear" class="button button-remove">Clear cart</a>
                        <a href="cart.php?action=clear" class="button button-approve">Buy items</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php include 'tpl/footer.phtml'; ?>