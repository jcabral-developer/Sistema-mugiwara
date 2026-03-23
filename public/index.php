<?php
/*************************************************
 * INDEX.PHP
 * Punto de entrada único del sistema
 * Controla sesiones, rutas y seguridad básica
 *************************************************/

// 1️ FORZAR USO DE COOKIES SEGURAS PARA SESIÓN

// Evitar que el navegador guarde en caché páginas privadas
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
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
require_once BASE_PATH . '/app/controllers/pedidosController.php';
require_once BASE_PATH . '/app/controllers/preciosController.php';

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
            // 1. Vaciamos el array de sesión
            $_SESSION = array();

            // 2. Si se desea destruir la sesión completamente, borramos también la cookie de sesión.
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            // 3. Finalmente, destruir la sesión en el servidor
            session_destroy();

            // 4. Redirigir al login
            header('Location: index.php');
            exit;

        case 'config/insumo':
            $controller = new ConfigController();
            $controller->registrarInsumo();
            break;
        case 'config/plato':
            $controller = new ConfigController();
            $controller->registrarPlato();
            break;
        case 'pedidos':
            $controller = new PedidosController();
            $controller->index();
            break;

        case 'precios':
            $controller = new PreciosController();
            $controller->index();
            break;

        case 'config/rendimiento':
            $controller = new ConfigController();
            $controller->registrarRendimiento();
            break;

        case 'config/eliminarRegla':
            $controller = new ConfigController();
            $controller->eliminarRendimiento();
            break;
        case 'stock/registrarCompra':
            $controller = new StockController();
            $controller->registrarCompra();
            break;

        case 'stock/obtenerDetalleCompra':
            $controller = new StockController();
            $controller->obtenerDetalleCompra();
            break;

        case 'stock/eliminarCompra':
            $controller = new StockController();
            $controller->eliminarCompra();
            break;
        case 'stock/actualizarLimitesYStock':
            $controller = new StockController();
            $controller->actualizarLimitesYStock();
            break;
        case 'precios/guardarPrecio':
            $controller = new PreciosController();
            $controller->guardarPrecio();
            break;
        case 'precios/obtenerIngredientes':
            $controller = new PreciosController();
            $controller->obtenerIngredientes();
            break;
        case 'stock/actualizarLimites':
            $controller = new StockController();
            $controller->actualizarLimites();
            break;
        case 'precios/guardarImagen':
            $controller = new PreciosController();
            $controller->actualizarImagen();
            break;
        case 'pedidos/guardarVenta':
            $controller = new PedidosController();
            $controller->guardarVenta();
            break;
        case 'pedidos/traerVentas':
            $controller = new PedidosController();
            $controller->traerVentas();
            break;


        default:
            $controller = new DashboardController();
            $controller->index();
            break;
    }
}
