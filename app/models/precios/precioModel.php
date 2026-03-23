<?php


class PrecioModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function obtenerPlatos()
    {
        try {
            $sql = "SELECT id, descripcion, costo_receta, margen, precio_venta, ganancia,imagen
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

    public function obtenerImpactoPlato($plato_id)
    {
        try {
            $sql = "SELECT 
            ip.impacto,
            ip.tipo,
            i.descripcion AS insumo
        FROM impacto_plato ip
        JOIN insumo i ON i.id = ip.insumo_id
        WHERE ip.plato_id = :plato
        ORDER BY ip.fecha DESC
        LIMIT 5";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':plato', $plato_id);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$resultados) {
                return "";
            }

            $total = 0;
            $mensajes = [];
            // generamos el mensaje de reporte
            foreach ($resultados as $r) {

                $impacto = $r['impacto'];
                $total += $impacto;

                if ($impacto > 0) {
                    $mensajes[] = "* subió $" . round($impacto, 2) . " por " . $r['insumo'] . "<br>";
                } else {
                    $mensajes[] = "* bajó $" . round(abs($impacto), 2) . " por " . $r['insumo'] . "<br>";
                }
            }

            $texto = implode("", $mensajes);

            if ($total > 0) {
                $total_txt = "Total aumento: $" . round($total, 2) . "<br>";
            } else {
                $total_txt = "Total baja: $" . round(abs($total), 2) . "<br>";
            }

            return $texto . " => " . $total_txt;

        } catch (PDOException $e) {
            error_log("Error impacto plato: " . $e->getMessage());
            return "";
        }
    }

    public function obtenerIngredientesPlato($plato_id)
    {
        try {

            $sql = "SELECT 
                insumo.descripcion,
            (rendimiento.cantidad_usada / rendimiento.rendimiento) * insumo.precio_unitario as parcial, (rendimiento.cantidad_usada / rendimiento.rendimiento) as cantidad
                FROM rendimiento
                INNER JOIN insumo
                ON rendimiento.insumo = insumo.id
                WHERE rendimiento.plato = :plato";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':plato' => $plato_id
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerIngredientesPlato: " . $e->getMessage());
            return [];
        }
    }


    public function guardarPrecioPlato($plato, $costo, $margen, $precio, $ganan)
    {

        $plato = (int) $plato;

        if (!is_numeric($costo) || !is_numeric($margen) || !is_numeric($precio)) {
            return false;
        }

        try {

            $sql = "UPDATE plato 
            SET costo_receta = :costo,
                margen = :margen,
                precio_venta = :precio,
                ganancia = :ganancia
            WHERE id = :plato";

            $stmt = $this->db->prepare($sql);

            $ok = $stmt->execute([
                ":costo" => $costo,
                ":margen" => $margen,
                ":precio" => $precio,
                ":ganancia" => $ganan,
                ":plato" => $plato

            ]);

            return $ok;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }


    public function actualizarRutaImagen($id, $nombreArchivo)
    {
        try {
            $sql = "UPDATE plato SET imagen = ? WHERE id = ?"; // Verifica si tu columna es 'imagen' o 'ruta_imagen'
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombreArchivo, $id]);
        } catch (Exception $e) {
            error_log("Error en Modelo: " . $e->getMessage());
            return false;
        }
    }


}