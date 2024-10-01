<?php
// models/productos.php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM productos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener productos por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear productos
    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO productos (user_name, last_name, nombre_doc, address, telephone, email, password, created_ad, updated_at)
                                         VALUES (:user_name, :last_name, :nombre_doc, :address, :telephone, :email, :password, NOW(), NOW() )");
            // Bind parameters
            $stmt->bindParam(':user_name', $data['user_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':nombre_doc', $data['nombre_doc']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            return "productos guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar productos
    public function update($data, $id)
    {
        $sql = "UPDATE productos SET user_name = :user_name, last_name = :last_name, nombre_doc = :nombre_doc, address = :address, telephone = :telephone, email = :email WHERE id = :id";
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
        return "productos actualizado correctamente";
    }

    // Eliminar productos
    public function delete($id)
    {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "productos eliminado correctamente";
    }
}
