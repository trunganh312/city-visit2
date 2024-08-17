/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : localhost:3306
 Source Schema         : out_tqa

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 09/06/2023 10:14:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `adm_id` int(11) NOT NULL AUTO_INCREMENT,
  `adm_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adm_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adm_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_boss` tinyint(1) NOT NULL DEFAULT 0,
  `adm_cto` tinyint(1) NULL DEFAULT 0,
  `adm_disable` tinyint(1) NOT NULL DEFAULT 0,
  `adm_time_create` int(11) NOT NULL DEFAULT 0,
  `adm_last_login` int(11) NULL DEFAULT 0,
  `adm_last_update` int(11) NOT NULL DEFAULT 0,
  `adm_last_online` int(11) NULL DEFAULT 0,
  `adm_active` tinyint(1) NOT NULL DEFAULT 0,
  `adm_group` tinyint(2) NOT NULL DEFAULT 0,
  `adm_random` int(11) NULL DEFAULT 0,
  `adm_ip_login` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_sex` tinyint(1) NULL DEFAULT 0,
  `adm_city` int(11) NULL DEFAULT 0,
  `adm_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_user_id` int(11) NULL DEFAULT 0,
  `adm_email_cskh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_link_fb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adm_hotline` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`adm_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin@website.com', '8e0286f5bb55c785efe2f8c921f6cf0b', 'Admin', '0912345678', 1, 1, 0, 1354973935, 1686278315, 0, 1686280427, 1, 1, 1248, '127.0.0.1', 1, 13, '77 Hàng Đào', 0, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for admin_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group`  (
  `adgr_id` int(11) NOT NULL AUTO_INCREMENT,
  `adgr_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adgr_note` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `adgr_active` tinyint(1) NULL DEFAULT 0,
  `adgr_parent` int(11) NULL DEFAULT 0,
  `adgr_check_level` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`adgr_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_group
-- ----------------------------

-- ----------------------------
-- Table structure for admin_group_admin
-- ----------------------------
DROP TABLE IF EXISTS `admin_group_admin`;
CREATE TABLE `admin_group_admin`  (
  `aga_admin_id` int(11) NULL DEFAULT 0,
  `aga_group_id` int(11) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_group_admin
-- ----------------------------

-- ----------------------------
-- Table structure for city
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city`  (
  `cit_id` int(11) NOT NULL AUTO_INCREMENT,
  `cit_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_name_other` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_active` tinyint(1) NULL DEFAULT NULL,
  `cit_order` tinyint(3) NULL DEFAULT 0,
  `cit_priority` tinyint(1) NULL DEFAULT 0,
  `cit_country` int(11) NULL DEFAULT 0,
  `cit_area` int(11) NULL DEFAULT 0,
  `cit_hot` tinyint(1) NOT NULL DEFAULT 0,
  `cit_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_address_map` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_lat_center` double NULL DEFAULT NULL,
  `cit_lng_center` double NULL DEFAULT NULL,
  `cit_page_cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_show_menu` tinyint(1) NOT NULL DEFAULT 0,
  `cit_search_data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cit_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`cit_id`) USING BTREE,
  INDEX `active`(`cit_active`) USING BTREE,
  INDEX `cit_order`(`cit_order`) USING BTREE,
  INDEX `cit_priority`(`cit_priority`) USING BTREE,
  INDEX `cit_area`(`cit_area`) USING BTREE,
  INDEX `cit_hot`(`cit_hot`) USING BTREE,
  INDEX `cit_show_menu`(`cit_show_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 64 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of city
-- ----------------------------
INSERT INTO `city` VALUES (1, 'Hà Nội', 'HN', 1, 1, 0, 1, 1, 0, 'ha-noi.jpg', '', 21.0031177, 105.8201408, 'ha-noi-banner-list-tour.jpg', 1, 'hà nội hn ha noi hn', '<div>Hà Nội nằm ở tả ngạn sông Đà và hai bên đồng bằng sông Hồng. Phía Bắc giáp với tỉnh Vĩnh Phúc và Thái Nguyên, phía Nam giáp tỉnh Hòa Bình, phía Đông giáp với tỉnh Bắc Ninh và Hưng Yên, còn phía Tây giáp tỉnh Vĩnh Phúc. Sau khi được mở rộng, Hà Nội nằm trong top 17 Thủ đô có diện tích lớn nhất thế giới với 3.324,92 km2. Với vị trí địa lý thuận lợi này, thành phố này dễ dàng trở thành trung tâm kinh tế &ndash; chính trị, văn hóa, khoa học quan trọng của cả nước. Hiện tại, bao gồm 12 quận, 1 thị xã và 17 huyện.</div>');
INSERT INTO `city` VALUES (2, 'Hà Giang', '', 1, 10, 0, 1, 1, 0, 'ha-giang.jpg', 'Hà Giang, Việt Nam', 22.823603802965, 104.98703246885, 'ha-giang-banner-list-tour.jpg', 1, 'hà giang ha giang', '');
INSERT INTO `city` VALUES (3, 'Hoà Bình', 'HB', 1, 9, 0, 1, 1, 0, 'hoa-binh.jpg', 'Hòa Bình, Việt Nam', 20.827510227695, 105.33895353723, 'hoa-binh-banner-list-tour.jpg', 1, 'hoà bình hb hoa binh hb', '');
INSERT INTO `city` VALUES (4, 'Cao Bằng', 'CB', 1, 25, 0, 1, 1, 0, 'cao-bang.jpg', 'Cao Bằng, Việt Nam', 22.66740690513, 106.25789122112, 'cao-bang-banner-list-tour.jpg', 0, 'cao bằng cb cao bang cb', '');
INSERT INTO `city` VALUES (5, 'Cà Mau', '', 1, 69, 0, 1, 4, 0, 'ca-mau.jpg', 'Cà Mau, Việt Nam', 9.1790008120342, 105.15088454892, 'ca-mau-banner-list-tour.jpg', 0, 'cà mau ca mau', '');
INSERT INTO `city` VALUES (6, 'Bắc Kạn', 'Bắc Cạn', 1, 14, 0, 1, 1, 0, 'bac-kan.jpg', 'Bắc Kạn, Việt Nam', 22.148611559198, 105.83552965292, 'bac-kan-banner-list-tour.jpg', 0, 'bắc kạn bắc cạn bac kan bac can', '');
INSERT INTO `city` VALUES (7, 'Bạc Liêu', '', 1, 72, 0, 1, 4, 0, 'bac-lieu.jpg', 'Bạc Liêu, Việt Nam', 9.2821015918641, 105.72247086703, 'bac-lieu-banner-list-tour.jpg', 0, 'bạc liêu bac lieu', '');
INSERT INTO `city` VALUES (8, 'Tuyên Quang', '', 1, 18, 0, 1, 1, 0, 'tuyen-quang.jpg', 'Tuyên Quang, Việt Nam', 21.817986192652, 105.20763959899, 'tuyen-quang-banner-list-tour.jpg', 0, 'tuyên quang tuyen quang', '');
INSERT INTO `city` VALUES (9, 'Cần Thơ', '', 1, 61, 0, 1, 4, 0, 'can-tho.jpg', 'Cần Thơ, Việt Nam', 10.035571522937, 105.76648874336, 'can-tho-banner-list-tour.jpg', 1, 'cần thơ can tho', '');
INSERT INTO `city` VALUES (10, 'Lào Cai', '', 1, 5, 0, 1, 1, 1, 'lao-cai.jpg', 'Lào Cai, Việt Nam', 22.4809431, 103.9754959, 'lao-cai-banner-list-tour.jpg', 1, 'lào cai lao cai', '');
INSERT INTO `city` VALUES (11, 'Điện Biên', '', 1, 27, 0, 1, 1, 0, 'dien-bien.jpg', 'Điện Biên, Việt Nam', 21.392301380006, 103.01596386703, 'dien-bien-banner-list-tour.jpg', 0, 'điện biên dien bien', '');
INSERT INTO `city` VALUES (12, 'Lai Châu', '', 1, 26, 0, 1, 1, 0, 'lai-chau.jpg', 'Lai Châu, Việt Nam', 22.388603548178, 103.47240886721, NULL, 0, 'lai châu lai chau', '');
INSERT INTO `city` VALUES (13, 'An Giang', '', 1, 68, 0, 1, 4, 0, 'an-giang.jpg', 'An Giang, Việt Nam', 10.703094958732, 105.12201702576, 'an-giang-banner-list-tour.jpg', 0, 'an giang an giang', '');
INSERT INTO `city` VALUES (14, 'Sơn La', '', 1, 11, 0, 1, 1, 0, 'son-la.jpg', 'Sơn La, Việt Nam', 21.3269024, 103.9143869, 'son-la-banner-list-tour.jpg', 1, 'sơn la son la', '');
INSERT INTO `city` VALUES (15, 'Yên Bái', '', 1, 20, 0, 1, 1, 0, 'yen-bai.jpg', 'Yên Bái, Việt Nam', 21.824637502804, 105.00930938813, 'yen-bai-banner-list-tour.jpg', 0, 'yên bái yen bai', '');
INSERT INTO `city` VALUES (16, 'Sóc Trăng', '', 1, 67, 0, 1, 4, 0, 'soc-trang.jpg', 'Sóc Trăng, Việt Nam', 9.5982472691138, 105.97145872538, 'soc-trang-banner-list-tour.jpg', 0, 'sóc trăng soc trang', '');
INSERT INTO `city` VALUES (17, 'Hậu Giang', '', 1, 70, 0, 1, 4, 0, 'hau-giang.jpg', 'Hậu Giang, Việt Nam', 9.757898, 105.6412527, 'hau-giang-banner-list-tour.jpg', 1, 'hậu giang hau giang', '');
INSERT INTO `city` VALUES (18, 'Kiên Giang', '', 1, 52, 0, 1, 3, 1, 'kien-giang.jpg', 'Phú Quốc, Phu Quoc, Kien Giang, Vietnam', 10.190887846416, 103.96822715332, 'kien-giang-banner-list-tour.jpg', 1, 'kiên giang kien giang', '');
INSERT INTO `city` VALUES (19, 'Thái Nguyên', '', 1, 12, 0, 1, 1, 0, 'thai-nguyen.jpg', 'Thái Nguyên, Việt Nam', 21.574827800834, 105.84387197474, 'thai-nguyen-banner-list-tour.jpg', 0, 'thái nguyên thai nguyen', '');
INSERT INTO `city` VALUES (20, 'Lạng Sơn', '', 1, 13, 0, 1, 1, 0, 'lang-son.jpg', 'Lạng Sơn, Việt Nam', 21.853249594977, 106.76397901445, 'lang-son-banner-list-tour.jpg', 0, 'lạng sơn lang son', '');
INSERT INTO `city` VALUES (21, 'Đồng Tháp', '', 1, 66, 0, 1, 4, 0, 'dong-thap.jpg', 'Đồng Tháp, Việt Nam', 10.455979825974, 105.63256051387, 'dong-thap-banner-list-tour.jpg', 0, 'đồng tháp dong thap', '');
INSERT INTO `city` VALUES (22, 'Quảng Ninh', '', 1, 4, 0, 1, 1, 1, 'quang-ninh.jpg', 'Quảng Ninh, Việt Nam', 21.00604403159, 107.26912553739, 'quang-ninh-banner-list-tour.jpg', 1, 'quảng ninh quang ninh', '');
INSERT INTO `city` VALUES (23, 'Vĩnh Long', '', 1, 71, 0, 1, 4, 0, 'vinh-long.jpg', 'Vĩnh Long, Việt Nam', 10.239574, 105.9571928, 'vinh-long-banner-list-tour.jpg', 1, 'vĩnh long vinh long', '');
INSERT INTO `city` VALUES (24, 'Bắc Giang', '', 1, 21, 0, 1, 1, 0, 'bac-giang.jpg', 'Bắc Giang, Việt Nam', 21.279472787342, 106.20535186567, 'bac-giang-banner-list-tour.jpg', 0, 'bắc giang bac giang', '');
INSERT INTO `city` VALUES (25, 'Phú Thọ', '', 1, 23, 0, 1, 1, 0, 'phu-tho.jpg', 'Phú Thọ, Việt Nam', 21.315581719955, 105.39600434605, 'phu-tho-banner-list-tour.jpg', 0, 'phú thọ phu tho', '');
INSERT INTO `city` VALUES (26, 'Vĩnh Phúc', '', 1, 24, 0, 1, 1, 0, 'vinh-phuc.jpg', 'Vĩnh Phúc, Việt Nam', 21.31211232966, 105.59636079243, 'vinh-phuc-banner-list-tour.jpg', 0, 'vĩnh phúc vinh phuc', '');
INSERT INTO `city` VALUES (27, 'Bắc Ninh', '', 1, 22, 0, 1, 1, 0, 'bac-ninh.jpg', 'Bắc Ninh, Việt Nam', 21.179292437945, 106.06441653699, 'bac-ninh-banner-list-tour.jpg', 0, 'bắc ninh bac ninh', '');
INSERT INTO `city` VALUES (28, 'Tiền Giang', '', 1, 65, 0, 1, 4, 0, 'tien-giang.jpg', 'Tiền Giang, Việt Nam', 10.365589158517, 106.35441001914, 'tien-giang-banner-list-tour.jpg', 1, 'tiền giang tien giang', '');
INSERT INTO `city` VALUES (29, 'Bến Tre', '', 1, 63, 0, 1, 4, 0, 'ben-tre.jpg', 'Bến Tre, Việt Nam', 10.239083509748, 106.37640930688, 'ben-tre-banner-list-tour.jpg', 1, 'bến tre ben tre', '');
INSERT INTO `city` VALUES (30, 'Hải Dương', '', 1, 17, 0, 1, 1, 0, 'hai-duong.jpg', 'Hải Dương, Việt Nam', 20.932771914164, 106.30923269731, NULL, 0, 'hải dương hai duong', '');
INSERT INTO `city` VALUES (31, 'Hải Phòng', '', 1, 6, 0, 1, 1, 1, 'hai-phong.jpg', 'Hải Phòng, Việt Nam', 20.8449115, 106.6880841, 'hai-phong-banner-list-tour.jpg', 1, 'hải phòng hai phong', '');
INSERT INTO `city` VALUES (32, 'Trà Vinh', '', 1, 62, 0, 1, 4, 0, 'tra-vinh.jpg', 'Trà Vinh, Việt Nam', 9.9356069012057, 106.34009926406, 'tra-vinh-banner-list-tour.jpg', 1, 'trà vinh tra vinh', '');
INSERT INTO `city` VALUES (33, 'Hưng Yên', '', 1, 16, 0, 1, 1, 0, 'hung-yen.jpg', 'Hưng Yên, Việt Nam', 20.652262595977, 106.05767733862, 'hung-yen-banner-list-tour.jpg', 0, 'hưng yên hung yen', '');
INSERT INTO `city` VALUES (34, 'Thái Bình', '', 1, 28, 0, 1, 1, 0, 'thai-binh.jpg', 'Thái Bình, Việt Nam', 20.443853956516, 106.33248438463, 'thai-binh-banner-list-tour.jpg', 0, 'thái bình thai binh', '');
INSERT INTO `city` VALUES (35, 'Hà Nam', '', 1, 15, 0, 1, 1, 0, 'ha-nam.jpg', 'Hà Nam, Việt Nam', 20.54277126677, 105.91487899994, 'ha-nam-banner-list-tour.jpg', 0, 'hà nam ha nam', '');
INSERT INTO `city` VALUES (36, 'Nam Định', '', 1, 19, 0, 1, 1, 0, 'nam-dinh.jpg', 'Nam Định, Việt Nam', 20.43460001045, 106.16498062806, 'nam-dinh-banner-list-tour.jpg', 0, 'nam định nam dinh', '');
INSERT INTO `city` VALUES (37, 'Ninh Bình', 'NB', 0, 8, 0, 1, 1, 0, 'ninh-binh.jpg', 'Ninh Bình, Việt Nam', 20.2506149, 105.9744536, 'ninh-binh-banner-list-tour.jpg', 1, 'ninh bình nb ninh binh nb', '');
INSERT INTO `city` VALUES (38, 'Thanh Hóa', 'TH', 1, 39, 0, 1, 2, 0, 'thanh-hoa.jpg', 'Thanh Hoá, Việt Nam', 19.806692, 105.7851816, 'thanh-hoa-banner-list-tour.jpg', 1, 'thanh hóa th thanh hoa th', '');
INSERT INTO `city` VALUES (39, 'Long An', '', 1, 64, 0, 1, 4, 0, 'long-an.jpg', 'Long An, Việt Nam', 10.536401884742, 106.42199165479, 'long-an-banner-list-tour.jpg', 0, 'long an long an', '');
INSERT INTO `city` VALUES (40, 'Nghệ An', '', 1, 41, 0, 1, 2, 0, 'nghe-an.jpg', 'Nghệ An, Việt Nam', 18.680311164267, 105.68624705603, 'nghe-an-banner-list-tour.jpg', 1, 'nghệ an nghe an', '');
INSERT INTO `city` VALUES (41, 'Hồ Chí Minh', '', 1, 51, 0, 1, 3, 0, 'ho-chi-minh.jpg', 'Hồ Chí Minh, Thành phố Hồ Chí Minh, Việt Nam', 10.8230989, 106.6296638, 'ho-chi-minh-banner-list-tour.jpg', 1, 'hồ chí minh ho chi minh', '');
INSERT INTO `city` VALUES (42, 'Hà Tĩnh', '', 1, 42, 0, 1, 2, 0, 'ha-tinh.jpg', 'Hà Tĩnh, Việt Nam', 18.339273246166, 105.89525958524, 'ha-tinh-banner-list-tour.jpg', 0, 'hà tĩnh ha tinh', '');
INSERT INTO `city` VALUES (43, 'Bà Rịa - Vũng Tàu', '', 1, 54, 0, 1, 3, 1, 'ba-ria-vung-tau.jpg', 'Vũng Tàu, Bà Rịa - Vũng Tàu, Vietnam', 10.342614051149, 107.08455392554, 'ba-ria-vung-tau-banner-list-tour.jpg', 1, 'bà rịa - vũng tàu ba ria  vung tau', '');
INSERT INTO `city` VALUES (44, 'Quảng Bình', 'QB', 1, 37, 0, 1, 2, 0, 'quang-binh.jpg', 'Quảng Bình, Việt Nam', 17.473108736983, 106.59799971934, 'quang-binh-banner-list-tour.jpg', 1, 'quảng bình qb quang binh qb', '');
INSERT INTO `city` VALUES (45, 'Quảng Trị', '', 1, 40, 0, 1, 2, 0, 'quang-tri.jpg', 'Quảng Trị, Việt Nam', 16.814097708391, 107.10564535972, 'quang-tri-banner-list-tour.jpg', 0, 'quảng trị quang tri', '');
INSERT INTO `city` VALUES (46, 'Thừa Thiên Huế', '', 1, 35, 0, 1, 2, 0, 'thua-thien-hue.jpg', 'Thừa Thiên Huế, Việt Nam', 16.467397, 107.5905326, 'thua-thien-hue-banner-list-tour.jpg', 1, 'thừa thiên huế thua thien hue', '');
INSERT INTO `city` VALUES (47, 'Đồng Nai', '', 1, 57, 0, 1, 3, 0, 'dong-nai.jpg', 'Đồng Nai, Việt Nam', 10.957383382674, 106.84268552877, 'dong-nai-banner-list-tour.jpg', 0, 'đồng nai dong nai', '');
INSERT INTO `city` VALUES (48, 'Đà Nẵng', 'ĐN', 1, 31, 0, 1, 2, 1, 'da-nang.jpg', '', 16.072931685987, 108.2296503668, 'da-nang-banner-list-tour.jpg', 1, 'đà nẵng đn da nang dn', '<p>Đà Nẵng là một thành phố ven biển tuyệt đẹp nằm ở miền trung của Việt Nam, luôn được coi là một trong những điểm đến du lịch hàng đầu của tất cả các du khách trong và ngoài nước. Với bãi biển Mỹ Khê trải dài và được mệnh danh là một trong những bãi biển đẹp nhất thế giới, Đà Nẵng luôn thu hút được du khách với cảnh quan thiên nhiên hoang sơ, nước biển trong xanh và bãi cát trắng mịn. Đà Nẵng còn nổi tiếng với rất nhiều địa điểm du lịch được du khách yêu thích như Cầu Rồng, Bán đảo Sơn Trà, Đèo Hải Vân, Bà Nà Hills, Chùa Linh Ứng, và cũng ở khá gần với Phố Cổ Hội An... Ngoài ra, đây cũng là trung tâm của nền văn hóa Chăm, với những di tích lịch sử như Tháp Chăm Mỹ Sơn và những hoạt động văn hóa truyền thống được tổ chức thường xuyên. Thành phố Đà Nẵng cũng nổi tiếng với các món ăn đặc trưng như: Mỳ Quảng, Bánh xèo, Nem lụi và nước mắm chấm đem hương vị đặc trưng của miền Trung đến với du khách.</p><p>Những năm gần đây, Đà Nẵng còn được mệnh danh là \"Thành phố đáng sống nhất Việt Nam\".</p>');
INSERT INTO `city` VALUES (49, 'Quảng Nam', '', 1, 32, 0, 1, 2, 1, 'quang-nam.jpg', 'Quảng Nam, Việt Nam', 15.88193736555, 108.33530225635, 'quang-nam-banner-list-tour.jpg', 1, 'quảng nam quang nam', '');
INSERT INTO `city` VALUES (50, 'Bình Dương', '', 1, 59, 0, 1, 3, 0, 'binh-duong.jpg', 'Bình Dương, Việt Nam', 10.986170135171, 106.67150934009, 'binh-duong-banner-list-tour.jpg', 0, 'bình dương binh duong', '');
INSERT INTO `city` VALUES (51, 'Quảng Ngãi', '', 1, 38, 0, 1, 2, 0, 'quang-ngai.jpg', 'Quảng Ngãi, Việt Nam', 15.1213873, 108.8044145, 'quang-ngai-banner-list-tour.jpg', 0, 'quảng ngãi quang ngai', '');
INSERT INTO `city` VALUES (52, 'Bình Định', '', 1, 33, 0, 1, 2, 1, 'binh-dinh.jpg', 'Bình Định, Việt Nam', 13.773985252525, 109.22128651562, 'binh-dinh-banner-list-tour.jpg', 1, 'bình định binh dinh', '');
INSERT INTO `city` VALUES (53, 'Tây Ninh', '', 1, 56, 0, 1, 3, 0, 'tay-ninh.jpg', 'Tây Ninh, Việt Nam', 11.300144155648, 106.11606520957, 'tay-ninh-banner-list-tour.jpg', 1, 'tây ninh tay ninh', '');
INSERT INTO `city` VALUES (54, 'Phú Yên', 'PY', 1, 34, 0, 1, 2, 1, 'phu-yen.jpg', 'Phú Yên, Việt Nam', 13.094038103115, 109.32307430649, 'phu-yen-banner-list-tour.jpg', 1, 'phú yên py phu yen py', '');
INSERT INTO `city` VALUES (55, 'Bình Phước', '', 1, 58, 0, 1, 3, 0, 'binh-phuoc.jpg', 'Bình Phước, Việt Nam', 11.537272876037, 106.88749788728, 'binh-phuoc-banner-list-tour.jpg', 0, 'bình phước binh phuoc', '');
INSERT INTO `city` VALUES (56, 'Khánh Hòa', '', 1, 53, 0, 1, 3, 1, 'khanh-hoa.jpg', 'Khánh Hòa, Việt Nam', 12.241734524098, 109.18925005605, 'khanh-hoa-banner-list-tour.jpg', 1, 'khánh hòa khanh hoa', '');
INSERT INTO `city` VALUES (57, 'Lâm Đồng', '', 1, 81, 0, 1, 5, 1, 'lam-dong.jpg', 'Lâm Đồng, Việt Nam', 11.940219773458, 108.45760803464, 'lam-dong-banner-list-tour.jpg', 1, 'lâm đồng lam dong', '');
INSERT INTO `city` VALUES (58, 'Ninh Thuận', '', 1, 36, 0, 1, 2, 0, 'ninh-thuan.jpg', 'Ninh Thuận, Việt Nam', 11.562707584721, 109.0215723123, 'ninh-thuan-banner-list-tour.jpg', 0, 'ninh thuận ninh thuan', '');
INSERT INTO `city` VALUES (59, 'Đắk Nông', 'Đắc Nông', 1, 84, 0, 1, 5, 0, 'dak-nong.jpg', 'Đăk Nông, Việt Nam', 12.2646476, 107.609806, 'dak-nong-banner-list-tour.jpg', 1, 'đắk nông đắc nông dak nong dac nong', '');
INSERT INTO `city` VALUES (60, 'Bình Thuận', '', 1, 55, 0, 1, 3, 0, 'binh-thuan.jpg', 'Bình Thuận, Việt Nam', 10.930368262995, 108.10589539126, 'binh-thuan-banner-list-tour.jpg', 1, 'bình thuận binh thuan', '');
INSERT INTO `city` VALUES (61, 'Đắk Lắk', 'Đắc Lắc', 1, 85, 0, 1, 5, 0, 'dak-lak.jpg', 'Đắk Lắk, Việt Nam', 12.7100116, 108.2377519, 'dak-lak-banner-list-tour.jpg', 1, 'đắk lắk đắc lắc dak lak dac lac', '');
INSERT INTO `city` VALUES (62, 'Kon Tum', '', 1, 82, 0, 1, 5, 0, 'kon-tum.jpg', 'Kon Tum, Việt Nam', 14.3497403, 108.0004606, 'kon-tum-banner-list-tour.jpg', 1, 'kon tum kon tum', '');
INSERT INTO `city` VALUES (63, 'Gia Lai', '', 1, 83, 0, 1, 5, 0, 'gia-lai.jpg', 'Gia Lai, Việt Nam', 13.975345138381, 108.00775146484, 'gia-lai-banner-list-tour.jpg', 1, 'gia lai gia lai', '');

-- ----------------------------
-- Table structure for configuration
-- ----------------------------
DROP TABLE IF EXISTS `configuration`;
CREATE TABLE `configuration`  (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_meta_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_meta_keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_hotline` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_zalo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_email_contact` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_social_fb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_social_twitter` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_social_instagram` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_social_tiktok` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_social_youtube` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `con_disable_email` tinyint(1) NULL DEFAULT 0,
  `con_my_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Địa chỉ IP VP',
  PRIMARY KEY (`con_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of configuration
-- ----------------------------
INSERT INTO `configuration` VALUES (1, 'Website', 'Website', 'Website', '0912345678', '0912345678', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `start_time` timestamp(0) NULL DEFAULT NULL,
  `end_time` timestamp(0) NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for module
-- ----------------------------
DROP TABLE IF EXISTS `module`;
CREATE TABLE `module`  (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mod_folder` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mod_order` tinyint(3) NOT NULL,
  `mod_active` tinyint(1) NOT NULL DEFAULT 0,
  `mod_icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mod_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`mod_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of module
-- ----------------------------
INSERT INTO `module` VALUES (1, 'Cấu hình hệ thống', 'system', 1, 1, 'fas fa-folder-open', '');
INSERT INTO `module` VALUES (2, 'Cấu hình website', 'config', 2, 1, 'fas fa-key', '');
INSERT INTO `module` VALUES (3, 'Tài khoản Admin', 'config', 3, 1, 'fas fa-user-cog', '');

-- ----------------------------
-- Table structure for module_file
-- ----------------------------
DROP TABLE IF EXISTS `module_file`;
CREATE TABLE `module_file`  (
  `modf_id` int(11) NOT NULL AUTO_INCREMENT,
  `modf_module_id` int(11) NOT NULL,
  `modf_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `modf_file` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `modf_order` int(11) NOT NULL DEFAULT 0,
  `modf_active` tinyint(1) NOT NULL DEFAULT 0,
  `modf_parent_id` int(11) NULL DEFAULT 0,
  `modf_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`modf_id`) USING BTREE,
  INDEX `modf_module_id`(`modf_module_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of module_file
-- ----------------------------
INSERT INTO `module_file` VALUES (1, 1, 'Danh sách module', 'list_module.php', 1, 1, 0, '');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `use_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_ward` int(11) NULL DEFAULT 0,
  `use_district` int(11) NULL DEFAULT 0,
  `use_city` int(11) NULL DEFAULT 0,
  `use_country` int(11) NULL DEFAULT 0,
  `use_time_create` int(11) NULL DEFAULT 0,
  `use_active` tinyint(1) NULL DEFAULT 1,
  `use_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_security` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_random` int(11) NULL DEFAULT 0,
  `use_login` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_disable` tinyint(1) NULL DEFAULT 0,
  `use_last_update_info` int(11) NULL DEFAULT 0,
  `use_last_time_change_pass` int(11) NULL DEFAULT 0 COMMENT 'Last time of changed password',
  `use_last_time_book` int(11) NULL DEFAULT 0 COMMENT 'Thời gian đặt booking gần nhất',
  `use_total_change_pass` tinyint(3) NULL DEFAULT 0 COMMENT 'Total number of change password',
  `use_source` tinyint(11) NULL DEFAULT 0,
  `use_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_sex` tinyint(1) NOT NULL DEFAULT 0,
  `use_type` tinyint(2) NOT NULL DEFAULT 0,
  `use_not_send_email` tinyint(1) NOT NULL DEFAULT 0,
  `use_total_logged` int(11) NOT NULL DEFAULT 0,
  `use_last_login` int(11) NOT NULL DEFAULT 0,
  `use_total_booking` int(11) NULL DEFAULT 0,
  `use_total_booking_success` int(11) NULL DEFAULT 0,
  `use_sent_email_mkt` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Danh dau la user nay da duoc gui email chua',
  `use_sent_email_verify` tinyint(1) NULL DEFAULT 0,
  `use_verify` tinyint(1) NULL DEFAULT 0,
  `use_contract` tinyint(1) NULL DEFAULT 0 COMMENT 'Ký HĐ hợp tác hay chưa',
  `use_vg_staff` tinyint(1) NULL DEFAULT 0,
  `use_level_id` int(11) NULL DEFAULT 0,
  `use_completed_money` float NULL DEFAULT 0,
  `use_referral_from` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`use_id`) USING BTREE,
  UNIQUE INDEX `use_email`(`use_email`) USING BTREE,
  INDEX `use_active`(`use_active`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
