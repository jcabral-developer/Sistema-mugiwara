<?php
/*************************************************
 * INDEX.PHP
 * Punto de entrada único del sistema
 * Controla sesiones, rutas y seguridad básica
 *************************************************/

// 1️ FORZAR USO DE COOKIES SEGURAS PARA SESIÓN
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// 2️ CONFIGURACIÓN DE LA COOKIE DE SESIÓN
session_set_cookie_params([
    'lifetime' => 0,          // dura hasta cerrar el navegador
    'path' => '/',
    'domain' => '',
    'secure' => false,        // true si usás HTTPS
    'httponly' => true,       // JS no puede acceder a la cookie
    'samesite' => 'Lax'    // evita CSRF básico
]);

// 3️ INICIAR SESIÓN
session_start();

// 4️ REGENERAR ID DE SESIÓN (anti session fixation)
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}

// 5️ DEFINIR RUTA BASE DEL PROYECTO

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/Sistema_mugiwara/public');



// 6️ CARGAR CONTROLADORES
require_once BASE_PATH . '/app/controllers/loginController.php';
require_once BASE_PATH . '/app/controllers/dashboardController.php';
require_once BASE_PATH . '/app/controllers/stockController.php';
require_once BASE_PATH . '/app/controllers/configController.php';

// 7️ OBTENER RUTA SOLICITADA
$route = $_GET['route'] ?? '';
// echo "ID de sesión actual: " . session_id() . "<br>";
// echo "Contenido de SESSION: ";
// print_r($_SESSION);

// 8️ VERIFICAR SI EL USUARIO ESTÁ LOGUEADO, ¿ESTÁ LOGUEADO? 
$usuarioLogueado = isset($_SESSION['user_id']);

// 9️ ROUTER PRINCIPAL
if (!$usuarioLogueado) {

    // SOLO LOGIN
    $controller = new LoginController();

    if ($route === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } else {
        $controller->mostrarLogin();
    }
} else {

    // Usuario autenticado → decidir a dónde va
    switch ($route) {

        case 'stock':
            $controller = new StockController();
            $controller->index();
            break;

        case 'config':
            $controller = new ConfigController();
            $controller->index();
            break;

        case 'logout':
            session_destroy();
            header('Location: index.php');
            exit;

        default:
            $controller = new DashboardController();
            $controller->index();
            break;
    }
}
