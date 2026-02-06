<?php

class StockController {

       public function index()
    {
        require_once BASE_PATH . '/app/models/configuraciones/insumoModel.php';
        // require_once BASE_PATH . '/app/models/configuraciones/productoModel.php';
       // require_once BASE_PATH . '/app/models/configuraciones/rendimientoModel.php';

       // $productos = ProductoModel::obtenerTodos();
        $insumos = InsumoModel::obtenerTodos();
        //$reglas = RendimientoModel::obtenerTodos();

        require_once BASE_PATH . '/app/views/stock/index.php';
    }


}
