<?php


class PreciosController
{



   public function index()
{
    require_once BASE_PATH . '/app/models/SistemaModel.php';
    require_once BASE_PATH . '/app/models/precios/precioModel.php';

    $model = new PrecioModel();

    $infoPlatos = $model->obtenerPlatos();

    $bajoStock = SistemaModel::obtenerStockBajo();

    require_once BASE_PATH . '/app/views/precios/index.php';
}

public function obtenerIngredientes()
{
    require_once BASE_PATH . '/app/models/precios/precioModel.php';

    $model = new PrecioModel();

    $plato_id = (int)$_GET['plato'];

    $ingredientes = $model->obtenerIngredientesPlato($plato_id);

    echo json_encode($ingredientes);
}

public function actualizarImagen(){
    // Limpiamos cualquier espacio o warning previo para asegurar JSON puro
    if (ob_get_length()) ob_clean(); 
    header('Content-Type: application/json');

    try {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
            $idPlato = $_POST['id'];
            $file = $_FILES['imagen'];

            // Usamos BASE_PATH para no errar la ruta en XAMPP
            $directorioDestino = BASE_PATH . "/public/img/imagenes_de_comidas/";
            
            // Asegurarnos de que la carpeta existe
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nombreArchivo = "plato_" . $idPlato . "_" . time() . "." . $extension;
            $rutaFinal = $directorioDestino . $nombreArchivo;

            if (move_uploaded_file($file['tmp_name'], $rutaFinal)) {
                require_once BASE_PATH . '/app/models/precios/precioModel.php';
                $modelo = new PrecioModel();
                $exito = $modelo->actualizarRutaImagen($idPlato, $nombreArchivo);

                if ($exito) {
                    echo json_encode(["success" => true, "nuevoNombre" => $nombreArchivo]);
                } else {
                    echo json_encode(["success" => false, "mensaje" => "Error al guardar en BD"]);
                }
            } else {
                echo json_encode(["success" => false, "mensaje" => "No se pudo mover el archivo a: " . $rutaFinal]);
            }
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "mensaje" => $e->getMessage()]);
    }
    exit; // Importante para que no se renderice nada más de index.php
}
public function guardarPrecio()
{

require_once BASE_PATH . '/app/models/precios/precioModel.php';
    $data = json_decode(file_get_contents("php://input"), true);

    $plato = $data['plato'];
    $costo = $data['costo'];
    $margen = $data['margen'];
    $precio = $data['precio'];
    $ganan = $data['ganancias'];

    $modelo = new PrecioModel();

    $ok = $modelo->guardarPrecioPlato($plato,$costo,$margen,$precio,$ganan);

    if($ok){
        echo json_encode(["status"=>"ok"]);
    }else{
        echo json_encode(["status"=>"error"]);
    }

}





}