 <?php
// config.php
$db_host = '127.0.0.1';
$db_name = 'e-commerce';     //le nom de la base de données
$db_user = 'root';          // utilisateur de la base de données
$db_pass = '';              // mot de passe de la base de données

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];


