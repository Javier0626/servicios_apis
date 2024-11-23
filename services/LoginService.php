<?php
// services/LoginService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/LoginController.php';

// Configuración del servicio SOAP
$namespace = "LoginUsuario";
$server = new soap_server();
$server->configureWSDL('ServicioLogin', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el usuario
$server->wsdl->addComplexType(
    'Credenciales',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nick_name' => array('name' => 'nick_name', 'type' => 'xsd:string'),
        'password' => array('name' => 'password', 'type' => 'xsd:string'),
    )
);

// Registrar método SOAP para credenciales de usuario
$server->register(
    'InicioSesion',
    array('data' => 'tns:Credenciales'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Servicio para insertar las credenciales de inicio de sesión de un usuario'
);

// Registrar el método SOAP para cerrar la sesión
$server->register(
    'CerrarSesion',
    array('usuario_id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Servicio para cerrar la sesión de un usuario'
);

// Registrar método SOAP para la validación de la sesión del usuario
$server->register(
    'ValidarSesion',
    array('usuario_id' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Servicio para validar si un usuario está logueado'
);

// Función que llama al controlador para crear las credenciales de inicio de sesion de un usuario
function InicioSesion($data)
{
    $pdo = getConnection();
    $controller = new LoginController($pdo);
    return $controller->loginUser($data);
}

// Función que llama al controlador para cerrar la sesión del usuario
function CerrarSesion($usuario_id)
{
    $pdo = getConnection();
    $controller = new LoginController($pdo);
    return $controller->logoutUser($usuario_id);
}

// Función que llama al controlador para validar la sesión de un usuario
function ValidarSesion($token)
{
    $pdo = getConnection();
    $controller = new LoginController($pdo);
    return $controller->validarSesionUsuario($token);
}


//-----------------------

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
