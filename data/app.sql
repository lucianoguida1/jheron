-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 06-Jun-2017 às 19:56
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app`
--
CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `app`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adicionais`
--

CREATE TABLE `adicionais` (
  `id` int(11) NOT NULL,
  `bebida_id` int(11) NOT NULL,
  `iten_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `adicionais`
--

INSERT INTO `adicionais` (`id`, `bebida_id`, `iten_id`) VALUES
(31, 3, 34),
(32, 5, 35),
(33, 1, 36),
(34, 3, 36),
(35, 5, 36),
(38, 3, 38),
(39, 1, 39);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bebidas`
--

CREATE TABLE `bebidas` (
  `id` int(11) NOT NULL,
  `tamanho` varchar(45) NOT NULL,
  `valor` float NOT NULL,
  `produto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `bebidas`
--

INSERT INTO `bebidas` (`id`, `tamanho`, `valor`, `produto_id`) VALUES
(1, '1', 8, 13),
(3, '2', 9, 15),
(5, '600', 4.5, 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `estoques`
--

CREATE TABLE `estoques` (
  `id` int(11) NOT NULL,
  `quantidade` float NOT NULL,
  `medida` varchar(45) NOT NULL,
  `preco` float NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(25) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `estoques`
--

INSERT INTO `estoques` (`id`, `quantidade`, `medida`, `preco`, `data`, `status`, `produto_id`, `fornecedor_id`) VALUES
(3, 10, 'kg', 6, '2016-11-20 21:53:16', 1, 3, 1),
(4, 10, 'kg', 4, '2016-11-20 21:53:16', 1, 4, 1),
(5, 10, 'kg', 2, '2016-11-20 21:53:16', 1, 5, 1),
(10, 10, 'kg', 4, '2016-11-20 21:53:16', 0, 2, 2),
(11, 10, 'kg', 2, '2016-11-20 21:53:16', 1, 3, 2),
(12, 10, 'kg', 2, '2016-11-20 21:53:16', 1, 4, 2),
(13, 10, 'kg', 6, '2016-11-20 21:53:16', 1, 6, 3),
(14, 10, 'kg', 15, '2016-11-20 21:53:16', 0, 7, 3),
(17, 10, 'un', 6, '2016-11-27 05:41:11', 1, 13, 3),
(19, 10, 'un', 2, '2016-11-27 05:41:08', 1, 15, 3),
(21, 10, 'un', 3.5, '2016-11-27 05:41:16', 1, 18, 1),
(30, 10, 'kg', 3, '2016-11-20 21:53:16', 0, 2, 2),
(31, 10, 'kg', 30, '2016-11-27 07:06:01', 0, 2, 2),
(32, 10, 'kg', 0, '2016-11-20 21:53:16', 1, 7, 3),
(33, 1, 'kg', 10, '2016-11-26 17:55:32', 0, 21, 2),
(34, 5, 'kg', 5, '2016-11-26 18:00:49', 1, 21, 1),
(35, 4, 'kg', 20, '2016-11-27 07:18:44', 1, 2, 1),
(36, 2, 'kg', 0, '2017-03-12 22:49:14', 1, 2, 1),
(37, 4, 'kg', 12, '2017-05-27 22:49:32', 1, 2, 1),
(38, 2, 'kg', 5, '2017-06-06 17:34:28', 1, 22, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedors`
--

CREATE TABLE `fornecedors` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `endereco` longtext NOT NULL,
  `telefone` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `fornecedors`
--

INSERT INTO `fornecedors` (`id`, `nome`, `endereco`, `telefone`, `user_id`) VALUES
(1, 'Fatima', 'Av. Castelo Branco', '33610000', 5),
(2, 'Bem Maior', 'Av. Juca', '190', 5),
(3, 'Serve Mais', 'Av. 23 de outubro', '6333611515', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL,
  `snack_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ingredientes`
--

INSERT INTO `ingredientes` (`id`, `snack_id`, `produto_id`, `quantidade`) VALUES
(5, 1, 3, 1),
(6, 1, 4, 1),
(9, 7, 3, 1),
(10, 7, 4, 1),
(12, 8, 2, 1),
(13, 8, 3, 1),
(14, 8, 4, 1),
(65, 7, 2, 1),
(66, 7, 5, 1),
(68, 7, 7, 1),
(74, 16, 2, 0),
(75, 16, 3, 0),
(76, 16, 4, 0),
(77, 16, 5, 0),
(78, 16, 7, 0),
(79, 16, 21, 0),
(80, 16, 6, 1),
(81, 17, 2, 0),
(82, 17, 3, 0),
(83, 17, 4, 0),
(84, 17, 5, 0),
(85, 17, 6, 0),
(86, 17, 7, 0),
(87, 17, 21, 0),
(88, 7, 22, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `snack_id` int(11) NOT NULL,
  `ingr` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `itens`
--

INSERT INTO `itens` (`id`, `pedido_id`, `snack_id`, `ingr`) VALUES
(34, 23, 1, '<span>Calabresa </span><span>Bacon </span>'),
(35, 24, 7, '<span>Calabresa </span><span>Bacon </span><span>Milho </span><span>Cavalinho </span><span>Ervilha </span>'),
(36, 25, 1, '<span>Calabresa </span><span>Bacon </span>'),
(38, 27, 7, '<span>Calabresa </span><span>Bacon </span><span>Milho </span><span>Cavalinho </span><span>Ervilha </span>'),
(39, 28, 1, '<span>Calabresa </span><span>Bacon </span>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `IP` varchar(20) NOT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lost_passwords`
--

CREATE TABLE `lost_passwords` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `IP` varchar(20) NOT NULL,
  `token` char(128) NOT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `telefone` varchar(45) NOT NULL,
  `setor` varchar(45) NOT NULL,
  `rua` varchar(45) NOT NULL,
  `numero` varchar(45) NOT NULL,
  `troco` int(11) NOT NULL,
  `referencia` longtext,
  `status` int(3) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `valor` double NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `nome`, `telefone`, `setor`, `rua`, `numero`, `troco`, `referencia`, `status`, `data`, `valor`, `user_id`) VALUES
(23, 'Guida', '63920067005', 'Alto PSO', 'Jose Nezio Ramos', '949', 0, '44', 3, '2016-11-26 17:50:12', 24.5, 5),
(24, 'Guida', '63920067005', 'Alto PSO', 'Jose Nezio Ramos', '949', 0, '44', 4, '2016-11-27 06:22:28', 27.5, 5),
(25, 'Guida', '63920067005', 'Alto PSO', 'Jose Nezio Ramos', '949', 0, '44', 3, '2016-11-26 17:50:18', 46, 5),
(26, 'Guida', '63920067005', 'Alto PSO', 'Jose Nezio Ramos', '949', 0, '44', 4, '2016-11-27 06:22:27', 14, 5),
(27, 'Guida', '63920067005', 'Alto PSO', 'Jose Nezio Ramos', '949', 0, '44', 2, '2017-05-27 22:53:12', 23, 5),
(28, 'Darciel Coutinho Dos Reis', '63992243118', 'Pouso alegre', 'Jk', '15', 50, '', 2, '2017-05-27 22:53:04', 23.5, 5),
(29, 'Darciel Coutinho Dos Reis', '63992243118', 'Pouso alegre', 'Jk', '15', 80, '', 2, '2017-05-27 22:53:10', 14, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `data`, `user_id`) VALUES
(2, 'Milho', '2016-11-12 17:56:38', 5),
(3, 'Calabresa', '2016-11-12 17:56:43', 5),
(4, 'Bacon', '2016-11-12 17:56:47', 5),
(5, 'Cavalinho', '2016-11-12 17:56:51', 5),
(6, 'Paoo', '2016-11-15 14:14:01', 5),
(7, 'Ervilha', '2016-11-12 19:54:57', 5),
(13, 'Coca Cola', '2016-11-12 21:39:42', 5),
(15, 'Guarana', '2016-11-19 13:03:11', 5),
(18, 'Fanta', '2016-11-15 04:55:21', 5),
(21, 'Frango', '2016-11-26 17:55:32', 5),
(22, 'Alface', '2017-06-06 17:34:28', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `snacks`
--

CREATE TABLE `snacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `preco` float NOT NULL,
  `observacoes` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `snacks`
--

INSERT INTO `snacks` (`id`, `user_id`, `nome`, `preco`, `observacoes`) VALUES
(1, 5, 'X-Gordo', 15.5, NULL),
(7, 5, 'teste2', 14, NULL),
(8, 5, 'X-Milho', 9, NULL),
(16, 5, 'X-Tudo', 14.5, NULL),
(17, 5, 'Luciano Guida', 23, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(45) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `obs` mediumtext,
  `role` varchar(45) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `telefone`, `password`, `salt`, `obs`, `role`, `status`) VALUES
(1, 'Bruno C. Santos', 'administrador', 'administrador@hxphp.com.br', '', '1fd149d21dd5e8072d12b5d6b4eb316e66e94409ddb4858ee7ed019ff2756e91ee2c5b0d9a4bb641f27f61238b1181a7bac43210c8a26dc66ac6538c00d06c45', 'bac4e9c259ac3acee88a679899062cd85e32493ff3d1109720ea459a62f5ef3166427612a167f29ab3e1e8d8f46c6b5409f1304be97cbf4b4fd5f5d55bdf55f9', NULL, 'administrator', 0),
(2, 'Usuário para testes', 'demo', 'contato@hxphp.com.br', '', '1fd149d21dd5e8072d12b5d6b4eb316e66e94409ddb4858ee7ed019ff2756e91ee2c5b0d9a4bb641f27f61238b1181a7bac43210c8a26dc66ac6538c00d06c45', 'bac4e9c259ac3acee88a679899062cd85e32493ff3d1109720ea459a62f5ef3166427612a167f29ab3e1e8d8f46c6b5409f1304be97cbf4b4fd5f5d55bdf55f9', NULL, 'user', 1),
(5, 'luciano', 'lucianoguida10', 'lucianoguida10@gmail.com', '6392397085', 'e883b88cd3fb931af17741aba9d5ed70b44ff9d434a385443d05883442e5a230ca70e046c84cf1e63e68f88ac029e15d54b48336018bb3ca02ff82662958e441', '8f27b8c4b7506f572a0abf930f509b101a2ee504db5a9841090b6579b334b30d42d39da4a0d0ae5fd6af892e901f82979229d887d9afbf3e3305a1d93cfaef40', NULL, 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adicionais`
--
ALTER TABLE `adicionais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adicionais_itens_idx` (`iten_id`),
  ADD KEY `bebida_iten_idx` (`bebida_id`);

--
-- Indexes for table `bebidas`
--
ALTER TABLE `bebidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bebidas_produtos1_idx` (`produto_id`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_idx` (`user_id`);

--
-- Indexes for table `estoques`
--
ALTER TABLE `estoques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estoques_produtos1_idx` (`produto_id`),
  ADD KEY `fk_estoques_fornecedors1_idx` (`fornecedor_id`);

--
-- Indexes for table `fornecedors`
--
ALTER TABLE `fornecedors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fornecedors_users1_idx` (`user_id`);

--
-- Indexes for table `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingredientes_lanches1_idx` (`snack_id`),
  ADD KEY `fk_ingredientes_estoques1_idx` (`produto_id`);

--
-- Indexes for table `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Pedido_itens_idx` (`pedido_id`),
  ADD KEY `Lanches_itens_idx` (`snack_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_attempts_user_id` (`user_id`);

--
-- Indexes for table `lost_passwords`
--
ALTER TABLE `lost_passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lost_password_user_id_idx` (`user_id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_users_idx` (`user_id`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produtos_users1_idx` (`user_id`);

--
-- Indexes for table `snacks`
--
ALTER TABLE `snacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_user_id_idx` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adicionais`
--
ALTER TABLE `adicionais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `bebidas`
--
ALTER TABLE `bebidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `estoques`
--
ALTER TABLE `estoques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `fornecedors`
--
ALTER TABLE `fornecedors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lost_passwords`
--
ALTER TABLE `lost_passwords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `snacks`
--
ALTER TABLE `snacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `adicionais`
--
ALTER TABLE `adicionais`
  ADD CONSTRAINT `adicionais_itens` FOREIGN KEY (`iten_id`) REFERENCES `itens` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `bebida_iten` FOREIGN KEY (`bebida_id`) REFERENCES `bebidas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `bebidas`
--
ALTER TABLE `bebidas`
  ADD CONSTRAINT `fk_bebidas_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `payments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `estoques`
--
ALTER TABLE `estoques`
  ADD CONSTRAINT `fk_estoques_fornecedors1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_estoques_produtos1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fornecedors`
--
ALTER TABLE `fornecedors`
  ADD CONSTRAINT `fk_fornecedors_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `fk_ingredientes_estoques1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ingredientes_lanches1` FOREIGN KEY (`snack_id`) REFERENCES `snacks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `itens`
--
ALTER TABLE `itens`
  ADD CONSTRAINT `Lanches_itens` FOREIGN KEY (`snack_id`) REFERENCES `snacks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Pedido_itens` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `lost_passwords`
--
ALTER TABLE `lost_passwords`
  ADD CONSTRAINT `lost_password_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `snacks`
--
ALTER TABLE `snacks`
  ADD CONSTRAINT `projects_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
