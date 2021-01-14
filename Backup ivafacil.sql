/*
 Navicat Premium Data Transfer

 Source Server         : IVA facil
 Source Server Type    : MySQL
 Source Server Version : 100327
 Source Host           : 50.31.176.9:3306
 Source Schema         : sopvszmc_ivafacil

 Target Server Type    : MySQL
 Target Server Version : 100327
 File Encoding         : 65001

 Date: 14/01/2021 11:45:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nick` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pass` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `estado` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A',
  `email` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `origen` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'W',
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `session_expire` timestamp(0) NULL DEFAULT NULL,
  `remember` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N',
  `fondo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_recu` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_validez` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (12, 'admin', '$2y$10$4nuisKWHBFEgZNeCmCghcu9xDvOH2rykJoKNhcJpFcmVpLR4zb79.', 'A', 'ivafacilpy@gmail.com', 'W', '2020-12-28 21:17:30', '2020-12-28 23:17:30', '$2y$10$bTU4cQHAFPal65yZCYUhfuDeZWkbW.WAUkLaY6u3jkbTanqZB1JLa', '2021-01-01 20:44:00', 'S', 'none', NULL, NULL);
INSERT INTO `admins` VALUES (18, 'sonia', '$2y$10$J9jVLRNVH9hy4FE6TVOZbOgncsqup4NINA6IrBwbl17uDyIJ8bBEe', 'A', 'jfulla@dad.com', 'W', '2021-01-13 12:03:55', '2021-01-13 14:03:55', '$2y$10$Qb.Ypy00I9iAw3hD2nZPNOJQvAFz5O0eu2AgOWPWinCGpS7C3VNha', '2021-01-23 14:03:00', 'N', NULL, NULL, NULL);
INSERT INTO `admins` VALUES (21, 'carlos', '$2y$10$/LYrXTkO0h4icTmhTuBgneyDiDVpwHpEzyplnlTR.bwTIMKZOO.cK', 'A', 'valente.py@hotmail.com', 'W', '2021-01-13 13:25:36', '2021-01-13 15:25:36', '$2y$10$h9CC4UPIxrwzbt00rrUFWehXMjt6hqsQgEqob6wr2qD1C707HXNzy', '2021-01-23 15:25:00', 'N', 'https://www.ivafacil.com.py/ivafacil/wallpapers/fondo2.jpg', NULL, NULL);
INSERT INTO `admins` VALUES (23, '648667', '$2y$10$g6KLhBoNeQuu8cF/3OTg1ucV.Rwn7Dif.3A35GjMoD0u7zP8m7yeO', 'A', 'rafaelpallarolas@gmail.com', 'W', '2021-01-05 15:57:37', '2021-01-05 17:57:37', '$2y$10$xzL1MOYqiw/KiF455Wo/de.jK8PGz6uR5MvZUSSFGlv8AJzPrDuQ6', '2021-01-15 17:57:00', 'S', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for calendario_pagos
-- ----------------------------
DROP TABLE IF EXISTS `calendario_pagos`;
CREATE TABLE `calendario_pagos`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ultimo_d_ruc` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dia_vencimiento` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of calendario_pagos
-- ----------------------------
INSERT INTO `calendario_pagos` VALUES (1, '0', '7', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (2, '1', '9', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (3, '2', '11', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (4, '3', '13', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (5, '4', '15', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (6, '5', '17', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (7, '6', '19', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (8, '7', '21', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (9, '8', '23', NULL, '2021-01-13 14:06:57');
INSERT INTO `calendario_pagos` VALUES (10, '9', '25', NULL, '2021-01-13 14:06:57');

-- ----------------------------
-- Table structure for ciudades
-- ----------------------------
DROP TABLE IF EXISTS `ciudades`;
CREATE TABLE `ciudades`  (
  `regnro` int UNSIGNED NOT NULL,
  `departa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ciudades
-- ----------------------------
INSERT INTO `ciudades` VALUES (1, 'Central', 'Areguá');
INSERT INTO `ciudades` VALUES (2, 'Central', 'Capiatá');
INSERT INTO `ciudades` VALUES (3, 'Central', 'Fernando de la Mora');
INSERT INTO `ciudades` VALUES (4, 'Central', 'Guarambaré');
INSERT INTO `ciudades` VALUES (5, 'Central', 'Itá');
INSERT INTO `ciudades` VALUES (6, 'Central', 'Itauguá');
INSERT INTO `ciudades` VALUES (7, 'Central', 'J. Augusto Saldívar');
INSERT INTO `ciudades` VALUES (8, 'Central', 'Lambaré');
INSERT INTO `ciudades` VALUES (9, 'Central', 'Limpio');
INSERT INTO `ciudades` VALUES (10, 'Central', 'Luque');
INSERT INTO `ciudades` VALUES (11, 'Central', 'Mariano Roque Alonso');
INSERT INTO `ciudades` VALUES (12, 'Central', 'Ñemby');
INSERT INTO `ciudades` VALUES (13, 'Central', 'Nueva Italia');
INSERT INTO `ciudades` VALUES (14, 'Central', 'San Antonio');
INSERT INTO `ciudades` VALUES (15, 'Central', 'San Lorenzo');
INSERT INTO `ciudades` VALUES (16, 'Central', 'Villa Elisa');
INSERT INTO `ciudades` VALUES (17, 'Central', 'Villeta');
INSERT INTO `ciudades` VALUES (18, 'Central', 'Ypacarai');
INSERT INTO `ciudades` VALUES (19, 'Central', 'Ypané');
INSERT INTO `ciudades` VALUES (20, 'Concepcion', 'Arroyito');
INSERT INTO `ciudades` VALUES (21, 'Concepcion', 'Azotey');
INSERT INTO `ciudades` VALUES (22, 'Concepcion', 'Belén');
INSERT INTO `ciudades` VALUES (23, 'Concepcion', 'Concepción');
INSERT INTO `ciudades` VALUES (24, 'Concepcion', 'Horqueta');
INSERT INTO `ciudades` VALUES (25, 'Concepcion', 'Loreto');
INSERT INTO `ciudades` VALUES (26, 'Concepcion', 'Paso Barreto');
INSERT INTO `ciudades` VALUES (27, 'Concepcion', 'San Alfredo');
INSERT INTO `ciudades` VALUES (28, 'Concepcion', 'San Carlos del Apa');
INSERT INTO `ciudades` VALUES (29, 'Concepcion', 'San Lázaro');
INSERT INTO `ciudades` VALUES (30, 'Concepcion', 'Sargento José Félix López');
INSERT INTO `ciudades` VALUES (31, 'Concepcion', 'Yby Yau');
INSERT INTO `ciudades` VALUES (32, 'San Pedro', '25 de Diciembre');
INSERT INTO `ciudades` VALUES (33, 'San Pedro', 'Antequera');
INSERT INTO `ciudades` VALUES (34, 'San Pedro', 'Capiibary');
INSERT INTO `ciudades` VALUES (35, 'San Pedro', 'Choré');
INSERT INTO `ciudades` VALUES (36, 'San Pedro', 'Cruce Liberación');
INSERT INTO `ciudades` VALUES (37, 'San Pedro', 'Gral. Elizardo Aquino');
INSERT INTO `ciudades` VALUES (38, 'San Pedro', 'Gral. Francisco Isidoro Resquí');
INSERT INTO `ciudades` VALUES (39, 'San Pedro', 'Guajayvi');
INSERT INTO `ciudades` VALUES (40, 'San Pedro', 'Itacurubí del Rosario');
INSERT INTO `ciudades` VALUES (41, 'San Pedro', 'Lima');
INSERT INTO `ciudades` VALUES (42, 'San Pedro', 'Nueva Germania');
INSERT INTO `ciudades` VALUES (43, 'San Pedro', 'San Estanislao');
INSERT INTO `ciudades` VALUES (44, 'San Pedro', 'San Pablo');
INSERT INTO `ciudades` VALUES (45, 'San Pedro', 'San Pedro de Ycuamandiyú');
INSERT INTO `ciudades` VALUES (46, 'San Pedro', 'Santa Rosa del Aguaray');
INSERT INTO `ciudades` VALUES (47, 'San Pedro', 'Tacuatí');
INSERT INTO `ciudades` VALUES (48, 'San Pedro', 'Unión');
INSERT INTO `ciudades` VALUES (49, 'San Pedro', 'Villa del Rosario');
INSERT INTO `ciudades` VALUES (50, 'San Pedro', 'Yataity del Norte');
INSERT INTO `ciudades` VALUES (51, 'San Pedro', 'Yrybukuá');
INSERT INTO `ciudades` VALUES (52, 'Cordillera', 'Altos');
INSERT INTO `ciudades` VALUES (53, 'Cordillera', 'Arroyos y Esteros');
INSERT INTO `ciudades` VALUES (54, 'Cordillera', 'Atyrá');
INSERT INTO `ciudades` VALUES (55, 'Cordillera', 'Caacupé');
INSERT INTO `ciudades` VALUES (56, 'Cordillera', 'Caraguatay');
INSERT INTO `ciudades` VALUES (57, 'Cordillera', 'Emboscada');
INSERT INTO `ciudades` VALUES (58, 'Cordillera', 'Eusebio Ayala');
INSERT INTO `ciudades` VALUES (59, 'Cordillera', 'Isla Pucú');
INSERT INTO `ciudades` VALUES (60, 'Cordillera', 'Itacurubí de la Cordillera');
INSERT INTO `ciudades` VALUES (61, 'Cordillera', 'Juan de Mena');
INSERT INTO `ciudades` VALUES (62, 'Cordillera', 'Loma Grande');
INSERT INTO `ciudades` VALUES (63, 'Cordillera', 'Mbocayaty del Yhaguy');
INSERT INTO `ciudades` VALUES (64, 'Cordillera', 'Nueva Colombia');
INSERT INTO `ciudades` VALUES (65, 'Cordillera', 'Piribebuy');
INSERT INTO `ciudades` VALUES (66, 'Cordillera', 'Primero de Marzo');
INSERT INTO `ciudades` VALUES (67, 'Cordillera', 'San Bernardino');
INSERT INTO `ciudades` VALUES (68, 'Cordillera', 'San José Obrero');
INSERT INTO `ciudades` VALUES (69, 'Cordillera', 'Santa Elena');
INSERT INTO `ciudades` VALUES (70, 'Cordillera', 'Tobatí');
INSERT INTO `ciudades` VALUES (71, 'Cordillera', 'Valenzuela');
INSERT INTO `ciudades` VALUES (72, 'Guaira', 'Borja');
INSERT INTO `ciudades` VALUES (73, 'Guaira', 'Capitan Mauricio Jose Troche');
INSERT INTO `ciudades` VALUES (74, 'Guaira', 'Coronel Martinez');
INSERT INTO `ciudades` VALUES (75, 'Guaira', 'Doctor Botrell');
INSERT INTO `ciudades` VALUES (76, 'Guaira', 'Felix Perez Cardozo');
INSERT INTO `ciudades` VALUES (77, 'Guaira', 'General Euginio A. Garay');
INSERT INTO `ciudades` VALUES (78, 'Guaira', 'Independencia');
INSERT INTO `ciudades` VALUES (79, 'Guaira', 'Itape');
INSERT INTO `ciudades` VALUES (80, 'Guaira', 'Iturbe');
INSERT INTO `ciudades` VALUES (81, 'Guaira', 'Jose A. Fassardi');
INSERT INTO `ciudades` VALUES (82, 'Guaira', 'Mbocayaty del Guaira');
INSERT INTO `ciudades` VALUES (83, 'Guaira', 'Natalicio Talavera');
INSERT INTO `ciudades` VALUES (84, 'Guaira', 'Ñumi');
INSERT INTO `ciudades` VALUES (85, 'Guaira', 'Paso Yovai');
INSERT INTO `ciudades` VALUES (86, 'Guaira', 'San Salvador');
INSERT INTO `ciudades` VALUES (87, 'Guaira', 'Villarrica del espiritu santo');
INSERT INTO `ciudades` VALUES (88, 'Guaira', 'Yataity del Guaira');
INSERT INTO `ciudades` VALUES (89, 'Guaira', 'Tebicuary');
INSERT INTO `ciudades` VALUES (90, 'Caaguazu', 'Coronel Oviedo');
INSERT INTO `ciudades` VALUES (91, 'Caaguazu', '3 de febrero');
INSERT INTO `ciudades` VALUES (92, 'Caaguazu', 'Caaguazu');
INSERT INTO `ciudades` VALUES (93, 'Caaguazu', 'Carayao');
INSERT INTO `ciudades` VALUES (94, 'Caaguazu', 'Dr. Cecilio Baez');
INSERT INTO `ciudades` VALUES (95, 'Caaguazu', 'Dr. Juan Eulogio Estigarribia');
INSERT INTO `ciudades` VALUES (96, 'Caaguazu', 'Dr. Juan Manuel Frutos');
INSERT INTO `ciudades` VALUES (97, 'Caaguazu', 'jose Domingo Ocampos');
INSERT INTO `ciudades` VALUES (98, 'Caaguazu', 'De la Pastora');
INSERT INTO `ciudades` VALUES (99, 'Caaguazu', 'Mariscal Francisco Solano Lope');
INSERT INTO `ciudades` VALUES (100, 'Caaguazu', 'Nueva Londres');
INSERT INTO `ciudades` VALUES (101, 'Caaguazu', 'Nueva Toledo');
INSERT INTO `ciudades` VALUES (102, 'Caaguazu', 'R.I 3 corrales');
INSERT INTO `ciudades` VALUES (103, 'Caaguazu', 'Raul Arsenio Oviedo');
INSERT INTO `ciudades` VALUES (104, 'Caaguazu', 'Repatriacion');
INSERT INTO `ciudades` VALUES (105, 'Caaguazu', 'San Joaquin');
INSERT INTO `ciudades` VALUES (106, 'Caaguazu', 'San Jose de los Arroyos');
INSERT INTO `ciudades` VALUES (107, 'Caaguazu', 'Santa Rosa del Mbutuy');
INSERT INTO `ciudades` VALUES (108, 'Caaguazu', 'Simon Bolivar');
INSERT INTO `ciudades` VALUES (109, 'Caaguazu', 'Tembiapora');
INSERT INTO `ciudades` VALUES (110, 'Caaguazu', 'Vaqueria');
INSERT INTO `ciudades` VALUES (111, 'Caaguazu', 'Yhu');
INSERT INTO `ciudades` VALUES (112, 'Caazapa', 'Tres de Mayo');
INSERT INTO `ciudades` VALUES (113, 'Caazapa', 'Abaí');
INSERT INTO `ciudades` VALUES (114, 'Caazapa', 'Buena Vista');
INSERT INTO `ciudades` VALUES (115, 'Caazapa', 'Caazapá');
INSERT INTO `ciudades` VALUES (116, 'Caazapa', 'Dr. Moisés Bertoni');
INSERT INTO `ciudades` VALUES (117, 'Caazapa', 'Fulgencio Yegros');
INSERT INTO `ciudades` VALUES (118, 'Caazapa', 'General Higinio Morínigo');
INSERT INTO `ciudades` VALUES (119, 'Caazapa', 'Maciel');
INSERT INTO `ciudades` VALUES (120, 'Caazapa', 'San Juan Nepomuceno');
INSERT INTO `ciudades` VALUES (121, 'Caazapa', 'Tavaí');
INSERT INTO `ciudades` VALUES (122, 'Caazapa', 'Yuty');
INSERT INTO `ciudades` VALUES (123, 'Itapua', 'Alto Verá');
INSERT INTO `ciudades` VALUES (124, 'Itapua', 'Bella Vista');
INSERT INTO `ciudades` VALUES (125, 'Itapua', 'Cambyreta');
INSERT INTO `ciudades` VALUES (126, 'Itapua', 'Capitán Meza');
INSERT INTO `ciudades` VALUES (127, 'Itapua', 'Capitán Miranda');
INSERT INTO `ciudades` VALUES (128, 'Itapua', 'Carlos Antonio López');
INSERT INTO `ciudades` VALUES (129, 'Itapua', 'Carmen del Paraná');
INSERT INTO `ciudades` VALUES (130, 'Itapua', 'Coronel Bogado');
INSERT INTO `ciudades` VALUES (131, 'Itapua', 'Edelira');
INSERT INTO `ciudades` VALUES (132, 'Itapua', 'Encarnación');
INSERT INTO `ciudades` VALUES (133, 'Itapua', 'Fram');
INSERT INTO `ciudades` VALUES (134, 'Itapua', 'General Artigas');
INSERT INTO `ciudades` VALUES (135, 'Itapua', 'General Delgado');
INSERT INTO `ciudades` VALUES (136, 'Itapua', 'Hohenau');
INSERT INTO `ciudades` VALUES (137, 'Itapua', 'Itapúa Poty');
INSERT INTO `ciudades` VALUES (138, 'Itapua', 'Jesús');
INSERT INTO `ciudades` VALUES (139, 'Itapua', 'José Leandro Oviedo');
INSERT INTO `ciudades` VALUES (140, 'Itapua', 'La Paz');
INSERT INTO `ciudades` VALUES (141, 'Itapua', 'Mayor Otaño');
INSERT INTO `ciudades` VALUES (142, 'Itapua', 'Natalio');
INSERT INTO `ciudades` VALUES (143, 'Itapua', 'Nueva Alborada');
INSERT INTO `ciudades` VALUES (144, 'Itapua', 'Obligado');
INSERT INTO `ciudades` VALUES (145, 'Itapua', 'Pirapó');
INSERT INTO `ciudades` VALUES (146, 'Itapua', 'San Cosme y Damián');
INSERT INTO `ciudades` VALUES (147, 'Itapua', 'San Juan del Paraná');
INSERT INTO `ciudades` VALUES (148, 'Itapua', 'San Pedro del Paraná');
INSERT INTO `ciudades` VALUES (149, 'Itapua', 'San Rafael del Paraná');
INSERT INTO `ciudades` VALUES (150, 'Itapua', 'Tomás Romero Pereira');
INSERT INTO `ciudades` VALUES (151, 'Itapua', 'Trinidad');
INSERT INTO `ciudades` VALUES (152, 'Itapua', 'Yatytay');
INSERT INTO `ciudades` VALUES (153, 'Misiones', 'Ayolas');
INSERT INTO `ciudades` VALUES (154, 'Misiones', 'San Ignacio');
INSERT INTO `ciudades` VALUES (155, 'Misiones', 'San Juan Bautista');
INSERT INTO `ciudades` VALUES (156, 'Misiones', 'San Miguel');
INSERT INTO `ciudades` VALUES (157, 'Misiones', 'San Patricio');
INSERT INTO `ciudades` VALUES (158, 'Misiones', 'Santa María');
INSERT INTO `ciudades` VALUES (159, 'Misiones', 'Santa Rosa');
INSERT INTO `ciudades` VALUES (160, 'Misiones', 'Santiago');
INSERT INTO `ciudades` VALUES (161, 'Misiones', 'Villa Florida');
INSERT INTO `ciudades` VALUES (162, 'Misiones', 'Yabebyry');
INSERT INTO `ciudades` VALUES (163, 'Paraguari', 'Acahay');
INSERT INTO `ciudades` VALUES (164, 'Paraguari', 'Caapucú');
INSERT INTO `ciudades` VALUES (165, 'Paraguari', 'Carapeguá');
INSERT INTO `ciudades` VALUES (166, 'Paraguari', 'Escobar');
INSERT INTO `ciudades` VALUES (167, 'Paraguari', 'Gral. Bernardino Caballero');
INSERT INTO `ciudades` VALUES (168, 'Paraguari', 'La Colmena');
INSERT INTO `ciudades` VALUES (169, 'Paraguari', 'María Antonia');
INSERT INTO `ciudades` VALUES (170, 'Paraguari', 'Mbuyapey');
INSERT INTO `ciudades` VALUES (171, 'Paraguari', 'Paraguarí');
INSERT INTO `ciudades` VALUES (172, 'Paraguari', 'Pirayú');
INSERT INTO `ciudades` VALUES (173, 'Paraguari', 'Quiindy');
INSERT INTO `ciudades` VALUES (174, 'Paraguari', 'Quyquyhó');
INSERT INTO `ciudades` VALUES (175, 'Paraguari', 'San Roque González de Santa Cr');
INSERT INTO `ciudades` VALUES (176, 'Paraguari', 'Sapucai');
INSERT INTO `ciudades` VALUES (177, 'Paraguari', 'Tebicuarymí');
INSERT INTO `ciudades` VALUES (178, 'Paraguari', 'Yaguarón');
INSERT INTO `ciudades` VALUES (179, 'Paraguari', 'Ybycui');
INSERT INTO `ciudades` VALUES (180, 'Paraguari', 'Ybytimí');
INSERT INTO `ciudades` VALUES (181, 'Alto Parana', 'Ciudad del Este');
INSERT INTO `ciudades` VALUES (182, 'Alto Parana', 'Domingo Martinez de Irala');
INSERT INTO `ciudades` VALUES (183, 'Alto Parana', 'Dr. Juan León Mallorquín');
INSERT INTO `ciudades` VALUES (184, 'Alto Parana', 'Dr. Raul Peña');
INSERT INTO `ciudades` VALUES (185, 'Alto Parana', 'Hernandarias');
INSERT INTO `ciudades` VALUES (186, 'Alto Parana', 'Iruña');
INSERT INTO `ciudades` VALUES (187, 'Alto Parana', 'Itakyry');
INSERT INTO `ciudades` VALUES (188, 'Alto Parana', 'Juan Emilio O´Leary');
INSERT INTO `ciudades` VALUES (189, 'Alto Parana', 'Los Cedrales');
INSERT INTO `ciudades` VALUES (190, 'Alto Parana', 'Mbaracayú');
INSERT INTO `ciudades` VALUES (191, 'Alto Parana', 'Minga Guazú');
INSERT INTO `ciudades` VALUES (192, 'Alto Parana', 'Minga Porá');
INSERT INTO `ciudades` VALUES (193, 'Alto Parana', 'Ñacunday');
INSERT INTO `ciudades` VALUES (194, 'Alto Parana', 'Naranjal');
INSERT INTO `ciudades` VALUES (195, 'Alto Parana', 'Presidente Franco');
INSERT INTO `ciudades` VALUES (196, 'Alto Parana', 'San Alberto');
INSERT INTO `ciudades` VALUES (197, 'Alto Parana', 'San Cristóbal');
INSERT INTO `ciudades` VALUES (198, 'Alto Parana', 'Santa Fé del Paraná');
INSERT INTO `ciudades` VALUES (199, 'Alto Parana', 'Santa Rita');
INSERT INTO `ciudades` VALUES (200, 'Alto Parana', 'Santa Rosa del Monday');
INSERT INTO `ciudades` VALUES (201, 'Alto Parana', 'Tavapy');
INSERT INTO `ciudades` VALUES (202, 'Alto Parana', 'Yguazú');
INSERT INTO `ciudades` VALUES (203, 'Ñeembucu', 'Alberdi');
INSERT INTO `ciudades` VALUES (204, 'Ñeembucu', 'Cerrito');
INSERT INTO `ciudades` VALUES (205, 'Ñeembucu', 'Desmochados');
INSERT INTO `ciudades` VALUES (206, 'Ñeembucu', 'Gral. José Eduvigis Díaz');
INSERT INTO `ciudades` VALUES (207, 'Ñeembucu', 'Guazú Cuá');
INSERT INTO `ciudades` VALUES (208, 'Ñeembucu', 'Humaitá');
INSERT INTO `ciudades` VALUES (209, 'Ñeembucu', 'Isla Umbú');
INSERT INTO `ciudades` VALUES (210, 'Ñeembucu', 'Laureles');
INSERT INTO `ciudades` VALUES (211, 'Ñeembucu', 'Mayor José D. Martínez');
INSERT INTO `ciudades` VALUES (212, 'Ñeembucu', 'Paso de Patria');
INSERT INTO `ciudades` VALUES (213, 'Ñeembucu', 'Pilar');
INSERT INTO `ciudades` VALUES (214, 'Ñeembucu', 'San Juan B. de Ñeembucú');
INSERT INTO `ciudades` VALUES (215, 'Ñeembucu', 'Tacuaras');
INSERT INTO `ciudades` VALUES (216, 'Ñeembucu', 'Villa Franca');
INSERT INTO `ciudades` VALUES (217, 'Ñeembucu', 'Villa Oliva');
INSERT INTO `ciudades` VALUES (218, 'Ñeembucu', 'Villalbín');
INSERT INTO `ciudades` VALUES (219, 'Amambay', 'Bella Vista');
INSERT INTO `ciudades` VALUES (220, 'Amambay', 'Capitán Bado');
INSERT INTO `ciudades` VALUES (221, 'Amambay', 'Karapai');
INSERT INTO `ciudades` VALUES (222, 'Amambay', 'Pedro Juan Caballero');
INSERT INTO `ciudades` VALUES (223, 'Amambay', 'Zanja Pyta');
INSERT INTO `ciudades` VALUES (224, 'Canindeyu', 'Corpus Christi');
INSERT INTO `ciudades` VALUES (225, 'Canindeyu', 'Gral. Francisco Caballero Alva');
INSERT INTO `ciudades` VALUES (226, 'Canindeyu', 'Itanará');
INSERT INTO `ciudades` VALUES (227, 'Canindeyu', 'Katueté');
INSERT INTO `ciudades` VALUES (228, 'Canindeyu', 'La Paloma');
INSERT INTO `ciudades` VALUES (229, 'Canindeyu', 'Nueva Esperanza');
INSERT INTO `ciudades` VALUES (230, 'Canindeyu', 'Salto del Guairá');
INSERT INTO `ciudades` VALUES (231, 'Canindeyu', 'Villa San Isidro Labrador del ');
INSERT INTO `ciudades` VALUES (232, 'Canindeyu', 'Villa Ygatimi');
INSERT INTO `ciudades` VALUES (233, 'Canindeyu', 'Yasy Kañý');
INSERT INTO `ciudades` VALUES (234, 'Canindeyu', 'Yby Pyta');
INSERT INTO `ciudades` VALUES (235, 'Canindeyu', 'Ybyrarovaná');
INSERT INTO `ciudades` VALUES (236, 'Canindeyu', 'Ypejhú');
INSERT INTO `ciudades` VALUES (237, 'Presidente Hayes', 'Benjamín Aceval');
INSERT INTO `ciudades` VALUES (238, 'Presidente Hayes', 'Gral. José María Bruguez');
INSERT INTO `ciudades` VALUES (239, 'Presidente Hayes', 'Nanawa');
INSERT INTO `ciudades` VALUES (240, 'Presidente Hayes', 'Puerto José Falcón');
INSERT INTO `ciudades` VALUES (241, 'Presidente Hayes', 'Puerto Pinasco');
INSERT INTO `ciudades` VALUES (242, 'Presidente Hayes', 'Tte. 1º Manuel Irala Fernández');
INSERT INTO `ciudades` VALUES (243, 'Presidente Hayes', 'Tte. Esteban Martínez');
INSERT INTO `ciudades` VALUES (244, 'Presidente Hayes', 'Villa Hayes');
INSERT INTO `ciudades` VALUES (245, 'Alto Paraguay', 'Bahía Negra');
INSERT INTO `ciudades` VALUES (246, 'Alto Paraguay', 'Carmelo Peralta');
INSERT INTO `ciudades` VALUES (247, 'Alto Paraguay', 'Fuerte Olimpo');
INSERT INTO `ciudades` VALUES (248, 'Alto Paraguay', 'Puerto Casado');
INSERT INTO `ciudades` VALUES (249, 'Boqueron', 'Filadelfia');
INSERT INTO `ciudades` VALUES (250, 'Boqueron', 'Loma Plata');
INSERT INTO `ciudades` VALUES (251, 'Boqueron', 'Mariscal José Félix Estigarribia');

-- ----------------------------
-- Table structure for compras
-- ----------------------------
DROP TABLE IF EXISTS `compras`;
CREATE TABLE `compras`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dv` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `codcliente` int UNSIGNED NOT NULL DEFAULT 0,
  `fecha` date NULL DEFAULT NULL,
  `factura` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `moneda` int UNSIGNED NULL DEFAULT NULL,
  `tcambio` double(10, 0) UNSIGNED NULL DEFAULT NULL,
  `importe1` double(20, 0) UNSIGNED NOT NULL DEFAULT 0,
  `importe2` double(20, 0) UNSIGNED NOT NULL DEFAULT 0,
  `importe3` double(20, 0) UNSIGNED NOT NULL DEFAULT 0,
  `total` double(20, 0) UNSIGNED NULL DEFAULT 0,
  `iva1` double(15, 0) UNSIGNED NULL DEFAULT 0,
  `iva2` double(15, 0) UNSIGNED NULL DEFAULT 0,
  `iva3` double(15, 0) UNSIGNED NULL DEFAULT 0,
  `origen` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `ruc`, `codcliente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of compras
-- ----------------------------
INSERT INTO `compras` VALUES (56, '648667', '3', 4, '2021-01-13', '123', 1, 0, 110000, 105000, 0, 215000, 10000, 5000, 0, 'W', '2021-01-13 12:17:00', '2021-01-13 12:17:00');
INSERT INTO `compras` VALUES (57, '648667', '3', 4, '2021-01-13', '1254', 1, 0, 155855, 25254, 0, 181109, 14169, 1203, 0, 'W', '2021-01-13 12:18:53', '2021-01-13 12:18:53');
INSERT INTO `compras` VALUES (58, '648667', '3', 4, '2021-01-13', '12525', 1, 0, 151522, 25252, 0, 176774, 13775, 1202, 0, 'W', '2021-01-13 12:19:24', '2021-01-13 12:19:24');
INSERT INTO `compras` VALUES (59, '4747132', '7', 1, '2021-01-13', '001-002-0065555', 1, 0, 500000, 0, 0, 500000, 45455, 0, 0, 'W', '2021-01-13 15:52:59', '2021-01-13 15:52:59');

-- ----------------------------
-- Table structure for estado_anio
-- ----------------------------
DROP TABLE IF EXISTS `estado_anio`;
CREATE TABLE `estado_anio`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dv` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codcliente` int UNSIGNED NOT NULL,
  `anio` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `t_i_compras` int UNSIGNED NULL DEFAULT NULL,
  `t_i_ventas` int UNSIGNED NULL DEFAULT NULL,
  `t_retencion` int UNSIGNED NULL DEFAULT NULL,
  `saldo` int NULL DEFAULT NULL,
  `saldo_inicial` int UNSIGNED NULL DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'P',
  `fecha_pago` timestamp(0) NULL DEFAULT NULL,
  `t_impo_compras` int UNSIGNED NULL DEFAULT NULL,
  `t_impo_ventas` int UNSIGNED NULL DEFAULT NULL,
  `t_impo_retencion` int UNSIGNED NULL DEFAULT NULL,
  `pago` int UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `codcliente`, `ruc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of estado_anio
-- ----------------------------
INSERT INTO `estado_anio` VALUES (22, '123456', '7', 2, 2021, '2021-01-13 11:53:24', '2021-01-13 11:53:24', 0, 0, 0, 0, 150000, 'P', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `estado_anio` VALUES (23, '648667', '3', 4, 2021, '2021-01-13 12:16:36', '2021-01-13 12:21:27', 0, 0, 0, 0, 25253, 'P', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `estado_anio` VALUES (24, '4747132', '7', 1, 2021, '2021-01-13 15:52:09', '2021-01-13 15:52:09', 0, 0, 0, 0, 100000, 'P', NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for estado_mes
-- ----------------------------
DROP TABLE IF EXISTS `estado_mes`;
CREATE TABLE `estado_mes`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dv` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codcliente` int UNSIGNED NOT NULL,
  `mes` int UNSIGNED NULL DEFAULT NULL,
  `anio` int UNSIGNED NULL DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'A',
  `t_i_compras` int UNSIGNED NULL DEFAULT NULL,
  `t_i_ventas` int UNSIGNED NULL DEFAULT NULL,
  `t_retencion` int UNSIGNED NULL DEFAULT NULL,
  `t_impo_compras` int UNSIGNED NULL DEFAULT NULL,
  `t_impo_ventas` int UNSIGNED NULL DEFAULT NULL,
  `t_impo_retencion` int UNSIGNED NULL DEFAULT NULL,
  `saldo` int NULL DEFAULT NULL,
  `saldo_inicial` int UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `pago` int UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `codcliente`, `ruc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of estado_mes
-- ----------------------------

-- ----------------------------
-- Table structure for monedas
-- ----------------------------
DROP TABLE IF EXISTS `monedas`;
CREATE TABLE `monedas`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `moneda` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `prefijo` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tcambio` double(10, 0) NULL DEFAULT NULL,
  `fechacambio` datetime(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `moneda`, `prefijo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of monedas
-- ----------------------------
INSERT INTO `monedas` VALUES (1, 'GUARANÍ', 'PYG', NULL, 1, NULL, '2020-12-22 10:10:14', '2020-12-22 12:10:14');
INSERT INTO `monedas` VALUES (2, 'DOLAR AMERICANO', 'USD', NULL, 7000, NULL, '2020-12-17 08:52:43', '2020-12-17 08:52:43');
INSERT INTO `monedas` VALUES (9, 'REAL', 'BRL', NULL, NULL, NULL, '2020-12-06 21:52:07', NULL);
INSERT INTO `monedas` VALUES (10, 'PESO ARGENTINO', 'ARS', NULL, NULL, NULL, '2020-12-06 21:52:07', NULL);
INSERT INTO `monedas` VALUES (16, 'EURO *', 'EUR', NULL, NULL, NULL, '2020-12-06 21:52:07', NULL);

-- ----------------------------
-- Table structure for pagos
-- ----------------------------
DROP TABLE IF EXISTS `pagos`;
CREATE TABLE `pagos`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dv` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codcliente` int NOT NULL,
  `comprobante` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha` timestamp(0) NULL DEFAULT NULL,
  `validez` timestamp(0) NULL DEFAULT NULL,
  `plan` int UNSIGNED NULL DEFAULT NULL,
  `concepto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `precio` int UNSIGNED NULL DEFAULT NULL,
  `cliente` int UNSIGNED NULL DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `codcliente`, `ruc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pagos
-- ----------------------------
INSERT INTO `pagos` VALUES (1, '', NULL, 0, NULL, '2020-12-21 11:10:33', '2021-01-20 11:10:33', 1, 'PRUEBA GRATUITA', 0, 1, 'A', '2020-12-21 11:10:33', '2020-12-21 11:10:33');
INSERT INTO `pagos` VALUES (2, '', NULL, 0, '211122', '2021-01-15 00:00:00', '2021-01-21 12:30:57', 1, 'pago por adelantado enero 2021', 60000, 1, 'A', '2020-12-22 12:30:57', '2020-12-22 12:30:57');
INSERT INTO `pagos` VALUES (3, '123456', '7', 0, NULL, '2020-12-22 20:11:19', '2021-01-21 20:11:19', 1, 'PRUEBA GRATUITA', 0, 2, 'A', '2020-12-22 20:11:19', '2020-12-22 20:11:19');
INSERT INTO `pagos` VALUES (4, '1055007', '0', 0, NULL, '2020-12-23 11:49:49', '2021-01-22 11:49:49', 2, 'PRUEBA GRATUITA', 0, 3, 'A', '2020-12-23 11:49:49', '2020-12-23 11:49:49');
INSERT INTO `pagos` VALUES (5, '648667', '3', 0, NULL, '2020-12-23 11:56:43', '2021-01-22 11:56:43', 1, 'PRUEBA GRATUITA', 0, 4, 'A', '2020-12-23 11:56:43', '2020-12-23 11:56:43');
INSERT INTO `pagos` VALUES (6, '3401604', '0', 0, NULL, '2020-12-23 12:01:05', '2021-01-22 12:01:05', 1, 'PRUEBA GRATUITA', 0, 5, 'A', '2020-12-23 12:01:05', '2020-12-23 12:01:05');
INSERT INTO `pagos` VALUES (7, '444444', '8', 0, NULL, '2020-12-24 12:10:39', '2021-01-23 12:10:39', 1, 'PRUEBA GRATUITA', 0, 6, 'A', '2020-12-24 12:10:39', '2020-12-24 12:10:39');
INSERT INTO `pagos` VALUES (8, '78787844', '8', 0, NULL, '2020-12-24 12:30:41', '2021-01-23 12:30:41', 1, 'PRUEBA GRATUITA', 0, 8, 'A', '2020-12-24 12:30:41', '2020-12-24 12:30:41');
INSERT INTO `pagos` VALUES (9, '47899004', '8', 0, NULL, '2020-12-24 12:34:28', '2021-01-23 12:34:28', 1, 'PRUEBA GRATUITA', 0, 9, 'A', '2020-12-24 12:34:28', '2020-12-24 12:34:28');
INSERT INTO `pagos` VALUES (10, '755212', '8', 0, NULL, '2020-12-24 12:37:17', '2021-01-23 12:37:17', 1, 'PRUEBA GRATUITA', 0, 10, 'A', '2020-12-24 12:37:17', '2020-12-24 12:37:17');
INSERT INTO `pagos` VALUES (11, '3401604', '1', 0, NULL, '2020-12-29 12:48:52', '2021-01-28 12:48:52', 1, 'PRUEBA GRATUITA', 0, 11, 'A', '2020-12-29 12:48:52', '2020-12-29 12:48:52');
INSERT INTO `pagos` VALUES (12, '', NULL, 0, '15453', '2020-12-29 00:00:00', '2021-01-28 21:34:25', 1, 'Pago Enero', 60, 2, 'A', '2020-12-29 21:34:25', '2020-12-29 21:34:25');
INSERT INTO `pagos` VALUES (13, '', NULL, 0, '15453', '2020-12-29 00:00:00', '2021-01-28 21:34:44', 1, 'Pago Enero', 60, 2, 'A', '2020-12-29 21:34:44', '2020-12-29 21:34:44');
INSERT INTO `pagos` VALUES (14, '', NULL, 0, '15453', '2020-12-29 00:00:00', '2021-01-28 21:34:58', 1, 'Pago Enero', 60, 2, 'A', '2020-12-29 21:34:58', '2020-12-29 21:34:58');
INSERT INTO `pagos` VALUES (15, '3401604', '2', 0, NULL, '2020-12-30 16:17:45', '2021-01-29 16:17:45', 1, 'PRUEBA GRATUITA', 0, 12, 'A', '2020-12-30 16:17:45', '2020-12-30 16:17:45');
INSERT INTO `pagos` VALUES (16, '', NULL, 0, '344345', '2021-01-31 00:00:00', '2021-01-31 11:05:03', 1, 'otro pago de ejemplo', 60, 1, 'A', '2021-01-01 11:05:03', '2021-01-01 11:05:03');
INSERT INTO `pagos` VALUES (17, '966555', '2', 0, NULL, '2021-01-08 15:58:54', '2021-02-07 15:58:54', 1, 'PRUEBA GRATUITA', 0, 13, 'A', '2021-01-08 15:58:54', '2021-01-08 15:58:54');

-- ----------------------------
-- Table structure for pagos_iva
-- ----------------------------
DROP TABLE IF EXISTS `pagos_iva`;
CREATE TABLE `pagos_iva`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `dv` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ruc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `codcliente` int UNSIGNED NULL DEFAULT NULL,
  `comprobante` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `importe` int UNSIGNED NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `mes` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anio` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pagos_iva
-- ----------------------------

-- ----------------------------
-- Table structure for parametros
-- ----------------------------
DROP TABLE IF EXISTS `parametros`;
CREATE TABLE `parametros`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `IVA1` int NULL DEFAULT NULL,
  `IVA2` int NULL DEFAULT NULL,
  `IVA3` int NULL DEFAULT NULL,
  `MORA` decimal(6, 2) NULL DEFAULT NULL,
  `REDONDEO` int NULL DEFAULT NULL,
  `DIASVTO` int NULL DEFAULT NULL,
  `FACTURA` double(10, 0) NULL DEFAULT NULL,
  `RECIBO` double(10, 0) NULL DEFAULT NULL,
  `FECMIN` date NULL DEFAULT NULL,
  `FECMAX` date NULL DEFAULT NULL,
  `EMAIL` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `DIASGRATIS` int UNSIGNED NULL DEFAULT NULL,
  `MSJ_PANT_CIERRE_A` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `MSJ_PANT_CIERRE_M` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `MSJ_PANT_REGISTRO` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of parametros
-- ----------------------------
INSERT INTO `parametros` VALUES (1, 10, 5, 0, 30.00, 0, 40, 0, 0, '0000-00-00', '0000-00-00', 'valente.py@hotmail.com', '2020-12-16 10:52:30', '2021-01-05 17:53:56', 30, '                                                Mensaje de pantalla de Cierre Año                                                                                                                                                                                ', '                                               PARA CERRAR EL MES DEBE ESTAR AL DIA CON EL PAGO DEL SERVICIO', '                                              EL PRIMER MES ES GRATUITO');

-- ----------------------------
-- Table structure for planes
-- ----------------------------
DROP TABLE IF EXISTS `planes`;
CREATE TABLE `planes`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `descr` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `precio` int UNSIGNED NULL DEFAULT NULL,
  `dias` int UNSIGNED NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of planes
-- ----------------------------
INSERT INTO `planes` VALUES (1, 'PLAN MENSUAL GS. 60.000', 60000, 30, '2020-12-04 18:05:39', '2021-01-02 13:19:58');
INSERT INTO `planes` VALUES (2, 'PLAN SEMESTRAL GS. 300.000', 300000, 180, '2020-12-04 18:06:18', '2021-01-02 13:19:19');
INSERT INTO `planes` VALUES (5, 'PLAN ANUAL GS. 600.000', 600000, 365, '2020-12-22 12:13:17', '2021-01-02 13:19:47');

-- ----------------------------
-- Table structure for retencion
-- ----------------------------
DROP TABLE IF EXISTS `retencion`;
CREATE TABLE `retencion`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` double(15, 0) NOT NULL,
  `dv` int NOT NULL DEFAULT 0,
  `codcliente` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `fecha` date NOT NULL,
  `retencion` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `moneda` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tcambio` double(10, 0) NULL DEFAULT NULL,
  `importe` double(15, 0) NULL DEFAULT NULL,
  `origen` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`, `ruc`, `dv`, `codcliente`, `fecha`, `retencion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of retencion
-- ----------------------------
INSERT INTO `retencion` VALUES (14, 648667, 3, '4', '2021-01-13', '12345', '1', 0, 125325, 'W', '2021-01-13 12:22:02', '2021-01-13 12:22:02');

-- ----------------------------
-- Table structure for rubro
-- ----------------------------
DROP TABLE IF EXISTS `rubro`;
CREATE TABLE `rubro`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `descr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rubro
-- ----------------------------
INSERT INTO `rubro` VALUES (1, 'General - Administración de Empresas y afines');
INSERT INTO `rubro` VALUES (2, 'General - Call Center/ Telecobranzas');
INSERT INTO `rubro` VALUES (3, 'General - Chofer/ Gestoría');
INSERT INTO `rubro` VALUES (4, 'General - Comercio Exterior');
INSERT INTO `rubro` VALUES (5, 'General - Compras');
INSERT INTO `rubro` VALUES (6, 'General - Contabilidad y Auditoría');
INSERT INTO `rubro` VALUES (7, 'General - Finanzas/Tesorería/ Caja');
INSERT INTO `rubro` VALUES (8, 'General - Informática/ Internet/ Web');
INSERT INTO `rubro` VALUES (9, 'General - Logística/ Depósito');
INSERT INTO `rubro` VALUES (10, 'General - Marketing (o Mercadeo)');
INSERT INTO `rubro` VALUES (11, 'General - Producción y afines *');
INSERT INTO `rubro` VALUES (12, 'General - Ventas/ Comercio/ Telemarketing');
INSERT INTO `rubro` VALUES (13, 'Profesional - Economía');
INSERT INTO `rubro` VALUES (14, 'Sectorial - Electricidad del Automóvil');
INSERT INTO `rubro` VALUES (15, 'Sectorial - Farmacia');
INSERT INTO `rubro` VALUES (16, 'Sectorial - Gastronomia *');
INSERT INTO `rubro` VALUES (17, 'Sectorial - Hotelería / Viajes / Turismo');
INSERT INTO `rubro` VALUES (18, 'Sectorial - Ingeniería Industrial');
INSERT INTO `rubro` VALUES (19, 'Sectorial - Mecánica Industrial');

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dv` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `pass` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tipoplan` int NULL DEFAULT NULL,
  `email` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cliente` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `cedula` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `celular` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `domicilio` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ciudad` int NULL DEFAULT NULL,
  `rubro` int NULL DEFAULT NULL,
  `saldo_IVA` int UNSIGNED NULL DEFAULT 0,
  `estado` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A',
  `pass_anterior` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `origen` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'W',
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `tipo` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `session_expire` timestamp(0) NULL DEFAULT NULL,
  `remember` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N',
  `demo` int NULL DEFAULT NULL,
  `fechainicio` date NULL DEFAULT NULL,
  `fondo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ultimo_nro` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_recu` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token_validez` timestamp(0) NULL DEFAULT NULL,
  `remember_pass` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `remember_expire` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`regnro`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES (1, '4747132', '7', '$2y$10$/T.7r/E0RFu/yBrxCEaxuORh5oSa5ODQi7XGF5YKnm6E.zMqEv7bS', 1, 'soniatoledo294@hotmail.com', 'Sonia Jazmin Toledo', '4747132', '', '0983129494', '', 1, 8, 100000, 'A', NULL, 'W', '2021-01-13 12:11:06', '2021-01-13 14:11:06', '', '$2y$10$kA0yA4afBYNfxLB8k2DlH.i8M.uSsFI3M6MWLWtU9w/JPCWFtGTz6', '2021-01-23 14:11:00', 'N', NULL, NULL, NULL, '3', '', '0000-00-00 00:00:00', '$2y$10$288f/JGGV.vS6ATtTT/Ba.jV.6vSW3NDN2r6ofXO.i2SUo0fGYaI6', '2021-01-15 18:54:00');
INSERT INTO `usuarios` VALUES (2, '123456', '7', '$2y$10$mJUrJuw.z9zBYQkafPUy0u1qTwQ0378TmiYZ79gvJXrDZuti9ulTe', 1, 'valente.py@hotmail.com', 'Ejemplo para probar', '123456', '0981132188', '0981132188', 'Limpio', 9, 8, 150000, 'A', NULL, 'W', '2021-01-13 12:33:30', '2021-01-13 14:33:30', 'C', '$2y$10$rTydrqJX4BK6akqRVH6Hn.GXFvMjtswCM4b1.LAJgPNT9EXmk6R6q', '2021-01-08 18:00:00', 'N', NULL, NULL, 'https://www.ivafacil.com.py/ivafacil/wallpapers/fondo4.jpg', '2280', '$2y$10$8CWGwbADk9UEmfzyltTQSevvCkHT8YmLXI7jpuihGsA0NC5W2Gj7O', '2021-01-06 18:47:00', '$2y$10$BsEm.WU3cx.ukJsVM6GwRO.uj54FEUGTOXeAZAKdvOM3dP9DfTfAu', '2021-01-19 10:42:00');
INSERT INTO `usuarios` VALUES (3, '1055007', '0', '$2y$10$gx1m6pz9BD.GlajMBJAqsuVyDKRtdj5wtqufZfKbZByGKzlSqGkKS', 2, 'lsamudio@starsoft.com.py', 'Luis Samudio', '1055007', '021492888', '0982309000', '', 1, 3, 100000, 'A', NULL, 'W', '2020-12-23 11:49:49', '2020-12-23 11:49:49', '', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (4, '648667', '3', '$2y$10$sklRgQ0VoYlmUJB4YyES/uF54v0mX6aRxvhdq.5zKapWV.kwtycNC', 1, 'gpallarolas91@gmail.com', 'Rafael Pallarolas ', '648667', '0986786181', '0986786181', 'Fosatti 839 c/ Itapúa', 3, 1, 25253, 'A', NULL, 'W', '2021-01-13 19:37:09', '2021-01-13 21:37:09', 'C', '$2y$10$TmOqnP9e2LdVerY/1BVpUe2TxCo9aynWBx8A5k2hM5a5pYfFwcmFO', '2021-01-23 21:37:00', 'N', NULL, NULL, 'none', '001-001-0000132', NULL, NULL, '$2y$10$YoEmgcqm8JW.UPQ0Ij2wUuk9U/Pjb5je2AUFa6Rk2OEtvKFdBe52e', '2021-01-22 16:21:00');
INSERT INTO `usuarios` VALUES (5, '3401604', '0', '$2y$10$Rl8A4ZbC.bTXNSPsgR.eM.1r5tOaK2j9qicFYpcvNnHz3e0Eyo6v6', 1, 'diegoefx@gmail.com', 'Diego Fleitas', '3401604', '', '0971777111', 'Manuel Dominguez', 8, 8, 10000, 'A', NULL, 'W', '2021-01-09 19:34:42', '2021-01-09 21:34:42', '', '$2y$10$vbRwPNWESal1r6E3WyXPZeyFpMamaz1xbnCCt6zyTDqih0FjzY9Hu', '2021-01-19 21:34:00', 'N', NULL, NULL, NULL, '0010010000004', NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (6, '444444', '8', '$2y$10$jmLtroS.tC1LkroNYMF0Meq7ZRLJ/0CBdGGzs91AAsmlvbART9AnO', 1, 'rodrigog@gmail.com', 'Rodrigo Guillen', '444444', '', '', '', 1, 1, 0, 'A', NULL, 'W', '2020-12-24 12:10:39', '2020-12-24 12:10:39', '', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (7, '412544', '8', '$2y$10$2NkiYnrDB8eJwuGq0mWeyOW3TdDARb/J3e.N7QVPhALv4qM5kuNZG', 1, 'rodrigog@gmail.com', 'Rodrigo Guillen', '442244', '', '', '', 1, 1, 0, 'A', NULL, 'W', '2020-12-24 12:27:40', '2020-12-24 12:27:40', '', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (8, '78787844', '8', '$2y$10$v2hhcL.cgHqZpqbsgSS9YueudnxP8uijab4LlP8S5RtTu0asKoSf2', 1, 'rodrigog@gmail.com', 'Rodrigo Guillen', '452224', '', '', '', 1, 1, 0, 'A', NULL, 'W', '2020-12-24 12:30:41', '2020-12-24 12:30:41', '', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (9, '47899004', '8', '$2y$10$owLSuFfMzTf34sy49karWOEXHDt6oJPx/9W23tiZzbwwlnoK.FMCe', 1, 'gelenag@gmail.com', 'Elena Guillen', '4789004', '', '', '', 1, 1, 0, 'A', NULL, 'W', '2020-12-24 12:34:28', '2020-12-24 12:34:28', '', NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (11, '3401604', '1', '$2y$10$SQLgXCPLZQDz39z4I0zoX.2yUQORHdOGpiZ2WgXVpEGPfW0qXFMr.', 1, 'diegoefx@gmail.com', 'Diego Fleitas', '3401604', NULL, '', '', 1, 1, 0, 'A', NULL, 'A', '2020-12-29 12:55:48', '2020-12-29 14:55:48', '', '$2y$10$aAGh.cQ3q78PSW10xCaP3u9Oeaydvud0W.02umOv1vAFPRYMWzoBu', '2021-01-08 14:55:00', 'S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (12, '3401604', '2', '$2y$10$1duyhCZtE3.4j5DU1Md1IeBblVTFpNFA/M6GMdWGkpV8BZCq2SZ8y', 1, 'diegoefx@gmail.com', 'Diego Fleitas 2', '3401604', NULL, '', '', 1, 1, 0, 'A', NULL, 'A', '2020-12-30 14:18:05', '2020-12-30 16:18:05', '', '$2y$10$UIZcVidjrMXvtkpbxVOUy.J.nhwFPkOnpN2ZcJbBhTeIb3MXDJ0iW', '2021-01-09 16:18:00', 'S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `usuarios` VALUES (13, '966555', '2', '$2y$10$LVslCQTiA95rod31tbdogu.cgS7PeKwXY3JFcsgZ3IlDN4i/6qKe.', 1, 'machelod92@gmail.com', 'Marcelo', '966555', '', '', '', 1, 1, 0, 'A', NULL, 'W', '2021-01-09 07:48:42', '2021-01-09 09:48:42', '', NULL, NULL, 'N', NULL, NULL, NULL, '001-001-0000005', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for ventas
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas`  (
  `regnro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` double(15, 0) UNSIGNED NOT NULL,
  `dv` int UNSIGNED NOT NULL DEFAULT 0,
  `codcliente` int UNSIGNED NOT NULL DEFAULT 0,
  `fecha` date NULL DEFAULT NULL,
  `factura` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `moneda` int UNSIGNED NULL DEFAULT NULL,
  `tcambio` double(10, 0) UNSIGNED NULL DEFAULT NULL,
  `importe1` double(20, 0) UNSIGNED NULL DEFAULT NULL,
  `importe2` double(20, 0) UNSIGNED NULL DEFAULT NULL,
  `importe3` double(20, 0) UNSIGNED NULL DEFAULT NULL,
  `total` double(20, 0) UNSIGNED NULL DEFAULT NULL,
  `iva1` double(15, 0) UNSIGNED NULL DEFAULT NULL,
  `iva2` double(15, 0) UNSIGNED NULL DEFAULT NULL,
  `iva3` double(15, 0) UNSIGNED NULL DEFAULT NULL,
  `origen` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A',
  PRIMARY KEY (`regnro`, `ruc`, `codcliente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES (53, 648667, 3, 4, '2021-01-13', '001-001-0000129', 1, 0, 1100000, 0, 0, 1100000, 100000, 0, 0, 'W', '2021-01-13 12:17:23', '2021-01-13 12:17:23', 'A');
INSERT INTO `ventas` VALUES (54, 648667, 3, 4, '2021-01-13', '001-001-0000130', 1, 0, 0, 105000, 0, 105000, 0, 5000, 0, 'W', '2021-01-13 12:17:35', '2021-01-13 12:17:35', 'A');
INSERT INTO `ventas` VALUES (55, 648667, 3, 4, '2021-01-13', '001-001-0000131', 1, 0, 0, 0, 0, 0, 0, 0, 0, 'W', '2021-01-13 12:19:34', '2021-01-13 12:19:34', 'B');
INSERT INTO `ventas` VALUES (56, 648667, 3, 4, '2021-01-13', '001-001-0000132', 1, 0, 12525225, 0, 0, 12525225, 1138657, 0, 0, 'W', '2021-01-13 12:19:48', '2021-01-13 12:19:48', 'A');

SET FOREIGN_KEY_CHECKS = 1;
