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
        try {
            $stmt =$this->pdo->prepare("SELECT * FROM login WHERE nick_name = ?");
            $stmt->execute([$data['nick_name']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

             // Si no se encuentra el usuario
            if (!$user) {
                return "Usuario no encontrado";
            }
            
            // Verificar si la contraseña coincide
            if (password_verify($data['password'],$user['password'])) {
                return "Usuario inició sesión correctamente";
            } else {
                return "Contraseña incorrecta"; 
            }

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
