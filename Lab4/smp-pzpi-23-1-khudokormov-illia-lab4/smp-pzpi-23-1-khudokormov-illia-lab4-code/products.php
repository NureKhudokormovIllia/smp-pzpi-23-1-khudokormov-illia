<?php
require_once('item_database_operations.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shop - Products</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }
        
        main {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
        }
        
        .products-form {
            display: flex;
            flex-direction: column;
        }
        
        .products-table {
            width: auto;
            margin: 0 auto;
        }
        
        .products-table th {
            padding: 10px 20px;
            text-align: center;
        }
        
        .product-image {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100px;
            max-height: 100px;
        }
        
        .quantity-input {
            width: 150px;
            text-align: center;
        }
        
        .submit-button {
            margin-left: auto;
            margin-top: 20px;
            height: 30px;
        }
    </style>
</head>

<body>
    <main>
        <form method="POST" action="index.php?item_bought" class="products-form">
            <table class="products-table">
                <?php foreach (fetchAllItems() as $merchandise) { ?>
                <tr>
                    <th>
                        <img src="<?php echo $merchandise['item_picture'] ?>" class="product-image">
                    </th>
                    <th><?php echo $merchandise['item_title'] ?></th>
                    <th>
                        <input type="number" min="0" name="amount[<?php echo $merchandise['item_id'] ?>]" class="quantity-input">
                    </th>
                    <th>$<?php echo $merchandise['item_cost'] ?></th>
                </tr>
                <?php } ?>
            </table>
            <input type="submit" class="submit-button" value="Покласти у кошик">
        </form>
    </main>
</body>
</html>