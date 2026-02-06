<?php

class ConfigController
{

    public function index()
    {
        require_once BASE_PATH . '/app/models/configuraciones/insumoModel.php';
        require_once BASE_PATH . '/app/models/configuraciones/productoModel.php';
        require_once BASE_PATH . '/app/models/configuraciones/rendimientoModel.php';

        $productos = ProductoModel::obtenerTodos();
        $insumos = InsumoModel::obtenerTodos();
        $reglas = RendimientoModel::obtenerTodos();

        require_once BASE_PATH . '/app/views/config/index.php';
    }


    public function registrarInsumo()
    {

        $nombre = self::limpiarInputs($_POST['insumo'] ?? '');           // Espacios

        $errores = [];

        if (empty($nombre) || strlen($nombre) < 3) {
            $errores[] = "El nombre del insumo es obligatorio y debe tener al menos 3 caracteres.";
        }

        if (is_numeric($nombre)) {
            $errores[] = "El nombre del insumo debe conformarse por letras.<br />";
        }
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $errores[] = "El nombre del insumo solo puede contener letras y espacios.";
        }



        if ($errores) {
            $_SESSION['errores'] = $errores;
            header("Location: index.php?route=config");
            exit;


        }


        require_once BASE_PATH . '/app/models/configuraciones/configuraciones.php';
        $insumo = new Configuraciones();


        try {
            $insumo->crearinsumo($nombre);
            $_SESSION['success'] = "Insumo registrado correctamente.";
        } catch (Exception $e) {
            $_SESSION['errores'][] = $e->getMessage();
        }

        header("Location: index.php?route=config");
        exit;

    }

    public function registrarPlato()
    {

        $nombre = self::limpiarInputs($_POST['producto'] ?? '');           // Espacios
        $errores = [];

        if (empty($nombre) || strlen($nombre) < 3) {
            $errores[] = "El nombre del plato es obligatorio y debe tener al menos 3 caracteres.";
        }

        if (is_numeric($nombre)) {
            $errores[] = "El nombre del plato debe conformarse por letras.<br />";
        }
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $errores[] = "El nombre del plato solo puede contener letras y espacios.";
        }

        if ($errores) {
            $_SESSION['errores'] = $errores;
            header("Location: index.php?route=config");
            exit;


        }

        require_once BASE_PATH . '/app/models/configuraciones/configuraciones.php';
        $insumo = new Configuraciones();


        try {
            $insumo->crearPlato($nombre);
            $_SESSION['success'] = "Plato registrado correctamente.";
        } catch (Exception $e) {
            $_SESSION['errores'][] = $e->getMessage();
        }

        header("Location: index.php?route=config");
        exit;


    }

    public function registrarRendimiento()
    {

        $plato = self::limpiarInputs($_POST['producto_id']);
        $insumo = self::limpiarInputs($_POST['insumo_id']);
        $cantidad = self::limpiarInputs($_POST['cantidad']);
        $unidad = self::limpiarInputs($_POST['unidad']);
        $rendimiento = self::limpiarInputs($_POST['rendimiento']);

        require_once BASE_PATH . '/app/models/configuraciones/configuraciones.php';
        $registrarRendimeito = new Configuraciones();


        try {
            $registrarRendimeito->crearRendimiento($plato, $insumo, $cantidad, $unidad, $rendimiento);
            $_SESSION['success'] = "Rendimiento registrado correctamente.";
        } catch (Exception $e) {
            $_SESSION['errores'][] = $e->getMessage();
        }

        header("Location: index.php?route=config");
        exit;



    }

    public static function limpiarInputs(string $valor)
    {
        $nombre = trim($valor ?? '');
        $nombre = strip_tags($nombre);
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        return $nombre;
    }

public function eliminarRendimiento()
{
    if (!isset($_POST['regla'])) {
        $_SESSION['errores'][] = "Regla inválida.";
        header("Location: index.php?route=config");
        exit;
    }

    $regla = (int) $_POST['regla'];

    require_once BASE_PATH . '/app/models/configuraciones/configuraciones.php';
    $config = new Configuraciones();

    try {
        $config->eliminarRendimiento($regla);
        $_SESSION['success'] = "Rendimiento eliminado correctamente.";
    } catch (Exception $e) {
        $_SESSION['errores'][] = $e->getMessage();
    }

    header("Location: index.php?route=config");
    exit;
}

}
