<?php
try {
    $database = new PDO('sqlite:shop.db');
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $database->exec("CREATE TABLE IF NOT EXISTS customer (
        customer_id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        pass_hash TEXT NOT NULL,
        first_name TEXT,
        last_name TEXT,
        birth_date DATE,
        profile_description TEXT
    )");
    
    $database->exec("CREATE TABLE IF NOT EXISTS item (
        item_id INTEGER PRIMARY KEY AUTOINCREMENT, 
        item_title TEXT NOT NULL, 
        item_picture TEXT NOT NULL,
        item_cost REAL NOT NULL
    )");
    
    $database->exec("CREATE TABLE IF NOT EXISTS customer_item (
        customer_id INTEGER NOT NULL, 
        item_id INTEGER NOT NULL, 
        amount INTEGER NOT NULL DEFAULT 1,
        PRIMARY KEY(customer_id, item_id),
        FOREIGN KEY(customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
        FOREIGN KEY(item_id) REFERENCES item(item_id) ON DELETE CASCADE
    )");
    
    $count = $database->query("SELECT COUNT(*) FROM item")->fetchColumn();
    if ($count == 0) {
        $database->exec("INSERT INTO item (item_title, item_picture, item_cost) VALUES 
            ('Напій Coca-cola', './images/cola.jpg', 12),
            ('Напій Fanta', './images/fanta.jpg', 9),
            ('Напій Sprite', './images/sprite.png', 25),
            ('Мінеральна вода', './images/water.jpg', 19),
            ('Арахіс', './images/nuts.jpg', 2)");
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>