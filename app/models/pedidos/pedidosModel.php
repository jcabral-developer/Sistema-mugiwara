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

            return $platos;

        } catch (PDOException $e) {
            error_log("Error en obtenerPlatos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerVentas($filtro = 'HOY')
    {
        try {
            // Base de la consulta
            $sql = "SELECT 
                    TIME(venta.fecha) AS hora,
                    DATE_FORMAT(venta.fecha, '%d/%m/%Y %H:%i') AS fecha,
                    venta.cliente_nombre,
                    GROUP_CONCAT(
                        CONCAT(venta_detalle.cantidad, 'x ', plato.descripcion)
                        SEPARATOR ', '
                    ) AS productos,
                    venta.metodo_pago,
                    venta.total,
                    venta.delivery,
                    venta.telefono,
                    venta.cliente_nombre,
                    venta.observaciones,
                    SUM(plato.ganancia * venta_detalle.cantidad) AS ganancia,
                    venta.direccion
                FROM venta
                JOIN venta_detalle ON venta.id = venta_detalle.venta_id
                JOIN plato ON plato.id = venta_detalle.plato_id";

            // Si el filtro es HOY, limitamos a la fecha actual
            if ($filtro === 'HOY') {
                $sql .= " WHERE DATE(venta.fecha) = CURDATE()";
            }

            // Cerramos con el agrupamiento y orden
            $sql .= " GROUP BY venta.id ORDER BY venta.fecha DESC";

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

            $sqlDetalle = "INSERT INTO venta_detalle
                       (venta_id, plato_id, cantidad, precio_unitario, subtotal)
                       VALUES (:venta, :plato, :cant, :precio, :subtotal)";

            $stmtDetalle = $this->db->prepare($sqlDetalle);

            foreach ($data['items'] as $item) {

                $subtotal = $item['precio'] * $item['cant'];

                $stmtDetalle->execute([
                    ':venta' => $venta_id,
                    ':plato' => $item['id'],
                    ':cant' => $item['cant'],
                    ':precio' => $item['precio'],
                    ':subtotal' => $subtotal
                ]);
            }

            // Descontar stock a los inusmos usados en los platos

            $sqlRend = "SELECT insumo, cantidad_usada,rendimiento 
            FROM rendimiento 
            WHERE plato = :plato_id";

            $stmtRend = $this->db->prepare($sqlRend);


            foreach ($data['items'] as $item) {

                $stmtRend->execute([
                    ':plato_id' => $item['id']
                ]);

                $insumos = $stmtRend->fetchAll(PDO::FETCH_ASSOC);

                foreach ($insumos as $insumo) {

                    $cantidad = $insumo['cantidad_usada'] / $insumo['rendimiento'];
                    $cantidadDescontar = $cantidad * $item['cant'];

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

            $this->db->commit();
            return true;

        } catch (Exception $e) {

            $this->db->rollBack();
            error_log("Error venta: " . $e->getMessage());
            return false;
        }
    }
}