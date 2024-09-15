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
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO usuario (user_name, last_name, nombre_doc, address, telephone, email, password, created_ad, updated_at)
                                         VALUES (:user_name, :last_name, :nombre_doc, :address, :telephone, :email, :password, NOW(), NOW() )");

            // Bind parameters
            $stmt->bindParam(':user_name', $data['user_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':nombre_doc', $data['nombre_doc']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            return "Usuario guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar usuario
    public function update($data, $id)
    {
        $sql = "UPDATE usuario SET user_name = :user_name, last_name = :last_name, nombre_doc = :nombre_doc, address = :address, telephone = :telephone, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'user_name' => $data['user_name'],
            'last_name' => $data['last_name'],
            'nombre_doc' => $data['nombre_doc'],
            'address' => $data['address'],
            'telephone' => $data['telephone'],
            'email' => $data['email']
        ]);
        return "Usuario actualizado correctamente";
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
