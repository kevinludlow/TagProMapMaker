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


-- phpMyAdmin SQL Dump
-- version 4.3.10
-- http://www.phpmyadmin.net
--
-- Host: voterblocks.cvcss2cfh3fh.us-west-2.rds.amazonaws.com:3306
-- Generation Time: Sep 21, 2015 at 01:01 AM
-- Server version: 5.6.23-log
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
  `vc_Hex` varchar(6) NOT NULL,
  `int_Order` int(10) unsigned NOT NULL,
  `bool_Active` tinyint(3) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_Brush`
--

INSERT INTO `_Brush` (`pk_ID`, `vc_Reference`, `vc_Title`, `vc_Image20`, `vc_Image40`, `vc_Hex`, `int_Order`, `bool_Active`) VALUES
(1, 'background', 'Background', 'icons/20/20_black.png', 'icons/40/black.png', '000000', 1, 1),
(2, 'wall', 'Wall', 'icons/20/20_wall.png', 'icons/40/wall.png', '787878', 2, 1),
(3, 'tile', 'Tile', 'icons/20/20_floor_blank.png', 'icons/40/floor_blank.png', 'd4d4d4', 3, 1),
(4, 'red_flag', 'Red Flag', 'icons/20/20_flag_red.png', 'icons/40/flag_red.png', 'ff0000', 12, 1),
(5, 'blue_flag', 'Blue Flag', 'icons/20/20_flag_blue.png', 'icons/40/flag_blue.png', '0000ff', 16, 1),
(6, 'boost', 'Speed Booster', 'icons/20/20_floor_speed.png', 'icons/40/floor_speed.png', 'ffff00', 19, 1),
(7, 'button', 'Button', 'icons/20/20_button.png', 'icons/40/button.png', 'b97a57', 17, 1),
(8, 'gate', 'Gated Floor', 'icons/20/20_floor_switch_off.png', 'icons/40/floor_switch_off.png', '007500', 18, 1),
(9, 'gate', 'Hot Gated Floor', 'icons/20/20_floor_switch_green.png', 'icons/40/floor_switch_green.png', '007500', 0, 0),
(10, 'spike', 'Mine', 'icons/20/20_mine.png', 'icons/40/mine.png', '373737', 21, 1),
(11, 'bomb', 'Bomb', 'icons/20/20_bomb.png', 'icons/40/bomb.png', 'ff8000', 22, 1),
(12, 'powerup', 'Weapon', 'icons/20/20_weapon.png', 'icons/40/weapon.png', '00ff00', 23, 1),
(13, 'blue_speed_tile', 'Blue Speed Tile', 'icons/20/20_floor_blue.png', 'icons/40/floor_blue.png', 'bbb8dd', 13, 1),
(14, 'red_speed_tile', 'Red Speed Tile', 'icons/20/20_floor_red.png', 'icons/40/floor_red.png', 'dcbaba', 9, 1),
(15, 'yellow_flag', 'Yellow Flag', '', '', '808000', 20, 1),
(16, 'red_end_zone', 'Red End Zone', '', '', 'b90000', 10, 1),
(17, 'blue_end_zone', 'Blue End Zone', '', '', '190094', 14, 1),
(18, 'red_spawn_tile', 'Red Speed Tile', '', '', 'dcbaba', 0, 0),
(21, 'blue_spawn_tile', 'Blue Spawn Tile', '', '', '00009b', 0, 0),
(22, 'portal', 'Portal', '', '', 'cac000', 4, 1),
(23, 'red_boost', 'Red Boost', '', '', 'ff7373', 11, 1),
(24, 'blue_boost', 'Blue Boost', '', '', '7373ff', 15, 1),
(25, 'tile_045', '45 Degree Wall', '', '', '807040', 5, 1),
(26, 'tile_135', '135 Degree Wall', '', '', '408050', 6, 1),
(27, 'tile_225', '225 Degree Wall', '', '', '405080', 7, 1),
(28, 'tile_315', '315 Degree Wall', '', '', '804070', 8, 1),
(29, 'red_potato', 'Red Potato', '', '', 'ff8080', 0, 0),
(30, 'blue_potato', 'Blue Potato', '', '', '8080ff', 0, 0),
(31, 'yellow_potato', 'Yellow Potato', '', '', '656500', 0, 0),
(32, 'gravity_well', 'Gravity Well', '', '', '202020', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `_Brush`
--
ALTER TABLE `_Brush`
  ADD PRIMARY KEY (`pk_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `_Brush`
--
ALTER TABLE `_Brush`
  MODIFY `pk_ID` int(12) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;


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

