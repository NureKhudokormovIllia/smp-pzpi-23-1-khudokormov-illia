<?php

function modifyCartItem($identifier, $amount)
{
    global $database;
    global $customer_id;
    $query = $database->prepare('UPDATE customer_item SET amount = :amount WHERE customer_id = :customer_id AND item_id = :item_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':item_id', $identifier);
    $query->bindParam(':amount', $amount);
    $query->execute();
}

function fetchAllItems()
{
    global $database;
    $query = $database->query('SELECT * FROM item');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function retrieveCartItems()
{
    global $database;
    global $customer_id;
    $query = $database->prepare('
    SELECT 
        ci.item_id, 
        i.item_title, 
        i.item_cost, 
        ci.amount, 
        i.item_cost * ci.amount AS total_cost 
    FROM customer_item ci 
    LEFT JOIN item i
    ON i.item_id = ci.item_id
    WHERE ci.customer_id = :customer_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function calculateTotalAmount(){
    global $database;
    global $customer_id;
    $query = $database->prepare('SELECT SUM(i.item_cost * ci.amount) AS total_sum 
        FROM customer_item ci 
        LEFT JOIN item i
        ON ci.item_id = i.item_id
        WHERE ci.customer_id = :customer_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
    return $query->fetch(PDO::FETCH_COLUMN);
}

function addItemToCart($identifier, $amount)
{
    global $database;
    global $customer_id;
    $query = $database->prepare('INSERT INTO customer_item (customer_id, item_id, amount) VALUES (:customer_id, :item_id, :amount)');
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':item_id', $identifier);
    $query->bindParam(':amount', $amount);
    $query->execute();
}

function removeFromCart($identifier)
{
    global $database;
    global $customer_id;
    $query = $database->prepare('DELETE FROM customer_item WHERE customer_id = :customer_id AND item_id = :item_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':item_id', $identifier);
    $query->execute();
}

function emptyCart($customer_id) {
    global $database;
    $query = $database->prepare('DELETE FROM customer_item WHERE customer_id = :customer_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
}

function checkItemInCart($identifier)
{
    global $database;
    global $customer_id;
    $query = $database->prepare('SELECT EXISTS(SELECT 1 FROM customer_item WHERE customer_id = :customer_id AND item_id = :item_id) AS found');
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':item_id', $identifier);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}