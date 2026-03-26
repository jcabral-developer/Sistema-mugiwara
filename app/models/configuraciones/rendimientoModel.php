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

private static function mostrarPeso($valor, $unidad = 'gramos')
{
    // Normalizamos la unidad para evitar errores
    $unidad = strtolower($unidad);

    if ($unidad === 'gramos') {
        if ($valor >= 1000) {
            $kg = $valor / 1000;

            if ($kg == floor($kg)) {
                return number_format($kg, 0, ',', '.') . " kg";
            }

            return number_format($kg, 3, ',', '.') . " kg";
        }

        return number_format($valor, 0, ',', '.') . " gr";
    }

    if ($unidad === 'mililitros') {
        if ($valor >= 1000) {
            $litros = $valor / 1000;

            if ($litros == floor($litros)) {
                return number_format($litros, 0, ',', '.') . " l";
            }

            return number_format($litros, 3, ',', '.') . " l";
        }

        return number_format($valor, 0, ',', '.') . " ml";
    }

    // Si se pasa una unidad desconocida
    return $valor . " " . $unidad;
}





}