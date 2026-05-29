<?php


class RendimientoModel
{

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
            if ($fila['unidad'] != 'un') {
                if ($fila['unidad'] == 'ml') {
                    $fila['cantidad_usada'] = self::mostrarPeso($fila['cantidad_usada'], $fila['unidad']);
                } else {
                    $fila['cantidad_usada'] = self::mostrarPeso($fila['cantidad_usada']);
                }
            }
        }
        unset($fila);

        return $datos;
    }

    private static function mostrarPeso($valor, $unidad = 'gramos')
    {
        $unidad = strtolower(trim($unidad));

        if ($unidad === 'gramos') {
            if (abs($valor) >= 1000) {   // usamos abs() para decidir
                $kg = $valor / 1000;     // mantenemos el signo

                if ($kg == floor($kg)) {
                    return number_format($kg, 0, ',', '.') . " kg";
                }

                return number_format($kg, 3, ',', '.') . " kg";
            }

            return number_format($valor, 0, ',', '.') . " gr";
        }

        if ($unidad === 'ml') {
            if (abs($valor) >= 1000) {
                $litros = $valor / 1000;

                if ($litros == floor($litros)) {
                    return number_format($litros, 0, ',', '.') . " lt";
                }

                return number_format($litros, 3, ',', '.') . " lt";
            }

            return number_format($valor, 0, ',', '.') . " ml";
        }

        return $valor . " " . $unidad;
    }





}