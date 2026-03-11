<?php

class StockModel
{


    private $db;

    public function __construct()
    {

        $this->db = Database::connect();

    }

    public function guardarCompraCompleta($data)
    {
        try {

            $this->db->beginTransaction();

            // encabezado
            $sqlCompra = "INSERT INTO compras (fecha, comprador,total)
                      VALUES (:fecha, :comprador, :total)";
            $stmt = $this->db->prepare($sqlCompra);
            $stmt->execute([
                ':fecha' => $data['fecha'],
                ':comprador' => $data['comprador'],
                ':total' => $data['total']
            ]);

            $compra_id = $this->db->lastInsertId();

            // $total = 0; // ← IMPORTANTE

            foreach ($data['items'] as $item) {

                // detalle

                $sqlDetalle = "INSERT INTO compras_detalle
                          (compra_id, insumo_id, cantidad, unidad_medida, precio_unitario)
                           VALUES (:compra, :insumo, :cantidad, :unidad, :precio)";
                $stmt = $this->db->prepare($sqlDetalle);
                $stmt->execute([
                    ':compra' => $compra_id,
                    ':insumo' => $item['id'],
                    ':cantidad' => $item['cantidad'],
                    ':unidad' => $item['unidad'],
                    ':precio' => $item['precio']
                ]);

                // convertir unidad
                //$cantidadBase = $this->convertirAUnidadBase($item['cantidad'], $item['unidad']);
                if ($item['unidad'] != 'un') {
                    $cantidadBase = self::convertirAGramos($item['cantidad'], $item['unidad']);
                } else {
                    $cantidadBase = $item['cantidad'];
                }
                // stock
                $sqlStock = "UPDATE insumo
                         SET stock = stock + :cantidad
                         WHERE id = :id";
                $stmt = $this->db->prepare($sqlStock);
                $stmt->execute([
                    ':cantidad' => $cantidadBase,
                    ':id' => $item['id']
                ]);
            }

            $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private static function convertirAGramos($cantidad, $unidad)
    {
        $unidad = strtolower(trim($unidad));

        if ($unidad == 'kg' || $unidad == 'kilo' || $unidad == 'kilos') {
            return $cantidad * 1000;
        }

        if ($unidad == 'gr' || $unidad == 'gramo' || $unidad == 'gramos') {
            return $cantidad;
        }

        throw new Exception("Unidad de medida no válida: " . $unidad);
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

    private static function calcularRendimiento($insumo, $stock)
    {
        $db = Database::connect();

        $sql = "SELECT plato.descripcion as descripcion, rendimiento, cantidad_usada, unidad 
            FROM rendimiento 
            INNER JOIN plato ON plato.id = rendimiento.plato 
            WHERE insumo = :insumo;";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':insumo', var: $insumo);
        $stmt->execute();

        $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$recetas)
            return []; // no aplica a ningún plato

        $resultados = [];

        foreach ($recetas as $r) {

            // Convertir cantidad_usada a gramos reales
            $gramosReceta = floatval($r['cantidad_usada']);
            $rendimiento = floatval($r['rendimiento']);
            if ($rendimiento <= 0 || $gramosReceta <= 0 || $stock <= 0) {
                $resultados[] = [
                    'plato' => $r['descripcion'],
                    'cantidad' => 0,
                    'sobrante' => $stock
                ];
                continue;
            }
            // recetas completas posibles
            $recetasEnteras = floor(($stock * $r['rendimiento']) / $gramosReceta);


            // sobrante REAL en gramos
            $sobrante = $stock - (($gramosReceta / $r['rendimiento']) * $recetasEnteras);

            $resultados[] = [
                'plato' => $r['descripcion'],
                'cantidad' => $recetasEnteras,
                'sobrante' => $sobrante,
                'unidad' => 'gr'
            ];
        }
        return $resultados;
    }



    public static function obtenerStock()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM insumo ORDER BY descripcion");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($datos as &$insumo) {
            $excepciones = ['un', 'unidad'];

            if (!in_array(strtolower($insumo['unidad_medida']), $excepciones)) {
                // formatea gramos/kilos
                $insumo['stock_formateado'] = self::mostrarPeso($insumo['stock']);

                // NUEVA COLUMNA -> rendimiento real
                $insumo['rendimientos'] = self::calcularRendimiento(
                    $insumo['id'],
                    $insumo['stock']
                );
            } else {
                $insumo['stock_formateado'] = $insumo['stock'];
                $insumo['rendimientos'] = self::calcularRendimiento(
                    $insumo['id'],
                    $insumo['stock']
                );

            }


        }
        unset($insumo);

        return $datos;
    }


