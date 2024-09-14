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
        if (!isset($data['nick_name'], $data['password'], $data['estado_id'], $data['rol_id'], $data['usuario_id'])) {
            return "Error: Credenciales incorrectas o faltan campos requeridos.";
        }
        return $this->loginModel->login($data);
    }
}
