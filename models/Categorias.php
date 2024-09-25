<?php
// models/Usuario.php
class User
{
    private $pdocatalogo;

    public function __construct($pdocatalogo)
    {
        $this->pdocatalogo = $pdocatalogo;
    }

    public function get()
    {
        try {
            $stmt = $this->pdocatalogo->query("SELECT * FROM categorias");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener usuario por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
    public function create($data)
    {
        try {
            $stmt = $this->pdocatalogo->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
            // Bind parameters
            $stmt->bindParam(':nombre', $data['nombre']);

            $stmt->execute();
            return "registro guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar usuario
    public function update($data, $id)
    {
        $sql = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
        ]);
        return "Registro actualizado correctamente";
    }

    // Eliminar usuario
    public function delete($id)
    {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "Registro eliminado correctamente";
    }
}
