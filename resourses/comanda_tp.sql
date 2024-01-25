-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comanda_tp`
--

-- --------------------------------------------------------

--
-- Table structure for table `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `puntuacionMesa` int(11) NOT NULL,
  `puntuacionMozo` int(11) NOT NULL,
  `puntuacionCocinero` int(11) NOT NULL,
  `puntuacionRestaurant` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encuesta`
--

INSERT INTO `encuesta` (`id`, `idMesa`, `idPedido`, `nombreCliente`, `descripcion`, `puntuacionMesa`, `puntuacionMozo`, `puntuacionCocinero`, `puntuacionRestaurant`, `estado`) VALUES
(2, 13, 13, 'fernando gutierrez', 'Muy buen restaurante', 9, 8, 7, 8, 1),
(3, 17, 17, 'santiago fernandez', 'excelente', 9, 8, 7, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mesa`
--

CREATE TABLE `mesa` (
  `id` int(3) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesa`
--

INSERT INTO `mesa` (`id`, `estado`, `activo`) VALUES
(4, 'cerrada', 1),
(5, 'con cliente esperando pedido', 1),
(6, 'cerrada', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `id` int(5) NOT NULL,
  `idMozo` int(3) NOT NULL,
  `idMesa` int(3) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `precio` int(20) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `tiempoEstimado` int(11) NOT NULL,
  `imagenMesa` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedido`
--

INSERT INTO `pedido` (`id`, `idMozo`, `idMesa`, `nombreCliente`, `precio`, `estado`, `tiempoEstimado`, `imagenMesa`) VALUES
(13, 24, 4, 'fernando gutierrez', 5300, 'Finalizado', 0, './images/\\4-fernando gutierrez.jpg'),
(16, 24, 5, 'abril diaz', 0, 'Pendiente', 0, '-'),
(17, 24, 6, 'santiago fernandez', 5300, 'Finalizado', 0, './images/\\6-santiago fernandez.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `id` int(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `sector` varchar(20) NOT NULL,
  `precio` double NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`id`, `descripcion`, `sector`, `precio`, `activo`) VALUES
(7, 'milanesa a caballo', 'cocina', 1500, 1),
(8, 'hamburguesa de garbanzo', 'cocina', 2000, 1),
(9, 'corona', 'cerveceria', 800, 1),
(10, 'daikiri', 'barra de tragos', 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productopedido`
--

CREATE TABLE `productopedido` (
  `id` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `estado` varchar(99) NOT NULL,
  `tiempoPreparacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productopedido`
--

INSERT INTO `productopedido` (`id`, `idProducto`, `idPedido`, `idEmpleado`, `estado`, `tiempoPreparacion`) VALUES
(2, 7, 13, 24, 'Listo Para Servir', 0),
(3, 8, 13, 24, 'Listo Para Servir', 0),
(4, 9, 13, 24, 'Listo Para Servir', 0),
(5, 10, 13, 24, 'Listo Para Servir', 0),
(6, 7, 17, 24, 'Listo Para Servir', 0),
(7, 8, 17, 24, 'Listo Para Servir', 0),
(8, 9, 17, 24, 'Listo Para Servir', 0),
(9, 10, 17, 24, 'Listo Para Servir', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(3) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `contrasenia` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `fechaRegistro`, `tipo`, `username`, `contrasenia`, `activo`) VALUES
(1, 'bianca', 'decima', '2023-12-02 22:39:00', 'socio', 'bian', '1234', 1),
(23, 'lucero', 'gomez', '2023-12-03 03:08:27', 'cocinero', 'lgomez', '1234', 1),
(24, 'tomas', 'peralta', '2023-12-03 03:09:02', 'mesero', 'tperalta', '1234', 1),
(25, 'marcos', 'aguirre', '2023-12-03 03:19:21', 'barman', 'marqui', '1234', 1),
(26, 'sofia', 'gutierrez', '2023-12-03 03:20:54', 'cervecero', 'sguti', '1234', 1),
(27, 'fiama', 'domingues', '2023-12-03 03:21:55', 'repostero', 'fdomi', '1234', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productopedido`
--
ALTER TABLE `productopedido`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
