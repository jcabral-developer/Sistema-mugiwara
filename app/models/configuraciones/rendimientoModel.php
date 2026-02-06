<?php


Class RendimientoModel{

       public static function obtenerTodos()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT rendimiento.id, insumo.descripcion AS insumo,plato.descripcion AS plato,rendimiento.cantidad_usada,rendimiento.unidad,rendimiento.rendimiento 
                                   FROM rendimiento 
                                   INNER JOIN insumo ON insumo.id = rendimiento.insumo 
                                   INNER JOIN plato on plato.id = rendimiento.plato
                                   ORDER BY rendimiento.id DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}