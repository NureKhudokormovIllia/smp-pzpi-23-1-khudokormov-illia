<?php
require_once('item_database_operations.php');
$cartItems = retrieveCartItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shop - Cart</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }
        .cart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            flex-direction: column;
        }
        .cart-table {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .cart-table th {
            border: 1px solid black;
            padding: 3px;
        }
        .button-container {
            display: flex;
            flex-direction: row;
            margin-left: 150px;
        }
        .cart-button {
            margin: 10px;
        }
        .cart-link {
            text-decoration: none;
            color: black;
        }
        .empty-cart-message {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }
    </style>
</head>
<body>
    <?php if (count($cartItems) > 0) { ?>
    <main class="cart-container">
        <form method="POST" action="index.php?item_removed">
            <table class="cart-table">
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>price</th>
                    <th>count</th>
                    <th>sum</th>
                    <th></th>
                </tr>
                <?php foreach ($cartItems as $item) { ?>
                <tr>
                    <th><?php echo $item['item_id'] ?></th>
                    <th><?php echo $item['item_title'] ?></th>
                    <th><?php echo $item['item_cost'] ?></th>
                    <th><?php echo $item['amount'] ?></th>
                    <th><?php echo $item['total_cost'] ?></th>
                    <th>
                        <button type="submit" name="identifier" value="<?php echo $item['item_id'] ?>">Delete</button>
                    </th>
                </tr>
                <?php } ?>
                <tr>
                    <th>Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?php echo calculateTotalAmount() ?></th>
                    <th></th>
                </tr>
            </table>
        </form>
        <form method="POST" action="index.php?cart" class="button-container">
            <button class="cart-button"><a class="cart-link" href="products.html">Cancel</a></button>
            <button class="cart-button" type="submit" name="purchase">Pay</button>
        </form>
    </main>
    <?php } else { ?>
    <main class="empty-cart-message">
        <a class="cart-link" href="products.html">Перейти до покупок</a>
    </main>
    <?php } ?>
</body>
</html>