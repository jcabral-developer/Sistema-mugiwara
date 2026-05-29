<?php

class InsumoModel
{
    public static function obtenerTodos()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM insumo ORDER BY descripcion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
