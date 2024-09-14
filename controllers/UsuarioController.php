<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../models/Usuario.php';

class UserController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function getAllUsers()
    {
        return $this->userModel->getUsers();
    }

    public function getUserDetail($id)
    {
        return $this->userModel->getUserById($id);
    }

    public function createUser($data)
    {
        if (!isset($data['user_name'], $data['last_name'], $data['nombre_doc'], $data['address'], $data['telephone'], $data['email'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->userModel->create($data);
    }

    public function updateUser($data, $id)
    {
        return $this->userModel->update($data, $id);
    }

    public function deleteUser($id)
    {
        return $this->userModel->delete($id);
    }
}
