<?php
// services/LoginService.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/catalogo_db.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

// Configuración del servicio SOAP
$namespace = "Productos";
$server = new soap_server();
$server->configureWSDL('ServicioProductos', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el Producto
$server->wsdl->addComplexType(
    'Producto',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
        'precio' => array('name' => 'precio', 'type' => 'xsd:double'),
        'stock' => array('name' => 'stock', 'type' => 'xsd:double'),
        'descripcion' => array('name' => 'decripcion', 'type' => 'xsd:string'),
        'categoria_id' => array('name' => 'categoria_id', 'type' => 'xsd:int')
    )
);


// Registrar método SOAP para ver todos los Productos
$server->register(
    'VerProductos',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los Productos'
);

// Registrar método SOAP para ver detalle de un Producto
$server->register(
    'VerProducto',
    array('id' => 'tns:int'),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Detalle de un Producto'
);

// Registrar método SOAP para crear un Producto
$server->register(
    'CrearProducto',
    array('data' => 'tns:Producto'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un Producto'
);

// Registrar método SOAP para actualizar un Producto
$server->register(
    'ActualizarProducto',
    array('data' => 'tns:Producto', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar un Producto'
);

// Registrar método SOAP para eliminar un Producto
$server->register(
    'EliminarProducto',
    array('id' => 'tns:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un Producto'
);


// Función que llama al controlador para ver todos los Productos
function VerProductos() {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->getAllUsers();
}

// Función que llama al controlador para obtener el detalle de uh Producto
function VerProducto($id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->getUserDetail($id);
}

// Función que llama al controlador para crear Producto
function CrearProducto($data) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->createUser($data);
}

// Función que llama al controlador para actualizar Producto
function ActualizarProducto($data, $id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->updateUser($data, $id);
}


// Función que llama al controlador para eliminar Producto
function EliminarProducto($id) {
    $pdo = getConnection();
    $controller = new UserController($pdo);
    return $controller->deleteUser($id);
}

//-----------------------

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
