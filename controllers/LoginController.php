<?php
// controllers/LoginController.php
require_once __DIR__ . '/../models/Login.php';

class LoginController
{
    private $loginModel;

    public function __construct($pdo)
    {
        $this->loginModel = new Login($pdo);
    }

    public function loginUser($data)
    {

        if (!isset($data['nick_name']) && !isset($data['password'])) {
            return "Error: Faltan campos requeridos.";
        }

        return $this->loginModel->login($data);
    }

    // Función para cerrar la sesión de un usuario por usuario_id
    public function logoutUser($usuario_id)
    {
        if (!isset($usuario_id)) {
            return "Error: No se puede cerrar sesión.";
        }

        return $this->loginModel->logout($usuario_id);
    }

    public function validarSesionUsuario($token)
    {
        return $this->loginModel->validarSesion($token);
    }
}
