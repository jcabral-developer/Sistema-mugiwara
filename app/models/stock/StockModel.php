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

        // ===============================
        // 1. BACKUP GLOBAL DE PLATOS (ANTES DE TODO)
        // ===============================
        $sqlCosto = "SELECT id, costo_receta,margen,precio_venta, ganancia FROM plato";
        $stmtCosto = $this->db->prepare($sqlCosto);
        $stmtCosto->execute();
        $costosAnteriores = $stmtCosto->fetchAll(PDO::FETCH_ASSOC);

        // Guardar backup general (tabla auxiliar tuya)
        $sqlBackupCosto = "INSERT INTO backup_precio_costo 
            (plato_id, costo_receta,margen,precio_venta, ganancia, fecha_actualizacion)
            VALUES (:id, :costo,:margen,:precio_venta, :ganancia, NOW())
            ON DUPLICATE KEY UPDATE 
                costo_receta = VALUES(costo_receta),
                 margen = VALUES(margen),
                  precio_venta = VALUES(precio_venta),
                ganancia = VALUES(ganancia),
                fecha_actualizacion = NOW()";

        $stmtBackupCosto = $this->db->prepare($sqlBackupCosto);

        foreach ($costosAnteriores as $plato) {
            $stmtBackupCosto->execute([
                ':id' => $plato['id'],
                ':costo' => $plato['costo_receta'],
                 ':margen' => $plato['margen'],
                  ':precio_venta' => $plato['precio_venta'],
                ':ganancia' => $plato['ganancia']
            ]);
        }

        // ===============================
        // 2. INSERT COMPRA
        // ===============================
        $sqlCompra = "INSERT INTO compras (fecha, comprador, total)
                      VALUES (:fecha, :comprador, :total)";
        $stmtCompra = $this->db->prepare($sqlCompra);
        $stmtCompra->execute([
            ':fecha' => $data['fecha'],
            ':comprador' => $data['comprador'],
            ':total' => $data['total']
        ]);

        $compra_id = $this->db->lastInsertId();

        // ===============================
        // 3. CREAR BACKUP
        // ===============================
        $sqlBackup = "INSERT INTO compra_backup (compra_id, fecha)
                      VALUES (:compra, NOW())";
        $stmtBackup = $this->db->prepare($sqlBackup);
        $stmtBackup->execute([
            ':compra' => $compra_id
        ]);

        $backup_id = $this->db->lastInsertId();

        // ===============================
        // 4. BACKUP GLOBAL DE PLATOS
        // ===============================
        foreach ($costosAnteriores as $plato) {

            $sqlBackupPlato = "INSERT INTO compra_backup_detalle
                (backup_id, tipo, entidad_id, costo_receta_anterior,margen,precio_venta, ganancia)
                VALUES (:backup, 'plato', :id, :costo,:margen, :precio_venta,:ganancia)";

            $stmt = $this->db->prepare($sqlBackupPlato);
            $stmt->execute([
                ':backup' => $backup_id,
                ':id' => $plato['id'],
                ':costo' => $plato['costo_receta'],
                ':margen' => $plato['margen'],
                 ':precio_venta' => $plato['precio_venta'],
                ':ganancia' => $plato['ganancia']
            ]);
        }

        // ===============================
        // 5. PROCESAR ITEMS
        // ===============================
        foreach ($data['items'] as $item) {

            // ===============================
            // 5.1 BACKUP INSUMO
            // ===============================
            $sqlEstado = "SELECT stock, precio_unitario
                          FROM insumo
                          WHERE id = :id";

            $stmtEstado = $this->db->prepare($sqlEstado);
            $stmtEstado->execute([':id' => $item['id']]);
            $estado = $stmtEstado->fetch(PDO::FETCH_ASSOC);

            $sqlGuardarEstado = "INSERT INTO compra_backup_detalle
                (backup_id, tipo, entidad_id, stock_anterior, precio_unitario_anterior)
                VALUES (:backup, 'insumo', :id, :stock, :precio)";

            $stmt = $this->db->prepare($sqlGuardarEstado);
            $stmt->execute([
                ':backup' => $backup_id,
                ':id' => $item['id'],
                ':stock' => $estado['stock'],
                ':precio' => $estado['precio_unitario']
            ]);

            // ===============================
            // 5.2 INSERT DETALLE COMPRA
            // ===============================
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

            // ===============================
            // 5.3 CONVERTIR UNIDADES
            // ===============================
            if ($item['unidad'] != 'un') {
                $cantidadBase = self::convertirAGramos($item['cantidad'], $item['unidad']);
            } else {
                $cantidadBase = $item['cantidad'];
            }

            // ===============================
            // 5.4 ACTUALIZAR STOCK
            // ===============================
            $sqlStock = "UPDATE insumo
                         SET stock = stock + :cantidad
                         WHERE id = :id";

            $stmt = $this->db->prepare($sqlStock);
            $stmt->execute([
                ':cantidad' => $cantidadBase,
                ':id' => $item['id']
            ]);

            // ===============================
            // 5.5 ACTUALIZAR PRECIO INSUMO
            // ===============================
            $precioUnitario = $item['precio'] / $cantidadBase;

            $sqlPrecio = "UPDATE insumo
                          SET precio_unitario = :precio
                          WHERE id = :id";

            $stmt = $this->db->prepare($sqlPrecio);
            $stmt->execute([
                ':precio' => $precioUnitario,
                ':id' => $item['id']
            ]);

            // ===============================
            // 5.6 RECALCULAR COSTO PLATOS
            // ===============================
            $sqlPrecioPlato = "UPDATE plato
                SET costo_receta = (
                    SELECT SUM(
                        (rendimiento.cantidad_usada / rendimiento.rendimiento) 
                        * insumo.precio_unitario
                    )
                    FROM rendimiento
                    INNER JOIN insumo ON rendimiento.insumo = insumo.id
                    WHERE rendimiento.plato = plato.id
                )
                WHERE plato.id IN (
                    SELECT rendimiento.plato
                    FROM rendimiento    
                    WHERE rendimiento.insumo = :insumo
                )";

            $stmt = $this->db->prepare($sqlPrecioPlato);
            $stmt->execute([
                ':insumo' => $item['id']
            ]);
        }

        // ===============================
        // 6. COMMIT
        // ===============================
        $this->db->commit();

        // ===============================
        // 7. ACTUALIZAR PRECIOS FINALES
        // ===============================
        $this->actualizarTablaPrecios($costosAnteriores);

        return true;

    } catch (Exception $e) {

        $this->db->rollBack();
        error_log("Error guardarCompraCompleta: " . $e->getMessage());
        return false;
    }
}

    public function actualizarTablaPrecios($costoPlatos)
    {
        foreach ($costoPlatos as $platoAnterior) {
            // Buscamos el costo NUEVO que ya se impactó en la BD en el paso anterior
            $sqlNuevo = "SELECT costo_receta, margen FROM plato WHERE id = :id";
            $stmt = $this->db->prepare($sqlNuevo);
            $stmt->execute([':id' => $platoAnterior['id']]);
            $platoNuevo = $stmt->fetch(PDO::FETCH_ASSOC);

            // VALIDACIÓN CLAVE: ¿El costo nuevo es mayor al que teníamos guardado?
            if ($platoNuevo['costo_receta'] > $platoAnterior['costo_receta']) {


               // $precio_venta = ceil($platoNuevo['costo_receta'] + ($platoNuevo['costo_receta'] * ($platoNuevo['margen'] / 100 )));
                $ganancia = ceil($platoNuevo['costo_receta'] + ($platoNuevo['costo_receta'] * ($platoNuevo['margen'] / 100))) - $platoNuevo['costo_receta'];

                $sqlActualizar = "UPDATE plato 
                              SET 
                                  ganancia = $ganancia
                              WHERE id = :id";
                    //antes se hacia precio_venta = $precio_venta, por las dudas lo dejo en comentario por si llega a fallar en el futuro.
                $stmtAct = $this->db->prepare($sqlActualizar);
                $stmtAct->execute([':id' => $platoAnterior['id']]);
            } else if ($platoNuevo['costo_receta'] < $platoAnterior['costo_receta']) {

                if($platoNuevo['margen'] == 0 ){
                     $sqlActualizar = "UPDATE plato 
                SET 
                    ganancia = 0,
                    margen = ((precio_venta - costo_receta) / costo_receta) * 100
                WHERE id = :id";

                }else{
                $sqlActualizar = "UPDATE plato 
                SET 
                    ganancia = precio_venta - costo_receta,
                    margen = ((precio_venta - costo_receta) / costo_receta) * 100
                WHERE id = :id";

                $stmtAct = $this->db->prepare($sqlActualizar);
                $stmtAct->execute([':id' => $platoAnterior['id']]);
                }
            }

            //  CASO 3: IGUAL → no hacer nada

        }
    }


    private static function convertirAGramos($cantidad, $unidad)
    {
        $unidad = strtolower(trim($unidad));

        // --- PESO ---
        if ($unidad == 'kg' || $unidad == 'kilo' || $unidad == 'kilos') {
            return $cantidad * 1000; // 1 kg = 1000 gr
        }

        if ($unidad == 'gr' || $unidad == 'gramo' || $unidad == 'gramos') {
            return $cantidad; // ya está en gramos
        }

        // --- VOLUMEN ---
        if ($unidad == 'lt' || $unidad == 'l' || $unidad == 'litro' || $unidad == 'litros') {
            return $cantidad * 1000; // 1 litro = 1000 ml
        }

        if ($unidad == 'ml' || $unidad == 'mililitro' || $unidad == 'mililitros') {
            return $cantidad; // ya está en mililitros
        }

        throw new Exception("Unidad de medida no válida: " . $unidad);
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
            $volumen = ['ml', 'lt'];

            if (!in_array(strtolower($insumo['unidad_medida']), $excepciones)) {
                // formatea gramos/kilos
                if (in_array(strtolower($insumo['unidad_medida']), $volumen)) {
                    $insumo['stock_formateado'] = self::mostrarPeso($insumo['stock'], $insumo['unidad_medida']);
                } else {
                    $insumo['stock_formateado'] = self::mostrarPeso($insumo['stock']);
                }
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
        try {

            $this->db->beginTransaction();

            // =============================
            // OBTENER FECHA DE LA COMPRA
            // =============================

            $sqlFecha = "SELECT fecha FROM compras WHERE id = :id";
            $stmtFecha = $this->db->prepare($sqlFecha);
            $stmtFecha->execute([':id' => $compra_id]);

            $compra = $stmtFecha->fetch(PDO::FETCH_ASSOC);

            if (!$compra) {
                throw new Exception("La compra no existe.");
            }

            $fechaCompra = date('Y-m-d', strtotime($compra['fecha']));
            $hoy = date('Y-m-d');

            // =============================
            // SI ES EL MISMO DIA RESTAURAR
            // =============================

            if ($fechaCompra === $hoy) {

                // buscar backup
                $sqlBackup = "SELECT id FROM compra_backup WHERE compra_id = :compra";
                $stmtBackup = $this->db->prepare($sqlBackup);
                $stmtBackup->execute([':compra' => $compra_id]);

                $backup = $stmtBackup->fetch(PDO::FETCH_ASSOC);

                if ($backup) {

                    $backup_id = $backup['id'];

                    $sqlDatos = "SELECT * FROM compra_backup_detalle WHERE backup_id = :backup";
                    $stmtDatos = $this->db->prepare($sqlDatos);
                    $stmtDatos->execute([':backup' => $backup_id]);

                    $registros = $stmtDatos->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($registros as $r) {

                        // RESTAURAR INSUMOS
                        if ($r['tipo'] === 'insumo') {

                            $sqlRestore = "UPDATE insumo
                        SET stock = :stock,
                        precio_unitario = :precio
                        WHERE id = :id";

                            $stmt = $this->db->prepare($sqlRestore);
                            $stmt->execute([
                                ':stock' => $r['stock_anterior'],
                                ':precio' => $r['precio_unitario_anterior'],
                                ':id' => $r['entidad_id']
                            ]);
                        }

                        // RESTAURAR PLATOS
                        if ($r['tipo'] === 'plato') {

                            $sqlRestore = "UPDATE plato
                        SET costo_receta = :costo,
                        ganancia = :ganancia,
                        precio_venta = :precio_venta
                        WHERE id = :id";

                            $stmt = $this->db->prepare($sqlRestore);
                            $stmt->execute([
                                ':costo' => $r['costo_receta_anterior'],
                                 ':precio_venta' => $r['precio_venta'],
                                ':ganancia' => $r['ganancia'],
                                ':id' => $r['entidad_id']
                            ]);
                        }

                    }

                }

            }

            // =============================
            // BORRAR DETALLE
            // =============================

            $sqlDetalle = "DELETE FROM compras_detalle WHERE compra_id = :id";
            $stmtDetalle = $this->db->prepare($sqlDetalle);
            $stmtDetalle->execute([':id' => $compra_id]);

            // =============================
            // BORRAR COMPRA
            // =============================

            $sqlCompra = "DELETE FROM compras WHERE id = :id";
            $stmtCompra = $this->db->prepare($sqlCompra);
            $stmtCompra->execute([':id' => $compra_id]);

            if ($stmtCompra->rowCount() === 0) {
                throw new Exception("La compra no existe o ya fue eliminada.");
            }

            $this->db->commit();

            // =============================
            // RECALCULAR PRECIOS
            // =============================

           // $this->actualizarTablaPrecios();

        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;

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

        // $excepciones = ['un', 'unidad', 'Un'];

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

 public function descontarStock($data)
{
    try {

        $this->db->beginTransaction();

        $insumoId = $data['id'];
        $entero = $data['entero'];
        $decimal = $data['decimal'];
        $motivo = $data['motivo'];
        $notas = $data['notas'];

        //  OBTENER UNIDAD DEL INSUMO
        $sql = "SELECT stock, unidad_medida FROM insumo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $insumoId]);

        $insumo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$insumo) {
            throw new Exception("Insumo no encontrado");
        }

        $unidad = $insumo['unidad_medida'];
        $stockActual = $insumo['stock'];

        // CONVERTIR A UNIDAD BASE
        if ($unidad == 'kg' || $unidad == 'gr') {
            // base = gramos
            $cantidadBase = ($entero * 1000) + $decimal;
        } 
        elseif ($unidad == 'lt' || $unidad == 'ml') {
            // base = mililitros
            $cantidadBase = ($entero * 1000) + $decimal;
        } 
        elseif ($unidad == 'un') {
            $cantidadBase = $entero;
        } 
        else {
            throw new Exception("Unidad no soportada");
        }

        //  VALIDAR
        if ($cantidadBase <= 0) {
            throw new Exception("Cantidad inválida");
        }

        if ($stockActual < $cantidadBase) {
            throw new Exception("No hay stock suficiente");
        }

        //  INSERT MOVIMIENTO
        $sqlMov = "INSERT INTO movimiento_stock 
                   (insumo_id, cantidad, motivo, fecha) 
                   VALUES (:id, :cantidad, :motivo, NOW())";

        $stmt = $this->db->prepare($sqlMov);
        $stmt->execute([
            ':id' => $insumoId,
            ':cantidad' => -$cantidadBase,
            ':motivo' => $motivo . " - " . $notas
        ]);

        //  ACTUALIZAR STOCK
        $sqlUpdate = "UPDATE insumo 
                      SET stock = stock - :cantidad 
                      WHERE id = :id";

        $stmt = $this->db->prepare($sqlUpdate);
        $stmt->execute([
            ':cantidad' => $cantidadBase,
            ':id' => $insumoId
        ]);

        $this->db->commit();

    } catch (Exception $e) {
        $this->db->rollBack();
        throw $e;
    }
}
}

