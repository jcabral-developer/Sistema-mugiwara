-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-05-2026 a las 21:24:18
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
-- Base de datos: `mugiwara_pruebas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backup_precio_costo`
--

CREATE TABLE `backup_precio_costo` (
  `plato_id` int(11) NOT NULL,
  `costo_receta` decimal(10,2) DEFAULT NULL,
  `margen` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ganancia` decimal(10,2) NOT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `backup_precio_costo`
--

INSERT INTO `backup_precio_costo` (`plato_id`, `costo_receta`, `margen`, `precio_venta`, `ganancia`, `fecha_actualizacion`) VALUES
(1, 4710.00, 112.30, 10000.00, 5290.00, '2026-05-18 20:01:20'),
(2, 1980.00, 304.04, 8000.00, 6020.00, '2026-05-18 20:01:20'),
(3, 8278.00, 141.60, 20000.00, 11722.00, '2026-05-18 20:01:20'),
(4, 4139.00, 189.92, 12000.00, 7861.00, '2026-05-18 20:01:20'),
(5, 688.40, 190.40, 2000.00, 1311.60, '2026-05-18 20:01:20'),
(6, 910.00, 119.70, 2000.00, 1090.00, '2026-05-18 20:01:20'),
(7, 1975.00, 305.73, 8000.00, 6025.00, '2026-05-18 20:01:20'),
(8, 1975.00, 204.30, 6000.00, 4025.00, '2026-05-18 20:01:20'),
(9, 0.00, 0.00, 0.00, 0.00, '2026-05-18 20:01:20'),
(10, 0.00, 0.00, 0.00, 0.00, '2026-05-18 20:01:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comprador` varchar(40) NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_detalle`
--

CREATE TABLE `compras_detalle` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `insumo_id` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `unidad_medida` varchar(10) NOT NULL,
  `precio_unitario` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_backup`
--

CREATE TABLE `compra_backup` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_backup_detalle`
--

CREATE TABLE `compra_backup_detalle` (
  `id` int(11) NOT NULL,
  `backup_id` int(11) DEFAULT NULL,
  `tipo` enum('insumo','plato') DEFAULT NULL,
  `entidad_id` int(11) DEFAULT NULL,
  `stock_anterior` decimal(10,2) DEFAULT NULL,
  `precio_unitario_anterior` decimal(10,4) DEFAULT NULL,
  `costo_receta_anterior` decimal(10,4) DEFAULT 0.0000,
  `precio_costo` decimal(10,2) NOT NULL,
  `margen` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ganancia` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra_backup_detalle`
--

INSERT INTO `compra_backup_detalle` (`id`, `backup_id`, `tipo`, `entidad_id`, `stock_anterior`, `precio_unitario_anterior`, `costo_receta_anterior`, `precio_costo`, `margen`, `precio_venta`, `ganancia`) VALUES
(1, 1, 'plato', 1, NULL, NULL, 4710.0000, 0.00, 112, 10000.00, 5290.00),
(2, 1, 'plato', 2, NULL, NULL, 1980.0000, 0.00, 304, 8000.00, 6020.00),
(3, 1, 'plato', 3, NULL, NULL, 8278.0000, 0.00, 142, 20000.00, 11722.00),
(4, 1, 'plato', 4, NULL, NULL, 4139.0000, 0.00, 190, 12000.00, 7861.00),
(5, 1, 'plato', 5, NULL, NULL, 688.4010, 0.00, 190, 2000.00, 1311.60),
(6, 1, 'plato', 6, NULL, NULL, 910.0000, 0.00, 120, 2000.00, 1090.00),
(7, 1, 'plato', 7, NULL, NULL, 1975.0000, 0.00, 305, 8000.00, 6025.00),
(8, 1, 'plato', 8, NULL, NULL, 1975.0000, 0.00, 204, 6000.00, 4025.00),
(9, 1, 'plato', 9, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(10, 1, 'plato', 10, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(11, 1, 'insumo', 12, 0.00, 75.0000, 0.0000, 0.00, 0, 0.00, 0.00),
(12, 1, 'insumo', 14, 0.00, 2.5000, 0.0000, 0.00, 0, 0.00, 0.00),
(13, 1, 'insumo', 9, 0.00, 5.6000, 0.0000, 0.00, 0, 0.00, 0.00),
(14, 1, 'insumo', 13, 0.00, 15.0000, 0.0000, 0.00, 0, 0.00, 0.00),
(15, 2, 'plato', 1, NULL, NULL, 4710.0000, 0.00, 112, 10000.00, 5290.00),
(16, 2, 'plato', 2, NULL, NULL, 1980.0000, 0.00, 304, 8000.00, 6020.00),
(17, 2, 'plato', 3, NULL, NULL, 8278.0000, 0.00, 142, 20000.00, 11722.00),
(18, 2, 'plato', 4, NULL, NULL, 4139.0000, 0.00, 190, 12000.00, 7861.00),
(19, 2, 'plato', 5, NULL, NULL, 688.4010, 0.00, 190, 2000.00, 1311.60),
(20, 2, 'plato', 6, NULL, NULL, 910.0000, 0.00, 120, 2000.00, 1090.00),
(21, 2, 'plato', 7, NULL, NULL, 1971.7500, 0.00, 306, 8000.00, 6028.25),
(22, 2, 'plato', 8, NULL, NULL, 1971.7500, 0.00, 204, 6000.00, 4028.25),
(23, 2, 'plato', 9, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(24, 2, 'plato', 10, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(25, 2, 'insumo', 8, 0.00, 0.7200, 0.0000, 0.00, 0, 0.00, 0.00),
(26, 3, 'plato', 1, NULL, NULL, 4710.0000, 0.00, 112, 10000.00, 5290.00),
(27, 3, 'plato', 2, NULL, NULL, 1980.0000, 0.00, 304, 8000.00, 6020.00),
(28, 3, 'plato', 3, NULL, NULL, 8278.0000, 0.00, 142, 20000.00, 11722.00),
(29, 3, 'plato', 4, NULL, NULL, 4139.0000, 0.00, 190, 12000.00, 7861.00),
(30, 3, 'plato', 5, NULL, NULL, 688.4010, 0.00, 190, 2000.00, 1311.60),
(31, 3, 'plato', 6, NULL, NULL, 910.0000, 0.00, 120, 2000.00, 1090.00),
(32, 3, 'plato', 7, NULL, NULL, 1975.0000, 0.00, 306, 8000.00, 6025.00),
(33, 3, 'plato', 8, NULL, NULL, 1975.0000, 0.00, 204, 6000.00, 4025.00),
(34, 3, 'plato', 9, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(35, 3, 'plato', 10, NULL, NULL, 0.0000, 0.00, 0, 0.00, 0.00),
(36, 3, 'insumo', 12, 0.00, 75.0000, 0.0000, 0.00, 0, 0.00, 0.00),
(37, 3, 'insumo', 14, 0.00, 2.5000, 0.0000, 0.00, 0, 0.00, 0.00),
(38, 3, 'insumo', 9, 0.00, 5.6000, 0.0000, 0.00, 0, 0.00, 0.00),
(39, 3, 'insumo', 13, 0.00, 15.0000, 0.0000, 0.00, 0, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra_detalle`
--

CREATE TABLE `extra_detalle` (
  `id` int(11) NOT NULL,
  `plato_id` int(11) NOT NULL,
  `insumo_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `extra_detalle`
--

INSERT INTO `extra_detalle` (`id`, `plato_id`, `insumo_id`, `cantidad`, `unidad`) VALUES
(1, 7, 14, 50.00, 'gr'),
(2, 9, 12, 50.00, 'gr');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impacto_plato`
--

CREATE TABLE `impacto_plato` (
  `id` int(11) NOT NULL,
  `plato_id` int(11) DEFAULT NULL,
  `insumo_id` int(11) DEFAULT NULL,
  `impacto` float DEFAULT NULL,
  `tipo` varchar(10) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `impacto_plato`
--

INSERT INTO `impacto_plato` (`id`, `plato_id`, `insumo_id`, `impacto`, `tipo`, `fecha`) VALUES
(1, 7, 9, -3.38, 'bajo', '2026-05-18 19:40:55'),
(2, 8, 9, -3.38, 'bajo', '2026-05-18 19:40:55'),
(3, 7, 8, 4.75, 'subio', '2026-05-18 19:45:41'),
(4, 8, 8, 4.75, 'subio', '2026-05-18 19:45:41'),
(5, 7, 8, -4.75, 'bajo', '2026-05-18 19:59:19'),
(6, 8, 8, -4.75, 'bajo', '2026-05-18 19:59:19'),
(7, 7, 9, 3.38, 'subio', '2026-05-18 19:59:21'),
(8, 8, 9, 3.38, 'subio', '2026-05-18 19:59:21'),
(9, 7, 9, -3.38, 'bajo', '2026-05-18 20:01:20'),
(10, 8, 9, -3.38, 'bajo', '2026-05-18 20:01:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

CREATE TABLE `insumo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `stock` float NOT NULL,
  `precio_unitario` float NOT NULL,
  `unidad_medida` varchar(30) NOT NULL,
  `stock_minimo` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumo`
--

INSERT INTO `insumo` (`id`, `descripcion`, `stock`, `precio_unitario`, `unidad_medida`, `stock_minimo`) VALUES
(1, 'Papa', 0, 0.75, 'gr', '0'),
(2, 'Huevo', 0, 160, 'un', '0'),
(3, 'Jamón', 0, 10.7, 'gr', '0'),
(4, 'Muzzarella', 0, 7.5, 'gr', '0'),
(5, 'Carne', 0, 9, 'gr', '0'),
(6, 'Cebolla', 0, 0.7, 'gr', '0'),
(7, 'Morrón', 0, 600, 'un', '0'),
(8, 'Harina', 0, 0.72, 'gr', '0'),
(9, 'Levadura', 0, 5.34, 'gr', '0'),
(10, 'Aceite', 0, 3.25, 'ml', '0'),
(11, 'Salsa de tomate', 0, 1.25, 'ml', '0'),
(12, 'Anchoas', 0, 50, 'gr', '0'),
(13, 'Roquefort', 0, 8.2152, 'gr', '0'),
(14, 'Choclo', 0, 3.75, 'gr', '0'),
(15, 'Tomate', 0, 0.75, 'gr', '0'),
(16, 'aceitunas', 0, 0, 'gr', '0');

--
-- Disparadores `insumo`
--
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

CREATE TABLE `movimiento_stock` (
  `id` int(11) NOT NULL,
  `insumo_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato`
--

CREATE TABLE `plato` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `costo_receta` float NOT NULL,
  `margen` float NOT NULL,
  `precio_venta` float NOT NULL,
  `ganancia` float NOT NULL,
  `imagen` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plato`
--

INSERT INTO `plato` (`id`, `descripcion`, `costo_receta`, `margen`, `precio_venta`, `ganancia`, `imagen`) VALUES
(1, 'Tortilla rellena', 4710, 112.3, 10000, 5290, 'default.png'),
(2, 'Tortilla simple', 1980, 304.04, 8000, 6020, 'default.png'),
(3, 'Empanadas de carne (Docena)', 8278, 141.6, 20000, 11722, 'default.png'),
(4, 'Empanadas de carne (Media docena)', 4139, 189.92, 12000, 7861, 'default.png'),
(5, 'Empanada de carne (Unidad)', 688.401, 190.4, 2000, 1311.6, 'default.png'),
(6, 'Empanadas de jamón y queso (Unidad)', 910, 119.7, 2000, 1090, 'default.png'),
(7, 'Pizza de muzzarella', 1971.75, 305.731, 8000, 6028.25, 'default.png'),
(8, 'Pizza de muzzarella (Prepizza)', 1971.75, 204.298, 6000, 4028.25, 'default.png'),
(9, 'Pizza especial', 1971.75, 508.59, 12000, 10028.2, 'plato_9_1779141006.png'),
(10, 'Pizza especial (Prepizza)', 1971.75, 407.16, 10000, 8028.25, 'default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('fijo','porcentaje','xyy') NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `cantidad_x` int(11) DEFAULT NULL,
  `cantidad_y` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `ganancia_promo` decimal(10,2) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  `imagen` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promo`
--

INSERT INTO `promo` (`id`, `nombre`, `tipo`, `valor`, `cantidad_x`, `cantidad_y`, `precio_total`, `ganancia_promo`, `fecha_creacion`, `estado`, `imagen`) VALUES
(1, 'Promo Pizza muza prehecha x2', 'fijo', 10000.00, NULL, NULL, 10000.00, 6056.50, '2026-05-28 16:48:52', 1, 'promosDefecto.png'),
(2, 'Promo pizza especial prehecha x2', 'fijo', 16000.00, NULL, NULL, 16000.00, 12056.50, '2026-05-28 17:55:05', 1, 'promosDefecto.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo_detalle`
--

CREATE TABLE `promo_detalle` (
  `id` int(11) NOT NULL,
  `promo_id` int(11) NOT NULL,
  `plato_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promo_detalle`
--

INSERT INTO `promo_detalle` (`id`, `promo_id`, `plato_id`, `cantidad`) VALUES
(2, 1, 8, 2),
(3, 2, 10, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rendimiento`
--

CREATE TABLE `rendimiento` (
  `id` int(11) NOT NULL,
  `insumo` int(11) NOT NULL,
  `plato` int(11) NOT NULL,
  `cantidad_usada` decimal(10,2) NOT NULL,
  `unidad` varchar(150) NOT NULL,
  `rendimiento` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rendimiento`
--

INSERT INTO `rendimiento` (`id`, `insumo`, `plato`, `cantidad_usada`, `unidad`, `rendimiento`) VALUES
(1, 1, 1, 2000.00, 'gr', '1'),
(2, 2, 1, 3.00, 'un', '1'),
(3, 3, 1, 150.00, 'gr', '1'),
(4, 4, 1, 150.00, 'gr', '1'),
(5, 1, 2, 2000.00, 'gr', '1'),
(6, 2, 2, 3.00, 'un', '1'),
(7, 5, 3, 800.00, 'gr', '1'),
(8, 6, 3, 800.00, 'gr', '1'),
(9, 7, 3, 0.40, 'un', '1'),
(10, 2, 3, 0.80, 'un', '1'),
(11, 1, 3, 200.00, 'gr', '1'),
(12, 5, 4, 400.00, 'gr', '1'),
(13, 6, 4, 400.00, 'gr', '1'),
(14, 7, 4, 0.20, 'un', '1'),
(15, 2, 4, 0.40, 'un', '1'),
(16, 1, 4, 100.00, 'gr', '1'),
(17, 5, 5, 66.67, 'gr', '1'),
(18, 6, 5, 66.67, 'gr', '1'),
(19, 7, 5, 0.03, 'un', '1'),
(20, 2, 5, 0.07, 'un', '1'),
(21, 1, 5, 16.67, 'gr', '1'),
(22, 3, 6, 50.00, 'gr', '1'),
(23, 4, 6, 50.00, 'gr', '1'),
(24, 8, 7, 250.00, 'gr', '1'),
(25, 9, 7, 12.50, 'gr', '1'),
(26, 10, 7, 50.00, 'ml', '1'),
(27, 11, 7, 50.00, 'ml', '1'),
(28, 4, 7, 200.00, 'gr', '1'),
(29, 8, 8, 250.00, 'gr', '1'),
(30, 9, 8, 12.50, 'gr', '1'),
(31, 10, 8, 50.00, 'ml', '1'),
(32, 11, 8, 50.00, 'ml', '1'),
(33, 4, 8, 200.00, 'gr', '1'),
(34, 8, 9, 250.00, 'gr', '1'),
(35, 9, 9, 12.50, 'gr', '1'),
(36, 10, 9, 50.00, 'ml', '1'),
(37, 11, 9, 50.00, 'ml', '1'),
(38, 4, 9, 200.00, 'gr', '1'),
(39, 8, 10, 250.00, 'gr', '1'),
(40, 9, 10, 12.50, 'gr', '1'),
(41, 10, 10, 50.00, 'ml', '1'),
(42, 11, 10, 50.00, 'ml', '1'),
(43, 4, 10, 200.00, 'gr', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `USUARIO` varchar(100) NOT NULL,
  `CONTRASENIA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `USUARIO`, `CONTRASENIA`) VALUES
(1, 'Admin', '$2y$10$RnWajK9rbS7N1eJoP4/hKe6kMRFs1MgNvOKUTFXJ3o0lKWYoy9Rxu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `cliente_nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) DEFAULT NULL,
  `delivery` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

CREATE TABLE `venta_detalle` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `plato_id` int(11) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `ganancia` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `backup_precio_costo`
--
ALTER TABLE `backup_precio_costo`
  ADD PRIMARY KEY (`plato_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Encabezado_detalle` (`compra_id`),
  ADD KEY `insumo_detalle` (`insumo_id`);

--
-- Indices de la tabla `compra_backup`
--
ALTER TABLE `compra_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra_backup_detalle`
--
ALTER TABLE `compra_backup_detalle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_backup` (`backup_id`,`tipo`,`entidad_id`);

--
-- Indices de la tabla `extra_detalle`
--
ALTER TABLE `extra_detalle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_extra` (`plato_id`,`insumo_id`);

--
-- Indices de la tabla `impacto_plato`
--
ALTER TABLE `impacto_plato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foranea_plato` (`plato_id`),
  ADD KEY `foranea_insumo` (`insumo_id`);

--
-- Indices de la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plato`
--
ALTER TABLE `plato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promo_detalle`
--
ALTER TABLE `promo_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_id` (`promo_id`),
  ADD KEY `plato_id` (`plato_id`);

--
-- Indices de la tabla `rendimiento`
--
ALTER TABLE `rendimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insumos` (`insumo`),
  ADD KEY `plato` (`plato`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `foranea_promo` (`promo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `compra_backup`
--
ALTER TABLE `compra_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compra_backup_detalle`
--
ALTER TABLE `compra_backup_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `extra_detalle`
--
ALTER TABLE `extra_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `impacto_plato`
--
ALTER TABLE `impacto_plato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `insumo`
--
ALTER TABLE `insumo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plato`
--
ALTER TABLE `plato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `promo_detalle`
--
ALTER TABLE `promo_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rendimiento`
--
ALTER TABLE `rendimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
