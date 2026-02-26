-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 06:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentacar`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ime` varchar(25) NOT NULL,
  `prezime` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `ime`, `prezime`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Dunja', 'Dunjic', 'dunja@gmail.com', '$2y$10$W1UXxP0RFse8ej5B93skxO4yqME1sN3/s2TM/1BI2jLuNmQF3Twla', '2026-02-21 23:39:20'),
(2, 'Tin', 'Ujević', 'tin@gmail.com', '$2y$10$9Klj4s1aogXobYnZSMP.HOFhZc5lVgoiGdNQ3bquwI6QtbElpzq2W', '2026-02-21 23:58:35'),
(3, 'Marko', 'Lončarević', 'loncarevicmarko809@gmail.com', '$2y$10$bE9SoaUOnv9s1pW32YGhFuDS2r/XYrq8XehEiN.GZcg12Hr0G2g9.', '2026-02-22 13:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `karakteristike_automobila`
--

CREATE TABLE `karakteristike_automobila` (
  `Godiste` int(11) DEFAULT NULL,
  `Kilometraza` int(11) DEFAULT NULL,
  `Registracija` varchar(11) DEFAULT NULL,
  `VoziloID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karakteristike_automobila`
--

INSERT INTO `karakteristike_automobila` (`Godiste`, `Kilometraza`, `Registracija`, `VoziloID`) VALUES
(2019, 30000, 'DA789EF', 52),
(2016, 80000, 'DA567JK', 54),
(2021, 20000, 'DA678QS', 57),
(2020, 54000, 'DA782HJ', 63),
(2020, 121212, 'DA782HJ', 64),
(2025, 1900, 'DA521JJ', 66),
(2021, 90100, 'DA923LK', 68);

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `IDKorisnici` int(11) NOT NULL,
  `ImeKorisnika` char(25) NOT NULL,
  `PrezimeKorisnika` char(25) NOT NULL,
  `KontaktKorisnika` char(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`IDKorisnici`, `ImeKorisnika`, `PrezimeKorisnika`, `KontaktKorisnika`) VALUES
(1, 'Ivan', 'Horvat', 'ivan.horvat@gmail.com'),
(2, 'Ana', 'Kovačić', 'ana.kovacic@gmail.com'),
(3, 'Marko', 'Novak', 'marko.novak@gmail.com'),
(4, 'Lucija', 'Marić', 'lucija.maric@gmail.com'),
(5, 'Petar', 'Babić', 'petar.babic@gmail.com'),
(8, 'Marija', 'Božić', 'marija.bozic@gmail.com'),
(9, 'Josip', 'Pavlović', 'josip.pavlovic@gmail.com'),
(10, 'Matej', 'Knežević', 'mate.knezevicj@gmail.com'),
(35, 'Marko', 'Loncarevic', 'marko.loncarevic@gmail.com'),
(36, 'Marko', 'Maric', 'markomaric@gmail.com'),
(37, 'Ivo', 'Ivic', 'ivo@gmail.com'),
(38, 'Karlo', 'Kar', 'kr@gmail.com'),
(39, 'Marko', 'Maric', 'loncarevicmarko809@gmail.com'),
(40, 'Karlo', 'Maric', 'karlo@gmail.com'),
(41, 'mario', 'maric', 'mario@gmail.com'),
(43, 'fds', 'fs', 'sdf@gmail.com'),
(44, 'ds', 'ds', 'dsa@gmail.com'),
(46, 'sa', 'asl', 'asas@gmail.com'),
(47, 'sa', 'asas', 'sasaS@gmail.com'),
(48, 'Visnja', 'Visnjic', 'visnja@gmail.com'),
(49, 'Dunja', 'Dunjic', 'dunja@gmail.com'),
(50, 'Tin', 'Ujević', 'tin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `rezervacije`
--

CREATE TABLE `rezervacije` (
  `IDRezervacija` int(11) NOT NULL,
  `VoziloID` int(11) NOT NULL,
  `KorisnikID` int(11) NOT NULL,
  `DatumRezervacije` datetime NOT NULL DEFAULT current_timestamp(),
  `DatumPocetka` datetime NOT NULL,
  `DatumZavrsetka` datetime NOT NULL,
  `UkupnaCijena` decimal(10,2) NOT NULL,
  `StatusRezervacije` varchar(20) DEFAULT 'aktivna',
  `AccountID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervacije`
--

INSERT INTO `rezervacije` (`IDRezervacija`, `VoziloID`, `KorisnikID`, `DatumRezervacije`, `DatumPocetka`, `DatumZavrsetka`, `UkupnaCijena`, `StatusRezervacije`, `AccountID`) VALUES
(100, 52, 44, '2026-02-21 15:46:27', '2026-02-21 15:46:00', '2026-02-22 15:46:00', 70.00, 'Otkazana', NULL),
(102, 57, 46, '2026-02-21 18:26:54', '2026-02-22 18:26:00', '2026-02-24 18:26:00', 150.00, 'Zavrsena', NULL),
(103, 63, 47, '2026-02-21 18:27:33', '2026-02-25 18:27:00', '2026-02-28 18:27:00', 492.00, 'Otkazana', NULL),
(105, 52, 48, '2026-02-21 23:37:48', '2026-02-21 23:37:00', '2026-02-27 23:37:00', 420.00, 'Aktivna', NULL),
(106, 54, 49, '2026-02-21 23:39:46', '2026-02-21 23:39:00', '2026-02-28 23:39:00', 350.00, 'Aktivna', NULL),
(107, 57, 49, '2026-02-21 23:52:52', '2026-02-21 23:52:00', '2026-02-26 23:52:00', 250.00, 'Otkazana', 1),
(108, 63, 50, '2026-02-21 23:59:02', '2026-02-23 23:58:00', '2026-02-26 23:59:00', 492.00, 'Aktivna', 2),
(109, 64, 49, '2026-02-22 00:29:29', '2026-02-22 00:29:00', '2026-02-26 00:29:00', 92.00, 'Zavrsena', 1),
(110, 63, 49, '2026-02-22 00:29:47', '2026-02-22 00:29:00', '2026-02-22 02:29:00', 123.00, 'Zavrsena', 1),
(111, 68, 39, '2026-02-22 13:39:42', '2026-02-22 13:39:00', '2026-02-24 13:39:00', 100.00, 'Zavrsena', 3);

-- --------------------------------------------------------

--
-- Table structure for table `vozila`
--

CREATE TABLE `vozila` (
  `IDVozilo` int(11) NOT NULL,
  `Naziv` char(25) NOT NULL,
  `Model` char(25) DEFAULT NULL,
  `TipVozila` varchar(30) DEFAULT 'Limuzina',
  `CijenaKoristenjaDnevno` float NOT NULL,
  `Raspolozivost` enum('Dostupno','Nije dostupno','Rezervirano') NOT NULL DEFAULT 'Dostupno'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vozila`
--

INSERT INTO `vozila` (`IDVozilo`, `Naziv`, `Model`, `TipVozila`, `CijenaKoristenjaDnevno`, `Raspolozivost`) VALUES
(52, 'Volkswagen', 'Golf', 'Kompakt', 70, 'Nije dostupno'),
(54, 'Opel', 'Astra', 'Kompakt', 50, 'Nije dostupno'),
(57, 'Volkswagen', 'Polo', 'Kompakt', 50, 'Dostupno'),
(63, 'Mercedes', 'A klasa', 'Limuzina', 123, 'Nije dostupno'),
(64, 'Mazda', 'cx 30', 'SUV', 23, 'Dostupno'),
(66, 'BMW', '1 series', 'Limuzina', 100, 'Dostupno'),
(68, 'Fiat', 'Punto', 'Limuzina', 50, 'Dostupno');

-- --------------------------------------------------------

--
-- Table structure for table `vozila_slike`
--

CREATE TABLE `vozila_slike` (
  `IDSlika` int(11) NOT NULL,
  `VoziloID` int(11) NOT NULL,
  `PutanjaSlike` varchar(255) NOT NULL,
  `JeGlavna` tinyint(1) DEFAULT 0,
  `DatumDodavanja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vozila_slike`
--

INSERT INTO `vozila_slike` (`IDSlika`, `VoziloID`, `PutanjaSlike`, `JeGlavna`, `DatumDodavanja`) VALUES
(18, 52, 'uploads/vehicles/vehicle_52_6991b23a4ed045.49574044.webp', 1, '2026-02-15 11:47:06'),
(24, 64, 'uploads/vehicles/vehicle_64_6991cd248d0137.58174847.jpg', 1, '2026-02-15 13:41:56'),
(25, 63, 'uploads/vehicles/vehicle_63_6991cda6701e81.40410932.jpg', 1, '2026-02-15 13:44:06'),
(27, 54, 'uploads/vehicles/vehicle_54_6991cdd491ba63.33239670.jpg', 1, '2026-02-15 13:44:52'),
(28, 57, 'uploads/vehicles/vehicle_57_6991ce051a35b0.31736416.jpg', 1, '2026-02-15 13:45:41'),
(29, 66, 'uploads/vehicles/vehicle_66_6991cec41a8c71.78462457.jpg', 1, '2026-02-15 13:48:52'),
(31, 68, 'uploads/vehicles/vehicle_68_699af89e542847.49034501.jpg', 1, '2026-02-22 12:37:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `karakteristike_automobila`
--
ALTER TABLE `karakteristike_automobila`
  ADD KEY `VoziloID` (`VoziloID`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`IDKorisnici`);

--
-- Indexes for table `rezervacije`
--
ALTER TABLE `rezervacije`
  ADD PRIMARY KEY (`IDRezervacija`),
  ADD KEY `KorisnikID` (`KorisnikID`),
  ADD KEY `fk_rezervacije_vozilo` (`VoziloID`),
  ADD KEY `fk_rezervacije_account` (`AccountID`);

--
-- Indexes for table `vozila`
--
ALTER TABLE `vozila`
  ADD PRIMARY KEY (`IDVozilo`);

--
-- Indexes for table `vozila_slike`
--
ALTER TABLE `vozila_slike`
  ADD PRIMARY KEY (`IDSlika`),
  ADD KEY `VoziloID` (`VoziloID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `karakteristike_automobila`
--
ALTER TABLE `karakteristike_automobila`
  MODIFY `VoziloID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `IDKorisnici` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `rezervacije`
--
ALTER TABLE `rezervacije`
  MODIFY `IDRezervacija` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `vozila`
--
ALTER TABLE `vozila`
  MODIFY `IDVozilo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `vozila_slike`
--
ALTER TABLE `vozila_slike`
  MODIFY `IDSlika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `karakteristike_automobila`
--
ALTER TABLE `karakteristike_automobila`
  ADD CONSTRAINT `fk_karakteristike_vozilo` FOREIGN KEY (`VoziloID`) REFERENCES `vozila` (`IDVozilo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karakteristike_automobila_ibfk_1` FOREIGN KEY (`VoziloID`) REFERENCES `vozila` (`IDVozilo`);

--
-- Constraints for table `rezervacije`
--
ALTER TABLE `rezervacije`
  ADD CONSTRAINT `fk_rezervacije_account` FOREIGN KEY (`AccountID`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rezervacije_vozilo` FOREIGN KEY (`VoziloID`) REFERENCES `vozila` (`IDVozilo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rezervacije_ibfk_1` FOREIGN KEY (`VoziloID`) REFERENCES `vozila` (`IDVozilo`),
  ADD CONSTRAINT `rezervacije_ibfk_2` FOREIGN KEY (`KorisnikID`) REFERENCES `korisnici` (`IDKorisnici`);

--
-- Constraints for table `vozila_slike`
--
ALTER TABLE `vozila_slike`
  ADD CONSTRAINT `fk_slike_vozilo` FOREIGN KEY (`VoziloID`) REFERENCES `vozila` (`IDVozilo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
