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
            $stmt = $this->pdo->prepare("SELECT * FROM login WHERE nick_name = ? LIMIT 1");
            $stmt->execute([$data['nick_name']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si no se encuentra el usuario
            if (!$user) {
                return "Usuario no encontrado";
            }

            // genera un token 
            $token = bin2hex(random_bytes(32));

            // Verificar si la contraseña coincide
            if (password_verify($data['password'], $user['password'])) {
                // Actualiza el estado de la sesión a "activo"
                $updateStmt = $this->pdo->prepare("UPDATE login SET status = 1 WHERE nick_name = ?");
                $updateStmt->execute([$user['nick_name']]);

                // Actualiza el token en la base de datos
                $updateStmt = $this->pdo->prepare("UPDATE login SET token = ? WHERE nick_name = ?");
                $updateStmt->execute([$token, $user['nick_name']]);

                return "Usuario inició sesión correctamente";
            } else {
                return "Contraseña incorrecta";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Función para cerrar la sesión del usuario
    public function logout($usuario_id)
    {
        try {
            // Verificar si el usuario existe en la tabla login
            $stmt = $this->pdo->prepare("SELECT * FROM login WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario no se encuentra
            if (!$user) {
                return "Usuario no encontrado o sesión no válida";
            }

            // Eliminar la sesión del usuario
            $updateStmt = $this->pdo->prepare("UPDATE login SET status = 0 WHERE usuario_id = ?");
            $updateStmt->execute([$usuario_id]);

            return "Sesión cerrada correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Validar la sesión del usuario
    public function validarSesion($token)
    {
        try {
            // Buscar al usuario en la base de datos
            $stmt = $this->pdo->prepare("SELECT * FROM login WHERE token = ? AND status = 1");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario no se encuentra
            if (!$user) {
                return "Sesión no válida o usuario inactivo";
            }

            // Si el usuario existe, devolver sesión válida
            return [
                'success' => true,
                'message' => 'Sesión válida',
                'usuario_id' => $user->usuario_id,
                'token' => $user->token
            ];
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