    public static function obtenerCompras()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM compras ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleCompra($id)
    {
        // encabezado
        $sql = "SELECT * FROM compras WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$compra) {
            throw new Exception("Compra no encontrada, el id es: " . $id);
        }

        // detalle
        $sql = "SELECT insumo.descripcion,compras_detalle.cantidad,compras_detalle.unidad_medida,compras_detalle.precio_unitario 
                FROM compras_detalle 
                INNER JOIN insumo ON insumo.id = compras_detalle.insumo_id 
                WHERE compras_detalle.compra_id = ?;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $compra['items'] = $items;

        return $compra;
    }


    public function eliminarCompra(int $compra_id): void
    {
        // Primero borrar detalles
        $sqlDetalle = "DELETE FROM compras_detalle WHERE compra_id = :id";
        $stmtDetalle = $this->db->prepare($sqlDetalle);
        $stmtDetalle->execute([':id' => $compra_id]);

        // Luego borrar la compra
        $sqlCompra = "DELETE FROM compras WHERE id = :id";
        $stmtCompra = $this->db->prepare($sqlCompra);
        $stmtCompra->execute([':id' => $compra_id]);

        if ($stmtCompra->rowCount() === 0) {
            throw new Exception("La compra no existe o ya fue eliminada.");
        }
    }

    public function actualizarLimites($id, $minimo, $unidad)
    {

        $excepciones = ['un', 'unidad', 'Un'];

        // if (!in_array($unidad, $excepciones)) {

        //     $limiteFormateado = $this->convertirAGramos($minimo, $unidad);
        // } else {
        //     $limiteFormateado = $minimo;

        // }

        $sql = "UPDATE insumo SET stock_minimo = :stock_minimo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':stock_minimo' => $minimo
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("El insumo no existe o los valores ya estaban iguales.");
        }


    }

    public function actualizarLimitesYStock($id, $stock, $unidad)
    {

        $excepciones = ['un', 'unidad', 'Un'];

        // if (!in_array($unidad, $excepciones)) {

        //     $stockFormateado = $this->convertirAGramos($stock, $unidad);

        // }else{

        // $stockFormateado = $stock;
        // }

        $sql = "UPDATE insumo SET stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':stock' => $stock
            //  ':minimo' => $limiteFormateado
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("El insumo no existe o los valores ya estaban iguales.");
        }



    }

    public static function obtenerCriticos()
    {

        $db = Database::connect();
        $stmt = $db->query("SELECT id, descripcion, stock,stock_minimo,unidad_medida FROM insumo WHERE stock <= stock_minimo");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($datos as &$insumo) {
            $excepciones = ['un', 'unidad'];

            if (!in_array(strtolower($insumo['unidad_medida']), $excepciones)) {
                // formatea gramos/kilos
                $insumo['stock_formateado'] = self::mostrarPeso($insumo['stock']);
                $insumo['stock_minimo_formateado'] = self::mostrarPeso($insumo['stock_minimo']);
            } else {

                $insumo['stock_formateado'] = $insumo['stock'];
                $insumo['stock_minimo_formateado'] = $insumo['stock_minimo'];
            }

        }

        unset($insumo);

        return $datos;

    }

    public static function obtenerUltimaCompra()
{

    $db = Database::connect();

    $sql = "
    SELECT 
        c.fecha,
        c.id,
        c.total,
        cd.insumo_id,
        i.descripcion,
        cd.cantidad,
        cd.precio_unitario,
        cd.unidad_medida
    FROM compras c
    INNER JOIN compras_detalle cd 
        ON cd.compra_id = c.id
    INNER JOIN insumo i 
        ON i.id = cd.insumo_id
    WHERE c.id = (
        SELECT id 
        FROM compras 
        ORDER BY id DESC 
        LIMIT 1
    )";

    $stmt = $db->query($sql);

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($compras as &$c) {

        $cantidadBase = $c['cantidad'];

        if ($c['unidad_medida'] != 'un') {
            $cantidadBase = self::convertirAGramos($c['cantidad'], $c['unidad_medida']);
        }

        $c['rendimientos'] = self::calcularRendimiento(
            $c['insumo_id'],
            $cantidadBase
        );
    }

    return $compras;
}
}

