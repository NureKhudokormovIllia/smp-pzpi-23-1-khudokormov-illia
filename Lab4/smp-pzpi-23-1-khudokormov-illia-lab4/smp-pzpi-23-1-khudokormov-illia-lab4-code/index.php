<?php

$sessionPath = __DIR__ . '/sessions';
$uploadsPath = __DIR__ . '/uploads';
if (!file_exists($sessionPath)) mkdir($sessionPath, 0700, true);
if (!file_exists($uploadsPath)) mkdir($uploadsPath, 0755, true);

ini_set('session.save_path', $sessionPath);

require_once('database.php'); 
require_once('item_database_operations.php');
require_once('customer_database_operations.php');

session_start();

$customer_id = $_SESSION['customer_id'] ?? null;

switch (true) {
    case isset($_GET["item_bought"]):
        require_once('header.php');
        if (!$customer_id) {
            require_once('page404.php');
            break;
        }
        if (isset($_POST['amount'])) {
            foreach ($_POST['amount'] as $identifier => $amount) {
                if ($amount !== "") {
                    $amount = (int)$amount;
                    if ($amount > 0) {
                        checkItemInCart($identifier)[0]['found'] == 1
                            ? modifyCartItem($identifier, $amount)
                            : addItemToCart($identifier, $amount);
                    } elseif ($amount == 0 && checkItemInCart($identifier)) {
                        removeFromCart($identifier);
                    }
                }
            }
        }
        require_once('cart.php');
        break;

    case isset($_GET["item_removed"]):
        require_once('header.php');
        if (!$customer_id) {
            require_once('page404.php');
            break;
        }
        removeFromCart($_POST["identifier"]);
        require_once('cart.php');
        break;

    case isset($_GET["signin"]):
        if ($customer_id) {
            session_unset();
            session_destroy();
            require_once('header.php');
            require_once('login.php');
        } elseif ($_POST['customerName'] ?? false && $_POST['password'] ?? false) {
            $username = $_POST['customerName'];
            $pass_hash = $_POST['password'];

            if (strlen($username) <= 1 || strlen($pass_hash) <= 1) {
                $dataSuccess = false;
                require_once('header.php');
                require_once('login.php');
                break;
            }

            if (verifyCustomerExists($username)['found'] == 1) {
                if (authenticateCustomer($username, $pass_hash)) {
                    require_once('header.php');
                    require_once('products.php');
                } else {
                    $passwordSuccess = false;
                    require_once('header.php');
                    require_once('login.php');
                }
            } else {
                createAccount($username, $pass_hash);
                require_once('header.php');
                require_once('products.php');
            }
        } else {
            require_once('header.php');
            require_once('login.php');
        }
        break;

    case isset($_GET["account"]):
        require_once('header.php');
        if (!$customer_id) {
            require_once('page404.php');
            break;
        }
        if (isset($_FILES['profilefile']) && $_FILES['profilefile']['name'] != "") {
            $extension = strtolower(pathinfo($_FILES['profilefile']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
            
            if (!in_array($extension, $allowedExtensions)) {
                $uploadError = "Invalid file type. Only PNG, JPG, JPEG, GIF are allowed.";
            } else {
                $targetFile = $uploadsPath . DIRECTORY_SEPARATOR . $customer_id . '.' . $extension;
                $existingFiles = glob($uploadsPath . DIRECTORY_SEPARATOR . $customer_id . '.*');
                
                if (!empty($existingFiles)) {
                    foreach ($existingFiles as $file) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
                
                if (move_uploaded_file($_FILES['profilefile']['tmp_name'], $targetFile)) {
                    $uploadSuccess = true;
                } else {
                    $uploadError = "Failed to upload file. Please try again.";
                }
            }
        } else if (isset($_POST['firstName'])) {
            $changeSuccess = updateCustomerProfile(
                $_POST['firstName'],
                $_POST['lastName'],
                $_POST['birthDate'],
                $_POST['description']
            );
        }
        require_once('profile.php');
        break;

    case isset($_GET["cart"]):
        require_once('header.php');
        if (!$customer_id) {
            require_once('page404.php');
            break;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
            emptyCart($customer_id);
            header('Location: index.php?cart');
            exit;
        }
        require_once('cart.php');
        break;

    default:
        require_once('header.php');
        if ($customer_id) {
            require_once('products.php');
        } else {
            require_once('page404.php');
        }
}

require_once('footer.php');
exit;
?>