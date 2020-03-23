-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Okt 2019 pada 22.55
-- Versi server: 10.1.35-MariaDB
-- Versi PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arjunane_chat2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `name`, `password`, `level`, `status`) VALUES
(1, 'admin', 'Super Admin', '2911a08c8fc0532e592ed504d8c3bae1fec9f9b222b6126aa6ee1beed3f5d648b8e8574940f322e897c4494b47b67278ca107b9d0942ac184c5d1a41ed42a3a87gNHrT8M/ueHPk+Z/7jVOW2RJnC1d4RBHPHJmnMUmqc=', 1, 0),
(2, 'admin2', 'TamVan', '2911a08c8fc0532e592ed504d8c3bae1fec9f9b222b6126aa6ee1beed3f5d648b8e8574940f322e897c4494b47b67278ca107b9d0942ac184c5d1a41ed42a3a87gNHrT8M/ueHPk+Z/7jVOW2RJnC1d4RBHPHJmnMUmqc=', 2, 2),
(3, 'admin3', 'TamVan dan maVan', '2911a08c8fc0532e592ed504d8c3bae1fec9f9b222b6126aa6ee1beed3f5d648b8e8574940f322e897c4494b47b67278ca107b9d0942ac184c5d1a41ed42a3a87gNHrT8M/ueHPk+Z/7jVOW2RJnC1d4RBHPHJmnMUmqc=', 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  `msg_from` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `chat`
--

INSERT INTO `chat` (`id`, `id_admin`, `id_user`, `message`, `date`, `msg_from`) VALUES
(73, 3, 46, 'Welcome to our WebChat, what can we do for you?', '2018-09-07 06:39:52', 1),
(74, 3, 46, 'What can I do for you, Sir?\r\n', '2018-09-07 06:40:27', 1),
(75, 3, 46, 'nothing\r\n', '2018-09-07 06:40:31', 2),
(76, 3, 46, 'thanks\r\n', '2018-09-07 06:40:37', 2),
(77, 3, 46, 'Oke, thank you so much!\r\n', '2018-09-07 06:40:46', 1),
(78, 3, 46, 'please leave high rate\r\n', '2018-09-07 06:40:52', 1),
(79, 3, 46, 'i don\'t think so\r\n', '2018-09-07 06:41:00', 2),
(80, 3, 46, 'ok\r\n', '2018-09-07 06:41:09', 2),
(81, 3, 46, 'see you later\r\n', '2018-09-07 06:41:13', 2),
(82, 3, 46, 'see ya\r\n', '2018-09-07 06:41:17', 1),
(83, 3, 46, 'If you don\'t have to say, please leave the chat!\r\n', '2018-09-07 06:41:37', 1),
(84, 3, 46, 'Oke\r\n', '2018-09-07 06:46:14', 1),
(85, 3, 46, 'ses ya\r\n', '2018-09-07 06:46:32', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `star` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `review`
--

INSERT INTO `review` (`id`, `id_admin`, `id_user`, `date`, `star`, `message`) VALUES
(13, 2, 32, '2018-09-05 14:49:02', 5, 'Nice bro'),
(14, 2, 34, '2018-09-05 15:49:29', 4, 'dfdfsd'),
(15, 2, 33, '2018-09-05 16:04:51', 4, 'asdfsdaafsda'),
(16, 2, 36, '2018-09-05 16:05:54', 5, 'asdasdas'),
(17, 2, 37, '2018-09-05 16:06:06', 3, 'asdasdas'),
(19, 2, 38, '2018-09-06 04:45:31', 1, 'i don\'t have a reason'),
(20, 3, 46, '2018-09-07 06:47:03', 5, 'Hyper Good Bro');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `telephon` varchar(16) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `delete_by_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `address`, `telephon`, `date`, `status`, `delete_by_admin`) VALUES
(46, 'Strong', 'dimazuchihabellamy@gmail.com', 'sdfsdfsdfsd', '12321', '2018-09-07 06:39:52', 5, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin` (`id_admin`,`id_user`),
  ADD KEY `user` (`id_user`);

--
-- Indeks untuk tabel `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT untuk tabel `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
