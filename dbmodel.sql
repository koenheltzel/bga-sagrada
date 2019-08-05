
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Sagrada implementation : © Koen Heltzel <koenheltzel@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';





ALTER TABLE `player`
    ADD COLUMN `sag_patterns` VARCHAR(20) NULL DEFAULT NULL COMMENT 'comma separated',
    ADD COLUMN `sag_private_objectives` VARCHAR(20) NOT NULL COMMENT 'comma separated',
    ADD COLUMN `sag_tokens` TINYINT(3) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `global`
    CHANGE COLUMN `global_value` `global_value` VARCHAR(20);





--
-- Table structure for table `sag_boardspace`
--

CREATE TABLE `sag_boardspace` (
                                  `player_no` int(10) NOT NULL,
                                  `x` tinyint(1) UNSIGNED NOT NULL,
                                  `y` tinyint(1) UNSIGNED NOT NULL,
                                  `die_color` char(1) DEFAULT NULL,
                                  `die_value` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sag_game_tools`
--

CREATE TABLE `sag_game_tools` (
                                  `id` int(11) NOT NULL,
                                  `tool_id` tinyint(3) UNSIGNED NOT NULL,
                                  `tokens` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
                                  `die_color` char(1) DEFAULT NULL,
                                  `die_value` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sag_patterns`
--

CREATE TABLE `sag_patterns` (
                                `id` tinyint(3) UNSIGNED NOT NULL,
                                `name` varchar(50) NOT NULL,
                                `difficulty` tinyint(4) NOT NULL,
                                `pattern` varchar(20) NOT NULL,
                                `pair` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sag_patterns`
--

INSERT INTO `sag_patterns` (`id`, `name`, `difficulty`, `pattern`, `pair`) VALUES
(1, 'Sun\'s Glory', 6, '1PY 4PY  6Y  53 5421', 1),
(2, 'Firelight', 5, '3415  62 Y   YR5 YR6', 1),
(3, 'Shadow Thief', 5, '6P  55 P  R6 P YR563', 2),
(4, 'Fulgor del Cielo', 5, ' BR   45 BB2 R56R31 ', 3),
(5, 'Aurora Sagradis', 4, 'R B Y4P3G2 1 5   6  ', 4),
(6, 'Lux Mundi', 6, '  1  1G3B2B546G B5G ', 5),
(7, 'Kaleidoscopic Dream', 4, 'YB  1G 5 43 R G2  BY', 6),
(8, 'Sun Catcher', 3, ' B2 Y 4 R   5Y G3  P', 2),
(9, 'Luz Celestial', 3, '  R5 P4 G36  B  Y2  ', 3),
(10, 'Aurorae Magnificus', 5, '5GBP2P   YY 6 P1  G4', 4),
(11, 'Lux Astram', 5, ' 1GP46P25G1G53P     ', 5),
(12, 'Firmitas', 5, 'P6  35P3   2P1  15P4', 6),
(13, 'Gravitas', 5, '1 3B  2B  6B 4 B52 1', 7),
(14, 'Batllo', 5, '  6   5B4 3GYP214R53', 8),
(15, 'Virtus', 5, '4 25G  6G2 3G4 5G1  ', 9),
(16, 'Ripples of Light', 5, '   R5  P4B B3Y6Y2G1R', 10),
(17, 'Via Lux', 4, 'Y 6   15 23YRP   43R', 11),
(18, 'Chromatic Splendor', 4, '  G  2Y5B1 R3P 1 6 4', 12),
(19, 'Water of Life', 6, '6B  1 5B  4R2B G6Y3P', 7),
(20, 'BellesGuard', 3, 'B6  Y 3B   562  4 1G', 8),
(21, 'Symphony of Light', 6, '2 5 1Y6P2R B4G  3 5 ', 9),
(22, 'Fractal Drops', 3, ' 4 Y6R 2    RP1BY   ', 10),
(23, 'Industria', 5, '1R3 654R2   5R1   3R', 11),
(24, 'Comitas', 5, 'Y 2 6 4 5Y   Y512Y3 ', 12);

--
-- Indexes for table `sag_patterns`
--
ALTER TABLE `sag_patterns`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `sag_patterns`
--
ALTER TABLE `sag_patterns`
    MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

-- --------------------------------------------------------

--
-- Table structure for table `sag_publicobjectives`
--

CREATE TABLE `sag_publicobjectives` (
                                        `id` tinyint(3) UNSIGNED NOT NULL,
                                        `name` varchar(50) NOT NULL,
                                        `description` varchar(255) NOT NULL,
                                        `points` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sag_publicobjectives`
--

INSERT INTO `sag_publicobjectives` (`id`, `name`, `description`, `points`) VALUES
(1, 'Light Shades', 'Sets of 1 & 2 values anywhere', 2),
(2, 'Medium Shades', 'Sets of 3 & 4 values anywhere', 2),
(3, 'Deep Shades', 'Sets of 5 & 6 values anywhere', 2),
(4, 'Shade Variety', 'Sets of one of each value anywhere', 5),
(5, 'Row Shade Variety', 'Rows with no repeated values', 5),
(6, 'Column Shade Variety', 'Columns with no repeated values', 4),
(7, 'Row Color Variety', 'Rows with no repeated colors', 6),
(8, 'Column Color Variety', 'Columns with no repeated colors', 5),
(9, 'Color Variety', 'Sets of one of each color anywhere', 4),
(10, 'Color Diagonals', 'Count of diagonally adjacent same-color dice', 0);

--
-- Indexes for table `sag_publicobjectives`
--
ALTER TABLE `sag_publicobjectives`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `sag_publicobjectives`
--
ALTER TABLE `sag_publicobjectives`
    MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

-- --------------------------------------------------------

--
-- Table structure for table `sag_roundtrack`
--

CREATE TABLE `sag_roundtrack` (
                                  `id` int(10) NOT NULL,
                                  `round` tinyint(1) UNSIGNED NOT NULL COMMENT 'A round can have multiple dice, which is why "round" is not primary key, but instead we use an auto incremented "id".',
                                  `die_color` char(1) DEFAULT NULL,
                                  `die_value` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `sag_roundtrack`
--
ALTER TABLE `sag_roundtrack`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `sag_roundtrack`
--
ALTER TABLE `sag_roundtrack`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Table structure for table `sag_tools`
--

CREATE TABLE `sag_tools` (
                             `id` tinyint(3) UNSIGNED NOT NULL,
                             `name` varchar(50) NOT NULL,
                             `description` text NOT NULL,
                             `die_color` char(1) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sag_tools`
--

INSERT INTO `sag_tools` (`id`, `name`, `description`, `die_color`) VALUES
(1, 'Grozing Pliers', 'After drafting, increase of decrease the value of the drafted die by 1 (1 may not change to 6, or 6 to 1).', 'P'),
(2, 'Eglomise Brush', '', 'B'),
(3, 'Copper Foil Burnisher', '', 'R'),
(4, 'Lathekin', '', 'Y'),
(5, 'Lens Cutter', '', 'G'),
(6, 'Flux Brush', '', 'P'),
(7, 'Glazing Hammer', '', 'B'),
(8, 'Running Pliers', '', 'R'),
(9, 'Cork-backed Straightedge', '', 'Y'),
(10, 'Grinding Stone', '', 'G'),
(11, 'Flux Remover', '', 'P'),
(12, 'Tap Wheel', '', 'B');

--
-- Indexes for table `sag_tools`
--
ALTER TABLE `sag_tools`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `sag_tools`
--
ALTER TABLE `sag_tools`
    MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tabelstructuur voor tabel `sag_draftpool`
--

CREATE TABLE `sag_draftpool` (
                                 `id` int(10) NOT NULL,
                                 `die_color` char(1) DEFAULT NULL,
                                 `die_value` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor tabel `sag_draftpool`
--
ALTER TABLE `sag_draftpool`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor een tabel `sag_draftpool`
--
ALTER TABLE `sag_draftpool`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;