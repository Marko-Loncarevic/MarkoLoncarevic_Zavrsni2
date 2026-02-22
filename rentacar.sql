-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 11:46 AM
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
(2018, 45000, 'DA123AB', 50),
(2017, 60000, 'DA456CD', 51),
(2019, 30000, 'DA789EF', 52),
(2020, 25000, 'DA234GH', 53),
(2016, 80000, 'DA567JK', 54),
(2018, 45000, 'DA890LM', 55),
(2021, 20000, 'DA678QS', 57),
(2015, 90000, 'DA901TU', 58),
(2020, 54000, 'DA782HJ', 63),
(2020, 121212, 'DA782HJ', 64),
(1, 1, 'DA782HJ', 65);

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
(7, 'Karlo', 'Vuković', 'karlo.vukovic@gmail.com'),
(8, 'Marija', 'Božić', 'marija.bozic@gmail.com'),
(9, 'Josip', 'Pavlović', 'josip.pavlovic@gmail.com'),
(10, 'Matej', 'Knežević', 'mate.knezevicj@gmail.com'),
(35, 'Marko', 'Loncarevic', 'marko.loncarevic@gmail.com');

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
  `StatusRezervacije` varchar(20) DEFAULT 'aktivna'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervacije`
--

INSERT INTO `rezervacije` (`IDRezervacija`, `VoziloID`, `KorisnikID`, `DatumRezervacije`, `DatumPocetka`, `DatumZavrsetka`, `UkupnaCijena`, `StatusRezervacije`) VALUES
(56, 50, 1, '2025-03-29 16:17:27', '2025-03-01 10:00:00', '2025-03-04 10:00:00', 150.00, 'Zavrsena'),
(57, 51, 2, '2025-03-29 16:17:27', '2025-03-05 09:00:00', '2025-03-10 17:00:00', 250.00, 'Zavrsena'),
(58, 52, 3, '2025-03-29 16:17:27', '2025-03-09 16:00:00', '2025-03-11 20:00:00', 100.00, 'Zavrsena'),
(59, 53, 4, '2025-03-29 16:17:27', '2025-03-15 08:00:00', '2025-03-22 08:00:00', 490.00, 'Zavrsena'),
(62, 50, 1, '2025-03-29 16:29:27', '2025-03-29 16:28:00', '2025-03-31 16:28:00', 100.00, 'Zavrsena'),
(65, 58, 1, '2025-03-30 13:58:24', '2025-03-30 13:58:00', '2025-03-31 13:58:00', 50.00, 'Zavrsena'),
(67, 52, 5, '2025-03-30 13:59:20', '2025-03-30 13:59:00', '2025-03-31 13:59:00', 70.00, 'Zavrsena'),
(68, 58, 1, '2025-03-30 13:59:46', '2025-03-30 13:59:00', '2025-03-31 13:59:00', 50.00, 'Zavrsena'),
(70, 51, 1, '2025-03-30 14:02:03', '2025-03-29 14:02:00', '2025-03-31 14:02:00', 120.00, 'Zavrsena'),
(81, 57, 1, '2025-04-07 17:29:49', '2025-04-07 17:29:00', '2025-04-09 17:29:00', 100.00, 'Zavrsena'),
(83, 63, 9, '2025-05-26 19:27:27', '2025-05-26 19:27:00', '2025-05-28 19:27:00', 246.00, 'Zavrsena'),
(84, 58, 2, '2026-01-09 17:21:31', '2026-01-09 17:21:00', '2026-01-11 17:21:00', 100.00, 'Zavrsena');

-- --------------------------------------------------------

--
-- Table structure for table `vozila`
--

CREATE TABLE `vozila` (
  `IDVozilo` int(11) NOT NULL,
  `Naziv` char(25) NOT NULL,
  `Model` char(25) DEFAULT NULL,
  `CijenaKoristenjaDnevno` float NOT NULL,
  `Raspolozivost` enum('Dostupno','Nije dostupno') NOT NULL DEFAULT 'Dostupno'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vozila`
--

INSERT INTO `vozila` (`IDVozilo`, `Naziv`, `Model`, `CijenaKoristenjaDnevno`, `Raspolozivost`) VALUES
(50, 'Fiat', 'Punto', 50, 'Dostupno'),
(51, 'Opel', 'Corsa', 60, 'Dostupno'),
(52, 'Volkswagen', 'Golf', 70, 'Dostupno'),
(53, 'Mazda', 'CX5', 50, 'Dostupno'),
(54, 'Opel', 'Astra', 50, 'Dostupno'),
(55, 'Fiat', 'Punto', 50, 'Dostupno'),
(57, 'Volkswagen', 'Polo', 50, 'Dostupno'),
(58, 'Fiat', 'Panda', 50, 'Dostupno'),
(63, 'Mercedes', 'A klasa', 123, 'Dostupno'),
(64, 'Mazda', 'cx 30', 23, 'Dostupno'),
(65, 'asa', 'as', 1, 'Dostupno');

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
(1, 64, 'uploads/vehicles/vehicle_64_6977ac333edea0.22675119.png', 1, '2026-01-26 18:02:27'),
(4, 55, 'uploads/vehicles/vehicle_55_6977ac6e9ab833.42923598.png', 0, '2026-01-26 18:03:26'),
(11, 55, 'uploads/vehicles/vehicle_55_6977cd7d4458a2.05432875.png', 0, '2026-01-26 20:24:29'),
(12, 58, 'uploads/vehicles/vehicle_58_6977cd8543bd87.38488696.png', 0, '2026-01-26 20:24:37'),
(13, 65, 'uploads/vehicles/vehicle_65_6977cdae3a6876.69956330.png', 1, '2026-01-26 20:25:18');

--
-- Indexes for dumped tables
--

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
  ADD KEY `fk_rezervacije_vozilo` (`VoziloID`);

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
-- AUTO_INCREMENT for table `karakteristike_automobila`
--
ALTER TABLE `karakteristike_automobila`
  MODIFY `VoziloID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `IDKorisnici` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `rezervacije`
--
ALTER TABLE `rezervacije`
  MODIFY `IDRezervacija` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `vozila`
--
ALTER TABLE `vozila`
  MODIFY `IDVozilo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `vozila_slike`
--
ALTER TABLE `vozila_slike`
  MODIFY `IDSlika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
