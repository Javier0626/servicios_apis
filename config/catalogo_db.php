<?php
// config/db.php
function getConnection() {
    $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=catalogo_db;charset=utf8';
    $username = 'root';
    $password = '';

    try {
        $pdocatalogo = new PDO($dsn, $username, $password);
        $pdocatalogo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdocatalogo;
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}
?>
