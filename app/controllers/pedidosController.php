<?php


Class PedidosController{



public function index()
    {
        // require_once BASE_PATH . '/app/models/configuraciones/insumoModel.php';
        // require_once BASE_PATH . '/app/models/configuraciones/productoModel.php';
        // require_once BASE_PATH . '/app/models/configuraciones/rendimientoModel.php';

        // $productos = ProductoModel::obtenerTodos();
        // $insumos = InsumoModel::obtenerTodos();
        // $reglas = RendimientoModel::obtenerTodos();

         require_once BASE_PATH . '/app/models/SistemaModel.php';
        require_once BASE_PATH . '/app/models/pedidos/pedidosModel.php';

        $bajoStock = SistemaModel::obtenerStockBajo();
        $modelo = new PedidoModel();
        $platos = $modelo->obtenerPlatos();

        require_once BASE_PATH . '/app/views/pedidos/index.php';
    }


public function guardarVenta()
{
    // Limpiar cualquier salida previa (buffer) para evitar errores de JSON inválido
  if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $json = file_get_contents("php://input");
    $data = json_decode($json, true);


    
    // Validamos que el JSON sea válido y que traiga los datos mínimos (método y detalle)
    if (!$data || empty($data['metodo_pago']) || empty($data['items'])) {
        echo json_encode([
            "status" => "error", 
            "message" => "Datos incompletos o formato JSON inválido"
        ]);
        return;
    }

    try {
        require_once BASE_PATH . '/app/models/pedidos/pedidosModel.php';
        $modelo = new PedidoModel();

        $resultado = $modelo->guardarVenta($data);

        if ($resultado) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error en la base de datos"]);
        }
    } catch (Exception $e) {
        // Capturamos cualquier error inesperado
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}


public function traerVentas()
{
    // Limpiamos cualquier salida previa y enviamos cabecera JSON
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    try {
        // Leer el JSON enviado por el Fetch
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        // Validar que el parámetro exista, por defecto usamos 'HOY'
        $tipoFiltro = $data['datos'] ?? 'HOY';

        require_once BASE_PATH . '/app/models/pedidos/pedidosModel.php';
        $modelo = new PedidoModel();

        // Llamamos al modelo pasando el filtro
        $ventas = $modelo->obtenerVentas($tipoFiltro);

        // Calculamos totales para los stats del modal
        $totalSumado = 0;
        $gananciaSumada = 0;
        foreach ($ventas as $v) {
            $totalSumado += $v['total'];
            $gananciaSumada += $v['ganancia'];
        }

        echo json_encode([
            "status" => "ok",
            "ventas" => $ventas,
            "totalSumado" => number_format($totalSumado, 0, ',', '.'),
            "gananciaSumada" => number_format($gananciaSumada, 0, ',', '.')
        ]);

    } catch (Exception $e) {
        error_log("Error en traerVentas: " . $e->getMessage());
        echo json_encode([
            "status" => "error",
            "message" => "No se pudieron obtener las ventas"
        ]);
    }
}



}