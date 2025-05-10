<?php
require_once 'config.php';

function getAllProducts() {
    $db = getDbConnection();
    $products = [];
    
    $result = $db->query('SELECT * FROM products ORDER BY id');
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $products[$row['id']] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'imageName' => $row['image_name'],
            'price' => $row['price']
        ];
    }
    
    $db->close();
    return $products;
}

function getProductById($id) {
    $db = getDbConnection();
    
    $stmt = $db->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $product = $result->fetchArray(SQLITE3_ASSOC);
    $db->close();
    
    if ($product) {
        return [
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'],
            'imageName' => $product['image_name'],
            'price' => $product['price']
        ];
    }
    
    return null;
}

function addToCart($productId, $quantity) {
    $db = getDbConnection();
    $sessionId = session_id();
    
    $stmt = $db->prepare('SELECT id, quantity FROM cart_items WHERE session_id = :session_id AND product_id = :product_id');
    $stmt->bindValue(':session_id', $sessionId, SQLITE3_TEXT);
    $stmt->bindValue(':product_id', $productId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $existingItem = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($existingItem) {
        $stmt = $db->prepare('UPDATE cart_items SET quantity = quantity + :quantity WHERE id = :id');
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $existingItem['id'], SQLITE3_INTEGER);
    } else {
        $stmt = $db->prepare('INSERT INTO cart_items (session_id, product_id, quantity) VALUES (:session_id, :product_id, :quantity)');
        $stmt->bindValue(':session_id', $sessionId, SQLITE3_TEXT);
        $stmt->bindValue(':product_id', $productId, SQLITE3_INTEGER);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
    }
    
    $result = $stmt->execute();
    $db->close();
    
    return ($result !== false);
}

function getCartItems() {
    $db = getDbConnection();
    $sessionId = session_id();
    $cartItems = [];
    
    $stmt = $db->prepare('
        SELECT ci.id as cart_id, p.id, p.title, p.price, p.image_name, ci.quantity AS count
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.session_id = :session_id
    ');
    $stmt->bindValue(':session_id', $sessionId, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $cartItems[] = [
            'cart_id' => $row['cart_id'],
            'id' => $row['id'],
            'name' => $row['title'],
            'price' => $row['price'],
            'count' => $row['count'],
            'imageName' => $row['image_name']
        ];
    }
    
    $db->close();
    return $cartItems;
}

function removeCartItem($productId) {
    $db = getDbConnection();
    $sessionId = session_id();
    
    $stmt = $db->prepare('DELETE FROM cart_items WHERE session_id = :session_id AND product_id = :product_id');
    $stmt->bindValue(':session_id', $sessionId, SQLITE3_TEXT);
    $stmt->bindValue(':product_id', $productId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $db->close();
    return ($result !== false);
}

function clearCart() {
    $db = getDbConnection();
    $sessionId = session_id();
    
    $stmt = $db->prepare('DELETE FROM cart_items WHERE session_id = :session_id');
    $stmt->bindValue(':session_id', $sessionId, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    $db->close();
    return ($result !== false);
}
?>