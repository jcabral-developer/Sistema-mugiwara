<?php


class PedidoModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }




    public function obtenerPlatos()
    {
        try {
            $sql = "SELECT id, descripcion, precio_venta, ganancia,imagen
                FROM plato";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $platos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // foreach ($platos as &$plato) {
            //     $plato['reporte'] = $this->obtenerImpactoPlato($plato['id']);
            // }
            $sql2 = "SELECT 
                    p.id AS promo_id,
                    p.nombre AS promo_nombre,
                    p.tipo,
                    p.valor,
                    p.cantidad_x,
                    p.cantidad_y,
                    p.precio_total,
                    p.imagen AS promoImagen,
                    pd.plato_id,
                    pd.cantidad,
                    pl.descripcion AS nombre_plato,
                    pl.precio_venta
                    FROM promo p
                    INNER JOIN promo_detalle pd ON p.id = pd.promo_id
                    INNER JOIN plato pl ON pl.id = pd.plato_id
                    WHERE p.estado = 1
                    ORDER BY p.id DESC;";

            $stmt = $this->db->prepare($sql2);
            $stmt->execute();
            $promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $promosAgrupadas = [];

            foreach ($promos as $p) {
                $id = $p['promo_id'];

                if (!isset($promosAgrupadas[$id])) {
                    $promosAgrupadas[$id] = [
                        "id" => "promo_" . $id,
                        "descripcion" => $p['promo_nombre'],
                        "precio_venta" => $p['precio_total'],
                        "imagen" => $p['promoImagen'],
                        "esPromo" => true
                    ];
                }
            }

            $promosFormateadas = array_values($promosAgrupadas);

            $todo = array_merge($platos, $promosFormateadas);

            return $todo;
        } catch (PDOException $e) {
            error_log("Error en obtenerPlatos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerVentas($filtro = 'HOY')
    {
        try {

            $sql = "SELECT 
            TIME(v.fecha) AS hora,
            DATE_FORMAT(v.fecha, '%d/%m/%Y %H:%i') AS fecha,
            v.cliente_nombre,

            GROUP_CONCAT(
                CONCAT(
                    vd.cantidad, 'x ',
                    CASE 
                        WHEN vd.promo_id IS NOT NULL THEN pr.nombre
                        ELSE pl.descripcion
                    END
                )
                SEPARATOR ', '
            ) AS productos,

            v.metodo_pago,
            v.total,
            v.delivery,
            v.telefono,
            v.observaciones,
            v.direccion,

            SUM(vd.ganancia) AS ganancia

        FROM venta v

        JOIN venta_detalle vd ON v.id = vd.venta_id

        LEFT JOIN plato pl ON pl.id = vd.plato_id
        LEFT JOIN promo pr ON pr.id = vd.promo_id";

            // =========================
            // FILTROS
            // =========================

            if ($filtro === 'HOY') {
                $sql .= " WHERE DATE(v.fecha) = CURDATE()";
            } elseif ($filtro === 'SEMANA') {
                $sql .= " WHERE YEARWEEK(v.fecha, 1) = YEARWEEK(CURDATE(), 1)";
            } elseif ($filtro === 'MES') {
                $sql .= " WHERE MONTH(v.fecha) = MONTH(CURDATE())
                      AND YEAR(v.fecha) = YEAR(CURDATE())";
            }

            // =========================
            // GROUP Y ORDER
            // =========================

            $sql .= " GROUP BY v.id ORDER BY v.fecha DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerVentas: " . $e->getMessage());
            return [];
        }
    }

    public function guardarVenta($data)
    {
        try {

            $this->db->beginTransaction();

            // === INSERT VENTA ===
            $sql = "INSERT INTO venta 
        (cliente_nombre, telefono, direccion, observaciones, metodo_pago, total, fecha, subtotal, delivery)
        VALUES (:nombre, :tel, :dir, :obs, :metodo, :total, NOW(), :subtotal, :delivery)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':nombre' => $data['cliente']['nombre'],
                ':tel' => $data['cliente']['telefono'],
                ':dir' => $data['cliente']['direccion'],
                ':obs' => $data['cliente']['observaciones'],
                ':metodo' => $data['metodo_pago'],
                ':total' => $data['total'],
                ':subtotal' => $data['subtotal'],
                ':delivery' => $data['delivery'],
            ]);

            $venta_id = $this->db->lastInsertId();

            // === INSERT DETALLE ===
            $sqlDetalle = "INSERT INTO venta_detalle
        (venta_id, plato_id, promo_id, cantidad, precio_unitario, subtotal, ganancia)
        VALUES (:venta, :plato, :promo_id, :cant, :precio, :subtotal, :ganancia)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            // === RENDIMIENTO ===
            $sqlRend = "SELECT insumo, cantidad_usada, rendimiento 
                    FROM rendimiento 
                    WHERE plato = :plato_id";
            $stmtRend = $this->db->prepare($sqlRend);

            foreach ($data['items'] as $item) {
// =====================================================
                // 🎁 PROMO (CON INTEGRACIÓN DE INSUMOS ESPECIALES / EXTRAS)
                // =====================================================
                if (strpos($item['id'], 'promo_') === 0) {

                    $promoId = str_replace('promo_', '', $item['id']);
                    $cantidadPromo = $item['cant'];

                    // Obtener datos base de la promo
                    $sqlPromoData = "SELECT precio_total, ganancia_promo FROM promo WHERE id = ?";
                    $stmtPromoData = $this->db->prepare($sqlPromoData);
                    $stmtPromoData->execute([$promoId]);
                    $promoData = $stmtPromoData->fetch(PDO::FETCH_ASSOC);

                    if (!$promoData) {
                        throw new Exception("Promo no encontrada ID: " . $promoId);
                    }

                    $precioPromo = $promoData['precio_total'];
                    $gananciaPromoUnit = $promoData['ganancia_promo'];
                    
                    // Inicializamos la ganancia total de la promo
                    $gananciaTotalPromo = $gananciaPromoUnit * $cantidadPromo;

                    // 👉 obtener platos de la promo para el stock base posterior
                    $sqlPromo = "SELECT pd.plato_id, pd.cantidad FROM promo_detalle pd WHERE pd.promo_id = ?;";
                    $stmtPromo = $this->db->prepare($sqlPromo);
                    $stmtPromo->execute([$promoId]);
                    $productosPromo = $stmtPromo->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($productosPromo)) {
                        throw new Exception("Promo sin productos");
                    }

                    // =====================================================
                    // ⭐ PROCESAR EXTRAS/INGREDIENTES ESPECIALES DENTRO DE LA PROMO
                    // =====================================================
                    if (!empty($item['extras'])) {
                        
                        // Detectamos si el ítem de la promo se configuró como MITAD
                        $esMitad = (stripos($item['nombre'], 'MITAD') !== false);

                        foreach ($item['extras'] as $extra) {
                            $idExtra = $extra['id'];
                            if (empty($idExtra)) {
                                continue;
                            }

                            // Gramos que se mandan desde el HTML
                            $gramosEnviados = isset($extra['cantidad']) ? floatval($extra['cantidad']) : 0;

                            // Si es mitad y mitad, dividimos por 2; si no, va entero. Multiplicado por la cantidad de promos
                            if ($esMitad) {
                                $cantidadStockExtraDescontar = ($gramosEnviados * floatval($cantidadPromo)) / 2;
                            } else {
                                $cantidadStockExtraDescontar = $gramosEnviados * floatval($cantidadPromo);
                            }

                            // A) DESCONTAMOS EL STOCK DEL INGREDIENTE ESPECIAL
                            $sqlStockExtra = "UPDATE insumo SET stock = stock - :cantidad WHERE id = :id";
                            $stmtStockExtra = $this->db->prepare($sqlStockExtra);
                            $stmtStockExtra->execute([
                                ':cantidad' => $cantidadStockExtraDescontar,
                                ':id' => $idExtra
                            ]);

                            // B) CALCULAMOS EL COSTO REAL PARA RESTARLO DE LA GANANCIA DE LA PROMO
                            $sqlCostoInsumo = "SELECT precio_unitario FROM insumo WHERE id = ?";
                            $stmtCostoInsumo = $this->db->prepare($sqlCostoInsumo);
                            $stmtCostoInsumo->execute([$idExtra]);
                            $costoRealInsumo = floatval($stmtCostoInsumo->fetchColumn());

                            $costoTotalDelExtra = $costoRealInsumo * $cantidadStockExtraDescontar;
                            
                            // Restamos el costo de los extras a la ganancia de la promo
                            $gananciaTotalPromo -= $costoTotalDelExtra;
                        }
                    }

                    // 👉 INSERTAR DETALLE DE LA PROMO (Con la ganancia real digerida)
                    $stmtDetalle->execute([
                        ':venta' => $venta_id,
                        ':plato' => null,
                        ':promo_id' => $promoId,
                        ':cant' => $cantidadPromo,
                        ':precio' => $precioPromo,
                        ':subtotal' => $precioPromo * $cantidadPromo,
                        ':ganancia' => $gananciaTotalPromo
                    ]);

                    // 👉 DESCONTAR STOCK BASE DE LOS PLATOS DE LA PROMO (Normal)
                    foreach ($productosPromo as $prod) {
                        $platoId = $prod['plato_id'];
                        $cantidadTotal = $prod['cantidad'] * $cantidadPromo;

                        $stmtRend->execute([':plato_id' => $platoId]);
                        $insumos = $stmtRend->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($insumos as $insumo) {
                            $cantidad = $insumo['cantidad_usada'] / $insumo['rendimiento'];
                            $cantidadDescontar = $cantidad * $cantidadTotal;

                            $sqlStock = "UPDATE insumo 
                                         SET stock = stock - :cantidad 
                                         WHERE id = :insumo_id";

                            $stmtStock = $this->db->prepare($sqlStock);
                            $stmtStock->execute([
                                ':cantidad' => $cantidadDescontar,
                                ':insumo_id' => $insumo['insumo']
                            ]);
                        }
                    }
           } else {
                    // =====================================================
                    // 🟢 PLATO NORMAL / ESPECIAL / MITAD
                    // =====================================================

                    $subtotal = $item['precio'] * $item['cant'];
                    $gananciaTotal = 0;
                    
                    // Detectamos si es mitad y mitad evaluando si el primer extra tiene la propiedad 'lado'
                    $tieneMitad = !empty($item['extras']) && isset($item['extras'][0]['lado']);

                    // =====================================================
                    // 🍕 CASO A: PIZZA MITAD Y MITAD
                    // =====================================================
                    if ($tieneMitad) {

                        // 1. Buscamos la ganancia de la pizza base (Muzzarella) usando su ID real
                        $sqlGanancia = "SELECT ganancia FROM plato WHERE id = ?";
                        $stmtGan = $this->db->prepare($sqlGanancia);
                        $stmtGan->execute([$item['id']]);
                        $gananciaUnit = $stmtGan->fetchColumn();
                        $gananciaTotal = $gananciaUnit * $item['cant'];

                        // Insertamos el detalle con el plato_id correcto de la Muzzarella
                        $stmtDetalle->execute([
                            ':venta' => $venta_id,
                            ':plato' => $item['id'], 
                            ':promo_id' => null,
                            ':cant' => $item['cant'],
                            ':precio' => $item['precio'], 
                            ':subtotal' => $subtotal,
                            ':ganancia' => $gananciaTotal
                        ]);

                        // Descontamos el stock base completo de la Muzzarella entera
                        $stmtRend->execute([':plato_id' => $item['id']]);
                        $insumosBase = $stmtRend->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($insumosBase as $insumo) {
                            $cantidad = $insumo['cantidad_usada'] / $insumo['rendimiento'];
                            $cantidadDescontar = $cantidad * $item['cant'];

                            $sqlStock = "UPDATE insumo SET stock = stock - :cantidad WHERE id = :insumo_id";
                            $stmtStock = $this->db->prepare($sqlStock);
                            $stmtStock->execute([
                                ':cantidad' => $cantidadDescontar,
                                ':insumo_id' => $insumo['insumo']
                            ]);
                        }

                        // 2. Procesamos las dos mitades especiales
                        foreach ($item['extras'] as $extra) {
                            $idExtra = $extra['id'];

                            if (empty($idExtra)) {
                                continue;
                            }

                            $gramosEnviados = isset($extra['cantidad']) ? floatval($extra['cantidad']) : 0;

                            // Dividimos por 2 los gramos enviados del HTML
                            $cantidadStockDescontar = ($gramosEnviados * floatval($item['cant'])) / 2;

                            // Descontamos el stock del ingrediente especial dividido por 2
                            $sqlStockExtra = "UPDATE insumo SET stock = stock - :cantidad WHERE id = :id";
                            $stmtStockExtra = $this->db->prepare($sqlStockExtra);
                            $stmtStockExtra->execute([
                                ':cantidad' => $cantidadStockDescontar,
                                ':id' => $idExtra
                            ]);

                            // Buscamos el costo de compra del insumo por gramo
                            $sqlCostoInsumo = "SELECT precio_unitario FROM insumo WHERE id = ?";
                            $stmtCostoInsumo = $this->db->prepare($sqlCostoInsumo);
                            $stmtCostoInsumo->execute([$idExtra]);
                            $costoRealInsumo = floatval($stmtCostoInsumo->fetchColumn());

                            // Calculamos el costo sobre la cantidad reducida
                            $costoTotalDelExtra = $costoRealInsumo * $cantidadStockDescontar;

                            // Restamos de la ganancia general
                            $gananciaTotal -= $costoTotalDelExtra;
                        }

                        // 3. Actualizamos la ganancia final procesada
                        $sqlUpdateGanancia = "UPDATE venta_detalle 
                                              SET ganancia = :ganancia 
                                              WHERE venta_id = :venta_id AND plato_id = :plato_id";
                        $stmtUpdateGan = $this->db->prepare($sqlUpdateGanancia);
                        $stmtUpdateGan->execute([
                            ':ganancia' => $gananciaTotal,
                            ':venta_id' => $venta_id,
                            ':plato_id' => $item['id']
                        ]);

                        // 🚨 CLAVE: Como ya procesamos los extras de la mitad acá adentro, 
                        // saltamos al siguiente elemento del carrito para que el bloque de abajo no los vuelva a sumar.
                        continue; 
                    }

                    // =====================================================
                    // 🍕 CASO B: PIZZA NORMAL O ESPECIAL ENTERA
                    // =====================================================
                    $sqlGanancia = "SELECT ganancia FROM plato WHERE id = ?";
                    $stmtGan = $this->db->prepare($sqlGanancia);
                    $stmtGan->execute([$item['id']]);

                    $gananciaUnit = $stmtGan->fetchColumn();
                    $gananciaTotal = $gananciaUnit * $item['cant'];

                    // INSERT DETALLE ENTERA
                    $stmtDetalle->execute([
                        ':venta' => $venta_id,
                        ':plato' => $item['id'],
                        ':promo_id' => null,
                        ':cant' => $item['cant'],
                        ':precio' => $item['precio'],
                        ':subtotal' => $subtotal,
                        ':ganancia' => $gananciaTotal
                    ]);

                    // STOCK BASE ENTERA
                    $stmtRend->execute([':plato_id' => $item['id']]);
                    $insumos = $stmtRend->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($insumos as $insumo) {
                        $cantidad = $insumo['cantidad_usada'] / $insumo['rendimiento'];
                        $cantidadDescontar = $cantidad * $item['cant'];

                        $sqlStock = "UPDATE insumo SET stock = stock - :cantidad WHERE id = :insumo_id";
                        $stmtStock = $this->db->prepare($sqlStock);
                        $stmtStock->execute([
                            ':cantidad' => $cantidadDescontar,
                            ':insumo_id' => $insumo['insumo']
                        ]);
                    }

                    // =====================================================
                    // ⭐ PROCESAR EXTRAS SI LA PIZZA ES ENTERA
                    // =====================================================
                    if (!empty($item['extras'])) {
                        foreach ($item['extras'] as $extra) {
                            $idExtra     = $extra['id'];
                            $gramosBase  = isset($extra['cantidad']) ? floatval($extra['cantidad']) : 0;
                            
                            if (empty($idExtra)) {
                                continue;
                            }

                            // Al ser entera, descuenta los gramos completos del extra
                            $cantidadStockDescontar = $gramosBase * floatval($item['cant']);

                            $sqlStockExtra = "UPDATE insumo SET stock = stock - :cantidad WHERE id = :id";
                            $stmtStockExtra = $this->db->prepare($sqlStockExtra);
                            $stmtStockExtra->execute([
                                ':cantidad' => $cantidadStockDescontar,
                                ':id' => $idExtra
                            ]);

                            $sqlCostoInsumo = "SELECT precio_unitario FROM insumo WHERE id = ?";
                            $stmtCostoInsumo = $this->db->prepare($sqlCostoInsumo);
                            $stmtCostoInsumo->execute([$idExtra]);
                            $costoRealInsumo = floatval($stmtCostoInsumo->fetchColumn());

                            $costoTotalDelExtra = $costoRealInsumo * $cantidadStockDescontar;
                            $gananciaTotal -= $costoTotalDelExtra;
                        }

                        // Actualizar ganancia final de la pizza entera con extras
                        $sqlUpdateGanancia = "UPDATE venta_detalle 
                                              SET ganancia = :ganancia 
                                              WHERE venta_id = :venta_id AND plato_id = :plato_id";
                        $stmtUpdateGan = $this->db->prepare($sqlUpdateGanancia);
                        $stmtUpdateGan->execute([
                            ':ganancia' => $gananciaTotal,
                            ':venta_id' => $venta_id,
                            ':plato_id' => $item['id']
                        ]);
                    }
                } // Fin del bloque general else
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {

            $this->db->rollBack();

            //  MOSTRAR ERROR REAL
            throw new Exception("ERROR SQL: " . $e->getMessage());
        }
    }

    public static function obtenerIngredientesEspeciales()
    {
        try {


            $db = Database::connect();

            $sql = "SELECT 
    extra_detalle.id,
    extra_detalle.plato_id,
    plato.descripcion AS plato,
    extra_detalle.insumo_id,
    insumo.descripcion AS insumo,
    insumo.precio_unitario,
    extra_detalle.cantidad,
    extra_detalle.unidad,

    (extra_detalle.cantidad * insumo.precio_unitario) AS precio_extra

FROM extra_detalle

INNER JOIN plato
    ON extra_detalle.plato_id = plato.id

INNER JOIN insumo
    ON extra_detalle.insumo_id = insumo.id

ORDER BY plato.descripcion ASC, insumo.descripcion ASC";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Error obtenerIngredientesEspeciales: " . $e->getMessage());

            return [];
        }
    }
}
