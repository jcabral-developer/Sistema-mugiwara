<?php

class StockController
{

    public function index()
    {
        require_once BASE_PATH . '/app/models/configuraciones/insumoModel.php';
        require_once BASE_PATH . '/app/models/stock/StockModel.php';
     require_once BASE_PATH . '/app/models/SistemaModel.php';

        $bajoStock = SistemaModel::obtenerStockBajo();
        $insumos = InsumoModel::obtenerTodos();
        $stock = StockModel::obtenerStock();
        $compras = StockModel::obtenerCompras();
        $insumosCriticos = StockModel::obtenerCriticos();
        $ultimaCompra = StockModel::obtenerUltimaCompra();


        require_once BASE_PATH . '/app/views/stock/index.php';
    }

    public function registrarCompra()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos']);
            return;
        }

        require_once BASE_PATH . '/app/models/stock/StockModel.php';
        $modeloStock = new StockModel();

        try {

            $modeloStock->guardarCompraCompleta($data);

            echo json_encode([
                'ok' => true,
                'mensaje' => 'Compra registrada correctamente'
            ]);

        } catch (Exception $e) {
            // Esto asegura que el error también se envíe como JSON y no como texto
            http_response_code(500);
            echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    public function obtenerDetalleCompra()
    {
        // Limpiar cualquier salida previa para evitar que se pegue HTML
        if (ob_get_length())
            ob_clean();

        header('Content-Type: application/json');

        try {
            if (!isset($_GET['id'])) {
                throw new Exception('ID faltante');
            }
            $id = $_GET['id'];
            require_once BASE_PATH . '/app/models/stock/StockModel.php';
            $modelo = new StockModel();
            $compraData = $modelo->obtenerDetalleCompra($id);

            echo json_encode([
                'ok' => true,
                'compra' => $compraData
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'ok' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    //elimianar compra

    public function eliminarCompra()
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }

        $compra = (int) $data['id'];

        require_once BASE_PATH . '/app/models/stock/StockModel.php';
        $stock = new StockModel();

        try {
            $stock->eliminarCompra($compra);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function actualizarLimites()
    {


        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!$data || !isset($data['id'], $data['minimo'], $data['uni_limit'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos']);
            exit;
        }

        $id = (int) $data['id'];
        $minimo = (float) $data['minimo'];
        $unidad_limite = $data['uni_limit'];


        require_once BASE_PATH . '/app/models/stock/StockModel.php';
        $stockModel = new StockModel();

        try {
            $stockModel->actualizarLimites($id, $minimo, $unidad_limite );
            echo json_encode(['ok' => true, 'mensaje' => 'Stock actualizado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
        }
        exit;

    }

    public function actualizarLimitesYStock()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!$data || !isset($data['id'], $data['stock'])) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos']);
            exit;
        }

        $id = (int) $data['id'];
        $stock = (float) $data['stock'];
        $unidad = $data['uni'];

        require_once BASE_PATH . '/app/models/stock/StockModel.php';
        $stockModel = new StockModel();

        try {
            $stockModel->actualizarLimitesYStock($id, $stock, $unidad, );
            echo json_encode(['ok' => true, 'mensaje' => 'Stock actualizado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
        }
        exit;
    }
}
