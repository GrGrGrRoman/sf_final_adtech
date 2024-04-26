-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 26 2024 г., 09:14
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `adtech`
--

-- --------------------------------------------------------

--
-- Структура таблицы `adoffer`
--

CREATE TABLE `adoffer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `theme` varchar(191) NOT NULL,
  `price` decimal(12,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `url` varchar(191) NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `date` int(10) UNSIGNED NOT NULL,
  `active` tinyint(3) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `adoffer`
--

INSERT INTO `adoffer` (`id`, `name`, `theme`, `price`, `url`, `userid`, `date`, `active`) VALUES
(42, 'Поиск', 'Поиск всего', 10.00, 'https://ya.ru', 58, 1714045374, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `click`
--

CREATE TABLE `click` (
  `id` int(11) NOT NULL,
  `masteruserid` varchar(191) DEFAULT '0',
  `date` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `url` varchar(191) NOT NULL,
  `offerid` varchar(191) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `click`
--

INSERT INTO `click` (`id`, `masteruserid`, `date`, `status`, `url`, `offerid`, `price`) VALUES
(1, '57', 1714045206, 5, '82780cc79dc73d2a3b5cac10f42dd4a5b29d92f526f6880a70319e58abca2080', '42', 10.00);

-- --------------------------------------------------------

--
-- Структура таблицы `masteroffer`
--

CREATE TABLE `masteroffer` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `offerid` int(10) UNSIGNED NOT NULL,
  `masterurl` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `masteroffer`
--

INSERT INTO `masteroffer` (`id`, `userid`, `offerid`, `masterurl`) VALUES
(26, 57, 42, '82780cc79dc73d2a3b5cac10f42dd4a5b29d92f526f6880a70319e58abca2080');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `balance` decimal(12,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `ip` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `datetime` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `balance`, `active`, `is_admin`, `ip`, `name`, `datetime`) VALUES
(1, 'root', '$2y$10$tT/w/iUKtOIZEEhXwNA6cOVDusu8DN/NOpqcRKNDs7lczQU31GLT.', 777.00, 1, 1, '127.0.0.1', 'admin', 1714046684),
(57, 'master', '$2y$10$HEDKZhm6R.BLEPEsBgyANuTVVso0hh5B0hNGj7zFdxSckkm5wx/NO', 1008.00, 1, 0, '127.0.0.1', 'test_master', 1714045225),
(58, 'advert', '$2y$10$WqFarjNFOSrm6Mf2bwjE.O5zrBZjPgSfPPJuj6zIFUslZWSCDiNWq', 4990.00, 1, 0, '127.0.0.1', 'test_advert', 1714045380);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `adoffer`
--
ALTER TABLE `adoffer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Индексы таблицы `click`
--
ALTER TABLE `click`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masteruserid` (`masteruserid`),
  ADD KEY `url` (`url`);

--
-- Индексы таблицы `masteroffer`
--
ALTER TABLE `masteroffer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masteroffer_userid` (`userid`),
  ADD KEY `masteroffer_offerid` (`offerid`),
  ADD KEY `masterurl` (`masterurl`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `adoffer`
--
ALTER TABLE `adoffer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `click`
--
ALTER TABLE `click`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `masteroffer`
--
ALTER TABLE `masteroffer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `adoffer`
--
ALTER TABLE `adoffer`
  ADD CONSTRAINT `adoffer_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `masteroffer`
--
ALTER TABLE `masteroffer`
  ADD CONSTRAINT `masteroffer_offerid` FOREIGN KEY (`offerid`) REFERENCES `adoffer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `masteroffer_userid` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
