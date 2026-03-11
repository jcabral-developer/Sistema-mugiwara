<?php


Class RendimientoModel{

  public static function obtenerTodos()
{
    $db = Database::connect();
    $stmt = $db->query("
        SELECT rendimiento.id,
               insumo.descripcion AS insumo,
               plato.descripcion AS plato,
               rendimiento.cantidad_usada,
               rendimiento.unidad,
               rendimiento.rendimiento
        FROM rendimiento
        INNER JOIN insumo ON insumo.id = rendimiento.insumo
        INNER JOIN plato ON plato.id = rendimiento.plato
        ORDER BY rendimiento.id DESC
    ");

    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($datos as &$fila) {
        // formatea la cantidad usada en gramos/kilos
        if($fila['unidad'] != 'un')
        $fila['cantidad_usada'] = self::mostrarPeso($fila['cantidad_usada']);
    }
    unset($fila);

    return $datos;
}

private static function mostrarPeso($gramos)
    {
        if ($gramos >= 1000) {

            $kg = $gramos / 1000;

            // Si es entero → sin decimales
            if ($kg == floor($kg)) {
                return number_format($kg, 0, ',', '.') . " kg";
            }

            // Si tiene decimales → mostrar 3
            return number_format($kg, 3, ',', '.') . " kg";
        }

        return number_format($gramos, 0, ',', '.') . " gr";
    }





}