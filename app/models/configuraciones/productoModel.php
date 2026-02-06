<?php

class ProductoModel
{
    public static function obtenerTodos()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT id, descripcion FROM plato ORDER BY descripcion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
