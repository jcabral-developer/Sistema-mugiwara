<?php


class PromoModel
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

            return $platos;

        } catch (PDOException $e) {
            error_log("Error en obtenerPlatos: " . $e->getMessage());
            return [];
        }
    }


    public function guardarPromo($data)
    {
        try {
            $this->db->beginTransaction();


            $costoTotal = 0;

            foreach ($data['productos'] as $prod) {

                $sqlCosto = "SELECT costo_receta FROM plato WHERE id = ?";
                $stmtCosto = $this->db->prepare($sqlCosto);
                $stmtCosto->execute([$prod['id']]);

                $costoPlato = $stmtCosto->fetchColumn();

                $costoTotal += $costoPlato * $prod['cantidad'];
            }

            // 💰 GANANCIA DE LA PROMO
            $gananciaPromo = $data['precio_total'] - $costoTotal;


            // === INSERT ENCABEZADO (Agregamos el campo imagen) ===
            $sql = "INSERT INTO promo 
            (nombre, tipo, valor, cantidad_x, cantidad_y, precio_total, ganancia_promo, imagen) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);

            


            $stmt->execute([
                $data['nombre'],
                $data['tipo'],
                $data['valor'] ?? null,
                $data['cantidad_x'] ?? null,
                $data['cantidad_y'] ?? null,
                $data['precio_total'],
                $gananciaPromo, // 👈 ahora sí
                $data['imagen_nombre']
            ]);
            $promoId = $this->db->lastInsertId();

            // === INSERT DETALLE ===
            $sqlDetalle = "INSERT INTO promo_detalle (promo_id, plato_id, cantidad) VALUES (?, ?, ?)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            foreach ($data['productos'] as $prod) {
                $stmtDetalle->execute([
                    $promoId,
                    $prod['id'],
                    $prod['cantidad']
                ]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error guardarPromo: " . $e->getMessage());
            return false;
        }


    }

    public function actualizarPromo($data)
    {
        try {
            $this->db->beginTransaction();

            // === UPDATE ENCABEZADO ===
            $sql = "UPDATE promo 
                SET nombre = ?, 
                    tipo = ?, 
                    valor = ?, 
                    cantidad_x = ?, 
                    cantidad_y = ?, 
                    precio_total = ?, 
                    ganancia_promo = ?, 
                    imagen = ?
                WHERE id = ?";

            $stmt = $this->db->prepare($sql);

            ////////////////////////calculo de la ganancia////////////////////////////
            $costoTotal = 0;

            foreach ($data['productos'] as $prod) {

                $sqlCosto = "SELECT costo_receta FROM plato WHERE id = ?";
                $stmtCosto = $this->db->prepare($sqlCosto);
                $stmtCosto->execute([$prod['id']]);

                $costoPlato = $stmtCosto->fetchColumn();

                $costoTotal += $costoPlato * $prod['cantidad'];
            }

            // 💰 GANANCIA DE LA PROMO
            $gananciaPromo = $data['precio_total'] - $costoTotal;
            //////////////////////////////////////////////////////

            $stmt->execute([
                $data['nombre'],
                $data['tipo'],
                $data['valor'] ?? null,
                $data['cantidad_x'] ?? null,
                $data['cantidad_y'] ?? null,
                $data['precio_total'],
                $gananciaPromo,
                $data['imagen_nombre'] ?? null,
                $data['id']
            ]);

            // 🔥 IMPORTANTE: borrar detalle anterior
            $sqlDelete = "DELETE FROM promo_detalle WHERE promo_id = ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([$data['id']]);

            // === INSERT DETALLE NUEVO ===
            $sqlDetalle = "INSERT INTO promo_detalle (promo_id, plato_id, cantidad) VALUES (?, ?, ?)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            foreach ($data['productos'] as $prod) {
                $stmtDetalle->execute([
                    $data['id'],
                    $prod['id'],
                    $prod['cantidad']
                ]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error actualizarPromo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPromos()
    {
        try {
            $sql = "SELECT 
                    p.id,
                    p.nombre,
                    p.tipo,
                    p.valor,
                    p.precio_total,
                    p.imagen,
                    pd.plato_id,
                    pd.cantidad,
                    pl.descripcion AS nombre_plato,
                    pl.precio_venta
                FROM promo p
                INNER JOIN promo_detalle pd ON p.id = pd.promo_id
                INNER JOIN plato pl ON pl.id = pd.plato_id
                WHERE p.estado = 1
                ORDER BY p.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $promos = [];

            foreach ($resultados as $row) {
                $id = $row['id'];

                if (!isset($promos[$id])) {
                    $promos[$id] = [
                        "id" => $row['id'],
                        "nombre" => $row['nombre'],
                        "tipo" => $row['tipo'],
                        "valor" => $row['valor'],
                        "precio_total" => $row['precio_total'],
                        "imagen" => $row['imagen'],
                        "precio" => $row['precio_venta'],
                        "productos" => []
                    ];
                }

                $promos[$id]["productos"][] = [
                    "id" => $row['plato_id'],
                    "nombre" => $row['nombre_plato'],
                    "cantidad" => $row['cantidad'],
                    "precio" => $row['precio_venta']
                ];
            }

            // Reindexar array
            return array_values($promos);

        } catch (Exception $e) {
            return [];
        }
    }


    public function eliminarPromo($id)
    {
        try {
            $sql = "UPDATE promo SET estado = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);

        } catch (Exception $e) {
            error_log("Error eliminarPromo: " . $e->getMessage());
            return false;
        }
    }

}
