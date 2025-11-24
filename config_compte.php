<?php
$host = 'localhost';
$db   = 'e-commerce';    // غيّر الاسم إذا قاعدة بياناتك اسمها مختلف
$user = 'root';            // غالبا root في XAMPP
$pass = '';                // غالبا فاضي في XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
