<?php

class PromosController
{


    public function index()
    {
        require_once BASE_PATH . '/app/models/configuraciones/insumoModel.php';
        require_once BASE_PATH . '/app/models/promos/promosModel.php';
        require_once BASE_PATH . '/app/models/SistemaModel.php';
        $modeloPlatos = new PromoModel();

        $bajoStock = SistemaModel::obtenerStockBajo();
        $platos = $modeloPlatos->obtenerPlatos();

        require_once BASE_PATH . '/app/views/promos/index.php';
    }
    public function obtenerPromos()
    {
        header('Content-Type: application/json');
        require_once BASE_PATH . '/app/models/promos/promosModel.php';
        $modelo = new PromoModel();
        $data = $modelo->obtenerPromos();

        echo json_encode($data);
        exit;
    }

    public function eliminarPromo()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id'])) {
            echo json_encode(["status" => "error", "message" => "ID inválido"]);
            exit;
        }
        require_once BASE_PATH . '/app/models/promos/promosModel.php';
        $modelo = new PromoModel();
        $ok = $modelo->eliminarPromo($input['id']);

        if ($ok) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo eliminar"]);
        }

        exit;
    }
    public function enviarPromo()
    {
        header('Content-Type: application/json');

        try {
            // Al usar FormData en JS, los datos llegan por $_POST, no por php://input
            if (empty($_POST)) {
                echo json_encode(["status" => "error", "message" => "Datos inválidos o vacíos"]);
                return;
            }

            // VALIDACIONES BÁSICAS
            if (empty($_POST['nombre']) || empty($_POST['tipo'])) {
                echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
                return;
            }

            // Decodificamos los productos (que enviamos como string JSON desde JS)
            $productos = isset($_POST['productos']) ? json_decode($_POST['productos'], true) : [];

            if (empty($productos)) {
                echo json_encode(["status" => "error", "message" => "La promo no tiene productos"]);
                return;
            }

            // --- LÓGICA DE LA IMAGEN ---
            $nombreImagen = 'promosDefecto.png'; // Imagen por defecto

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['imagen']['tmp_name'];
                $fileName = $_FILES['imagen']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Generamos un nombre único para evitar pisar imágenes anteriores
                // Ejemplo: promo_1709212345.jpg
                $nuevoNombreImagen = "promo_" . time() . "." . $fileExtension;

                // Definimos la ruta 
                $destPath = BASE_PATH . '/public/img/promos/' . $nuevoNombreImagen;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $nombreImagen = $nuevoNombreImagen;
                }
            }

            // Preparo los datos para el modelo (incluyendo el nombre de la imagen)
            $dataParaGuardar = $_POST;
            $dataParaGuardar['productos'] = $productos;
            $dataParaGuardar['imagen_nombre'] = $nombreImagen;

            require_once BASE_PATH . '/app/models/promos/promosModel.php';
            $model = new PromoModel();

            // Enviamos el array al modelo
            if (!empty($_POST['id'])) {
                //  MODO EDICIÓN
                $dataParaGuardar['id'] = $_POST['id'];
                $resultado = $model->actualizarPromo($dataParaGuardar);
            } else {
                //  MODO CREACIÓN
                $resultado = $model->guardarPromo($dataParaGuardar);
            }



            if ($resultado) {
                echo json_encode(["status" => "ok"]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudo guardar en la BD"]);
            }

        } catch (Exception $e) {
            error_log("Error enviarPromo: " . $e->getMessage());
            echo json_encode(["status" => "error", "message" => "Error interno: " . $e->getMessage()]);
        }
    }
}