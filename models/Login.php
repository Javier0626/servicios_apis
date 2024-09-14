<?php
// models/Login.php
class Login
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Inicio de sesion deusuario
    public function login($data)
    {
        $password_hashed = password_hash($data['password'], PASSWORD_BCRYPT);
        
        try {
            $stmt = $this->pdo->prepare("INSERT INTO login (nick_name, password, estado_id, rol_id, usuario_id, created_ad, updated_at)
            VALUES (:nick_name, :password, :estado_id, :rol_id, :usuario_id, NOW(), NOW() )");

            // Bind parameters
            $stmt->bindParam(':nick_name', $data['nick_name']);
            $stmt->bindParam(':password', $password_hashed);
            $stmt->bindParam(':estado_id', $data['estado_id']);
            $stmt->bindParam(':rol_id', $data['rol_id']);
            $stmt->bindParam(':usuario_id', $data['usuario_id']);

            $stmt->execute();
            return "Usuario inició sesion correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
