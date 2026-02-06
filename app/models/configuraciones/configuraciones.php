<?php

/*indice del codigo:

    1. Registros.



    99.existe tal articulo, logs.

*/

class Configuraciones
{



    private $db;

    public function __construct()
    {

        $this->db = Database::connect();

    }

    /**
     * <1>. Registra un insumo si no existe
     * @throws Exception
     */
    public function crearInsumo(string $nombre): void
    {
        // Normalizar (clave para evitar duplicados raros)
        $nombre = mb_strtolower(trim($nombre));

        // Verificar si ya existe
        if ($this->existeInsumo($nombre)) {
            throw new Exception("El insumo ya existe en el sistema.");
        }

        // Insertar
        $sql = "INSERT INTO insumo (descripcion) VALUES (:nombre)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt->execute([':nombre' => $nombre])) {
            throw new Exception("No se pudo registrar el insumo.");
        }
    }


    public function crearPlato(string $nombre): void
    {
        // Normalizar (clave para evitar duplicados raros)
        $nombre = mb_strtolower(trim($nombre));

        // Verificar si ya existe
        if ($this->existePlato($nombre)) {
            throw new Exception("El plato ya existe en el sistema.");
        }

        // Insertar
        $sql = "INSERT INTO plato (descripcion) VALUES (:nombre)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt->execute([':nombre' => $nombre])) {
            throw new Exception("No se pudo registrar el plato.");
        }
    }


    public function crearRendimiento(
        int $platoId,
        int $insumoId,
        int $cantidad,
        string $unidad,
        int $rendimiento
    ): void {

        //  Validaciones básicas de seguridad
        if ($platoId <= 0 || $insumoId <= 0) {
            throw new Exception("Producto o insumo inválido.");
        }

        if ($cantidad < 0 || $rendimiento < 0) {
            throw new Exception("La cantidad y el rendimiento deben ser mayores a cero.");
        }

        $unidad = trim(mb_strtolower($unidad));

        //  Verificar si ya existe la regla
        $sql = "
        SELECT COUNT(*) 
        FROM rendimiento 
        WHERE plato = :plato 
          AND insumo = :insumo
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':plato' => $platoId,
            ':insumo' => $insumoId
        ]);

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Ya existe una regla de producción para este producto e insumo.");
        }

        //  Insertar
        $sql = "
        INSERT INTO rendimiento 
        (plato, insumo, cantidad_usada, unidad, rendimiento)
        VALUES (:plato, :insumo, :cantidad, :unidad, :rendimiento)
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':plato' => $platoId,
            ':insumo' => $insumoId,
            ':cantidad' => $cantidad,
            ':unidad' => $unidad,
            ':rendimiento' => $rendimiento
        ]);
    }

    /***************************************************************************
     * <2>. eliminar
     */

    public function eliminarRendimiento(int $rendimiento_id): void
    {
        $sql = "DELETE FROM rendimiento WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $rendimiento_id]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("La regla no existe o ya fue eliminada.");
        }
    }

    /***************************************************************************
     * <99>. Verifica si existen valores en la bd
     */


    private function existeInsumo(string $nombre): bool
    {
        $sql = "SELECT id FROM insumo WHERE LOWER(descripcion) = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nombre' => $nombre]);

        return $stmt->fetch() !== false;
    }

    private function existePlato(string $nombre): bool
    {
        $sql = "SELECT id FROM plato WHERE LOWER(descripcion) = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nombre' => $nombre]);

        return $stmt->fetch() !== false;
    }
    //************************************************************************* */


}