-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `www.tagpromapmaker.com`
--

-- --------------------------------------------------------

--
-- Table structure for table `_Brush`
--

CREATE TABLE IF NOT EXISTS `_Brush` (
  `pk_ID` int(12) unsigned NOT NULL,
  `vc_Reference` varchar(32) NOT NULL,
  `vc_Title` varchar(32) NOT NULL,
  `vc_Image20` varchar(64) NOT NULL,
  `vc_Image40` varchar(64) NOT NULL,
  `vc_Hex` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_Map`
--

CREATE TABLE IF NOT EXISTS `_Map` (
  `pk_ID` int(12) unsigned NOT NULL,
  `fk_UserID` int(12) unsigned NOT NULL,
  `vc_MapCode` varchar(32) NOT NULL,
  `vc_Name` varchar(64) NOT NULL,
  `vc_Author` varchar(64) NOT NULL,
  `dt_Created` datetime NOT NULL,
  `dt_Updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_Map_Cells`
--

CREATE TABLE IF NOT EXISTS `_Map_Cells` (
  `fk_MapID` int(12) unsigned NOT NULL,
  `fk_CellID` int(12) unsigned NOT NULL,
  `fk_BrushID` int(12) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_Map_Switches`
--

CREATE TABLE IF NOT EXISTS `_Map_Switches` (
  `fk_MapID` int(12) unsigned NOT NULL,
  `fk_ButtonCellID` int(12) unsigned NOT NULL,
  `fk_CellID` int(12) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_User`
--

CREATE TABLE IF NOT EXISTS `_User` (
  `pk_ID` int(12) unsigned NOT NULL,
  `fk_GoogleID` decimal(21,0) NOT NULL,
  `vc_Name` varchar(60) NOT NULL,
  `vc_Email` varchar(60) NOT NULL,
  `vc_Link` varchar(60) NOT NULL,
  `vc_Image` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `_Brush`
--
ALTER TABLE `_Brush`
  ADD PRIMARY KEY (`pk_ID`);

--
-- Indexes for table `_Map`
--
ALTER TABLE `_Map`
  ADD PRIMARY KEY (`pk_ID`), ADD KEY `fk_UserID` (`fk_UserID`);

--
-- Indexes for table `_Map_Cells`
--
ALTER TABLE `_Map_Cells`
  ADD KEY `fk_MapID` (`fk_MapID`,`fk_CellID`,`fk_BrushID`);

--
-- Indexes for table `_Map_Switches`
--
ALTER TABLE `_Map_Switches`
  ADD KEY `fk_MapID` (`fk_MapID`,`fk_ButtonCellID`,`fk_CellID`);

--
-- Indexes for table `_User`
--
ALTER TABLE `_User`
  ADD PRIMARY KEY (`pk_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `_Brush`
--
ALTER TABLE `_Brush`
  MODIFY `pk_ID` int(12) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_Map`
--
ALTER TABLE `_Map`
  MODIFY `pk_ID` int(12) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_User`
--
ALTER TABLE `_User`
  MODIFY `pk_ID` int(12) unsigned NOT NULL AUTO_INCREMENT;

---
--- INSERT A TEST USER INTO THE ACCOUNT
---
INSERT INTO _User( pk_ID, vc_Name, vc_Email )
VALUES ('', 'Test Account', 'test@tagpromapmaker.com' );

