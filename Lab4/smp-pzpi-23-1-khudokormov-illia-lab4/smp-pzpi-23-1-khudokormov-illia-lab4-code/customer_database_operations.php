<?php

function createAccount($username, $pass_hash)
{
    global $database;
    $query = $database->prepare('INSERT INTO customer (username, pass_hash) VALUES (:username, :pass_hash)');
    $query->bindParam(':username', $username);
    $query->bindParam(':pass_hash', $pass_hash);
    $query->execute();
    $customer_id = $database->lastInsertId();
    $_SESSION['customer_id'] = $customer_id;
}

function verifyCustomerExists($username)
{
    global $database;
    $query = $database->prepare('SELECT EXISTS(SELECT 1 FROM customer WHERE username = :username) AS found');
    $query->bindParam(':username', $username);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function authenticateCustomer($username, $pass_hash)
{
    global $database;
    $query = $database->prepare('SELECT customer_id FROM customer WHERE username = :username AND pass_hash = :pass_hash');
    $query->bindParam(':username', $username);
    $query->bindParam(':pass_hash', $pass_hash);
    $query->execute();
    $data = $query->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        $customer_id = $data['customer_id'];
        $_SESSION['customer_id'] = $customer_id;
    }
    return $data;
}

function updateCustomerProfile($firstName, $lastName, $birthDate, $description)
{
    $currentDate = new DateTime();
    $userDate = new DateTime($birthDate);
    $ageDifference = $currentDate->diff($userDate);
    if (strlen($firstName) <= 1 || strlen($lastName) <= 1 || $ageDifference->y < 18 || strlen($description) < 50) {
        return false;
    }
    global $database;
    global $customer_id;
    $query = $database->prepare('UPDATE customer SET first_name = :first_name, last_name = :last_name, birth_date = :birth_date, profile_description = :profile_description WHERE customer_id = :customer_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->bindParam(':first_name', $firstName);
    $query->bindParam(':last_name', $lastName);
    $query->bindParam(':birth_date', $birthDate);
    $query->bindParam(':profile_description', $description);
    $query->execute();
    return true;
}

function fetchCustomerProfile(){
    global $database;
    global $customer_id;
    $query = $database->prepare('SELECT first_name, last_name, birth_date, profile_description FROM customer WHERE customer_id = :customer_id');
    $query->bindParam(':customer_id', $customer_id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}