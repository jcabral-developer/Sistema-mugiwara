<?php

class SistemaModel
{


    private $db;

    public function __construct()
    {

        $this->db = Database::connect();

    }

     static function obtenerStockBajo()
    {

        $db = Database::connect();
        $stmt = $db->query("SELECT 1 FROM insumo WHERE stock <= stock_minimo LIMIT 1");
return (bool) $stmt->fetch();
    }


}
