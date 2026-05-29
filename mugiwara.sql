-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-04-2026 a las 22:10:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mugiwara`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backup_precio_costo`
--

DROP TABLE IF EXISTS `backup_precio_costo`;
CREATE TABLE IF NOT EXISTS `backup_precio_costo` (
  `plato_id` int(11) NOT NULL,
  `costo_receta` decimal(10,2) DEFAULT NULL,
  `margen` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ganancia` decimal(10,2) NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`plato_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `comprador` varchar(40) NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_detalle`
--

DROP TABLE IF EXISTS `compras_detalle`;
CREATE TABLE IF NOT EXISTS `compras_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) NOT NULL,
  `insumo_id` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `unidad_medida` varchar(10) NOT NULL,
  `precio_unitario` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Encabezado_detalle` (`compra_id`),
  KEY `insumo_detalle` (`insumo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_backup`
--

DROP TABLE IF EXISTS `compra_backup`;
CREATE TABLE IF NOT EXISTS `compra_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_backup_detalle`
--

DROP TABLE IF EXISTS `compra_backup_detalle`;
CREATE TABLE IF NOT EXISTS `compra_backup_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `backup_id` int(11) DEFAULT NULL,
  `tipo` enum('insumo','plato') DEFAULT NULL,
  `entidad_id` int(11) DEFAULT NULL,
  `stock_anterior` decimal(10,2) DEFAULT NULL,
  `precio_unitario_anterior` decimal(10,4) DEFAULT NULL,
  `costo_receta_anterior` decimal(10,4) DEFAULT 0.0000,
  `precio_costo` decimal(10,2) NOT NULL,
  `margen` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ganancia` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_backup` (`backup_id`,`tipo`,`entidad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impacto_plato`
--

DROP TABLE IF EXISTS `impacto_plato`;
CREATE TABLE IF NOT EXISTS `impacto_plato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plato_id` int(11) DEFAULT NULL,
  `insumo_id` int(11) DEFAULT NULL,
  `impacto` float DEFAULT NULL,
  `tipo` varchar(10) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `foranea_plato` (`plato_id`),
  KEY `foranea_insumo` (`insumo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

DROP TABLE IF EXISTS `insumo`;
CREATE TABLE IF NOT EXISTS `insumo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(150) NOT NULL,
  `stock` float NOT NULL,
  `precio_unitario` float NOT NULL,
  `unidad_medida` varchar(30) NOT NULL,
  `stock_minimo` varchar(1500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `insumo`
--
DROP TRIGGER IF EXISTS `impacto_precio_insumo`;
DELIMITER $$
CREATE TRIGGER `impacto_precio_insumo` AFTER UPDATE ON `insumo` FOR EACH ROW BEGIN

    DECLARE diferencia FLOAT;
    DECLARE fin INT DEFAULT 0;
    DECLARE v_plato_id INT;
    DECLARE v_cantidad INT;
    DECLARE impacto FLOAT;

    DECLARE cursor_platos CURSOR FOR
    SELECT plato, cantidad_usada
    FROM rendimiento
    WHERE insumo = NEW.id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;

    IF OLD.precio_unitario <> NEW.precio_unitario THEN

        SET diferencia = NEW.precio_unitario - OLD.precio_unitario;

        OPEN cursor_platos;

        leer_platos: LOOP

            FETCH cursor_platos INTO v_plato_id, v_cantidad;

            IF fin = 1 THEN
                LEAVE leer_platos;
            END IF;

            SET impacto = diferencia * v_cantidad;

            INSERT INTO impacto_plato (
                plato_id,
                insumo_id,
                impacto,
                tipo
            ) VALUES (
                v_plato_id,
                NEW.id,
                impacto,
                IF(impacto > 0,'subio','bajo')
            );

        END LOOP;

        CLOSE cursor_platos;

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_stock`
--

DROP TABLE IF EXISTS `movimiento_stock`;
CREATE TABLE IF NOT EXISTS `movimiento_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insumo_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato`
--

DROP TABLE IF EXISTS `plato`;
CREATE TABLE IF NOT EXISTS `plato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(150) NOT NULL,
  `costo_receta` float NOT NULL,
  `margen` float NOT NULL,
  `precio_venta` float NOT NULL,
  `ganancia` float NOT NULL,
  `imagen` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo`
--

DROP TABLE IF EXISTS `promo`;
CREATE TABLE IF NOT EXISTS `promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('fijo','porcentaje','xyy') NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `cantidad_x` int(11) DEFAULT NULL,
  `cantidad_y` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `ganancia_promo` decimal(10,2) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  `imagen` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo_detalle`
--

DROP TABLE IF EXISTS `promo_detalle`;
CREATE TABLE IF NOT EXISTS `promo_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_id` int(11) NOT NULL,
  `plato_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `promo_id` (`promo_id`),
  KEY `plato_id` (`plato_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rendimiento`
--

DROP TABLE IF EXISTS `rendimiento`;
CREATE TABLE IF NOT EXISTS `rendimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insumo` int(11) NOT NULL,
  `plato` int(11) NOT NULL,
  `cantidad_usada` decimal(10,2) NOT NULL,
  `unidad` varchar(150) NOT NULL,
  `rendimiento` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `insumos` (`insumo`),
  KEY `plato` (`plato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO` varchar(100) NOT NULL,
  `CONTRASENIA` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE IF NOT EXISTS `venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) DEFAULT NULL,
  `delivery` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

DROP TABLE IF EXISTS `venta_detalle`;
CREATE TABLE IF NOT EXISTS `venta_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) DEFAULT NULL,
  `plato_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `ganancia` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `foranea_promo` (`promo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `backup_precio_costo`
--
ALTER TABLE `backup_precio_costo`
  ADD CONSTRAINT `plato_foranea` FOREIGN KEY (`plato_id`) REFERENCES `plato` (`id`);

--
-- Filtros para la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD CONSTRAINT `Encabezado_detalle` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `insumo_detalle` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `impacto_plato`
--
ALTER TABLE `impacto_plato`
  ADD CONSTRAINT `foranea_insumo` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foranea_plato` FOREIGN KEY (`plato_id`) REFERENCES `plato` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `promo_detalle`
--
ALTER TABLE `promo_detalle`
  ADD CONSTRAINT `promo_detalle_ibfk_1` FOREIGN KEY (`promo_id`) REFERENCES `promo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_detalle_ibfk_2` FOREIGN KEY (`plato_id`) REFERENCES `plato` (`id`);

--
-- Filtros para la tabla `rendimiento`
--
ALTER TABLE `rendimiento`
  ADD CONSTRAINT `insumos` FOREIGN KEY (`insumo`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plato` FOREIGN KEY (`plato`) REFERENCES `plato` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `foranea_promo` FOREIGN KEY (`promo_id`) REFERENCES `promo` (`id`),
  ADD CONSTRAINT `venta_detalle_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
