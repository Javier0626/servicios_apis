<?php
// models/Usuario.php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM usuario");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener usuario por ID
    public function getUserById($id)
    {
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
    public function create($data)
    {
        try {
            // Iniciar la transacción
            $this->pdo->beginTransaction();

            // Insertar en la tabla usuario
            $stmt = $this->pdo->prepare("INSERT INTO usuario (user_name, last_name, nombre_doc, address, telephone, email, created_ad, updated_at)
                                         VALUES (:user_name, :last_name, :nombre_doc, :address, :telephone, :email, NOW(), NOW())");

            // Bind de parámetros para usuario
            $stmt->bindParam(':user_name', $data['user_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':nombre_doc', $data['nombre_doc']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();

            // Obteneenemos el ID del usuario recién insertado
            $usuario_id = $this->pdo->lastInsertId();

            // Hash de la contraseña
            $password = password_hash($data['password'], PASSWORD_BCRYPT);

            // generar un token manual
            $token = bin2hex(random_bytes(32));

            // estado activo por defecto
            $status = 1;

            // Insertamos en la tabla login
            $stmt = $this->pdo->prepare("INSERT INTO login (nick_name, password, token, estado_id, rol_id, usuario_id, status)
                                         VALUES (:nick_name, :password, :token, :estado_id, :rol_id, :usuario_id, :status)");

            // Bind de parámetros para login
            $stmt->bindParam(':nick_name', $data['nick_name']);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':estado_id', $data['estado_id']);
            $stmt->bindParam(':rol_id', $data['rol_id']);
            $stmt->bindParam(':usuario_id', $usuario_id); // Vinculamos el usuario recién creado
            $stmt->bindParam(':status', $status); // estado de login en 1 (activo) 0(inactivo)
            $stmt->execute();

            // Confirmar la transacción
            $this->pdo->commit();

            return "Usuario y login creados correctamente";
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $this->pdo->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar usuario
    public function update($data, $id)
    {
        try {
            // Actualizar la tabla usuario
            $sqlUsuario = "UPDATE usuario 
                           SET user_name = :user_name, 
                               last_name = :last_name, 
                               nombre_doc = :nombre_doc, 
                               address = :address, 
                               telephone = :telephone, 
                               email = :email 
                           WHERE id = :id";
            $stmtUsuario = $this->pdo->prepare($sqlUsuario);
            $stmtUsuario->execute([
                'id' => $id,
                'user_name' => $data['user_name'],
                'last_name' => $data['last_name'],
                'nombre_doc' => $data['nombre_doc'],
                'address' => $data['address'],
                'telephone' => $data['telephone'],
                'email' => $data['email']
            ]);

            // Actualizar la tabla login si se proveen datos de login
            if (!empty($data['nick_name']) && !empty($data['password'])) {
                $passwordHashed = password_hash($data['password'], PASSWORD_BCRYPT);

                $sqlLogin = "UPDATE login 
                             SET nick_name = :nick_name, 
                                 password = :password, 
                                 estado_id = :estado_id, 
                                 rol_id = :rol_id 
                             WHERE usuario_id = :usuario_id";
                $stmtLogin = $this->pdo->prepare($sqlLogin);
                $stmtLogin->execute([
                    'nick_name' => $data['nick_name'],
                    'password' => $passwordHashed,
                    'estado_id' => $data['estado_id'],
                    'rol_id' => $data['rol_id'],
                    'usuario_id' => $id
                ]);
            }

            return "Usuario y login actualizados correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    // Eliminar usuario
    public function delete($id)
    {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "Usuario eliminado correctamente";
    }
}
