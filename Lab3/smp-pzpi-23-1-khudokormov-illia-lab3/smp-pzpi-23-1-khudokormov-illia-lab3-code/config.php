<?php
define('DB_FILE', __DIR__ . '/data/shop.db');

function getDbConnection() {
    try {
        $db = new SQLite3(DB_FILE);
        $db->exec('PRAGMA foreign_keys = ON;');
        return $db;
    } catch (Exception $e) {
        die('Error connecting to database: ' . $e->getMessage());
    }
}

function initDatabase() {
    $db = getDbConnection();
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            image_name TEXT,
            price REAL NOT NULL
        )
    ');
    
    $result = $db->query('SELECT COUNT(*) as count FROM products');
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($row['count'] == 0) {
        $products = [
            [
                'title' => 'Cola',
                'description' => 'Sweetened, carbonated beverage',
                'image_name' => 'images/cola.jpg',
                'price' => 1.99
            ],
            [
                'title' => 'Fanta',
                'description' => 'Orange flavored soft drink',
                'image_name' => 'images/fanta.jpg',
                'price' => 1.89
            ],
            [
                'title' => 'Sprite',
                'description' => 'Lemon-lime flavored soft drink',
                'image_name' => 'images/sprite.png',
                'price' => 1.79
            ],
            [
                'title' => 'Water',
                'description' => 'Mineral water',
                'image_name' => 'images/water.jpg',
                'price' => 0.99
            ],
            [
                'title' => 'Nuts',
                'description' => 'Mixed nuts package',
                'image_name' => 'images/nuts.jpg',
                'price' => 2.99
            ]
        ];
        
        $stmt = $db->prepare('
            INSERT INTO products (title, description, image_name, price) 
            VALUES (:title, :description, :image_name, :price)
        ');
        
        foreach ($products as $product) {
            $stmt->bindValue(':title', $product['title'], SQLITE3_TEXT);
            $stmt->bindValue(':description', $product['description'], SQLITE3_TEXT);
            $stmt->bindValue(':image_name', $product['image_name'], SQLITE3_TEXT);
            $stmt->bindValue(':price', $product['price'], SQLITE3_FLOAT);
            $stmt->execute();
            $stmt->reset();
        }
    }
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS cart_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id TEXT NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL DEFAULT 1,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ');
    
    $db->close();
}

initDatabase();
?>