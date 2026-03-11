<?php

class DashboardController {

    public function index() {
     require_once BASE_PATH . '/app/models/SistemaModel.php';

        $bajoStock = SistemaModel::obtenerStockBajo();
    
    require BASE_PATH . '/app/views/dashboard/index.php';
    }



}
