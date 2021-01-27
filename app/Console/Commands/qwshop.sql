/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : crm

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-01-27 19:01:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for addresses
-- ----------------------------
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `receive_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人名',
  `receive_tel` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人手机',
  `area_info` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址信息',
  `address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址信息',
  `province_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '省份',
  `city_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '城市',
  `region_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '区县',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否默认',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of addresses
-- ----------------------------
INSERT INTO `addresses` VALUES ('6', '8', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', '1', '33', '681', '1', '2020-09-10 18:22:25', '2020-09-10 18:22:27');
INSERT INTO `addresses` VALUES ('7', '1', '李三', '18888888888', '山西省 太原市 长子县', '打扫打扫', '4', '34', '547', '1', '2021-01-14 12:54:36', '2021-01-14 13:11:04');

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '登陆IP',
  `login_time` timestamp NOT NULL DEFAULT '2020-07-05 14:16:46' COMMENT '登陆时间',
  `last_login_time` timestamp NOT NULL DEFAULT '2020-07-05 14:16:46' COMMENT '最后一次登陆',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'admin', '$2y$10$TnUza7yGyIObzC2PSXWbWOaMr3EItm4Z8B.bSIAWeIyObYgJ7BgZe', '管理员', '', '127.0.0.1', '2021-01-09 12:32:48', '2020-11-28 14:42:39', '2020-08-09 18:43:46', '2021-01-09 12:32:48');

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT 0,
  `role_id` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES ('1', '1', '1', null, null);

-- ----------------------------
-- Table structure for advs
-- ----------------------------
DROP TABLE IF EXISTS `advs`;
CREATE TABLE `advs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ap_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '广告位ID',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '广告名称',
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#' COMMENT '链接',
  `image_url` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `adv_start` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `adv_end` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `adv_sort` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `adv_type` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of advs
-- ----------------------------
INSERT INTO `advs` VALUES ('5', '4', '1', '', 'http://127.0.0.1:8000/storage/adv/2020-08-06/hc30PI0zqFDEFHgCQhJl9K8BpY1LowqsrGAgw2dS.jpg', '2020-08-06 16:05:19', '2020-09-26 16:05:19', '0', '0', '2020-08-06 16:06:24', '2020-08-06 16:06:24');
INSERT INTO `advs` VALUES ('6', '5', '1', '', 'http://127.0.0.1:8000/storage/adv/2020-08-06/Du1PuYRHlzY76e9yEh7X3e2TyvKIPlwuWBG93nCo.jpg', '2020-08-06 16:09:24', '2021-09-01 16:09:24', '0', '0', '2020-08-06 16:09:24', '2020-08-06 16:20:22');
INSERT INTO `advs` VALUES ('7', '5', '2', '', 'http://127.0.0.1:8000/storage/adv/2020-08-06/AaQxeGVF1WoJifocfABcp0snrFZ7mE5Bk1bswc8F.jpg', '2020-08-06 16:09:38', '2021-09-01 16:09:38', '0', '0', '2020-08-06 16:09:46', '2020-08-06 16:09:46');
INSERT INTO `advs` VALUES ('8', '5', '4', '', 'http://127.0.0.1:8000/storage/adv/2020-08-06/EJahW4QPvcTPA1u6YtX9yR0iOQGVUMviViL0vlFV.jpg', '2020-08-06 16:09:55', '2021-09-01 16:09:55', '0', '0', '2020-08-06 16:10:02', '2020-08-06 16:10:02');

-- ----------------------------
-- Table structure for adv_positions
-- ----------------------------
DROP TABLE IF EXISTS `adv_positions`;
CREATE TABLE `adv_positions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ap_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '广告位名称',
  `ap_width` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建议宽度',
  `ap_height` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '建议高度',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of adv_positions
-- ----------------------------
INSERT INTO `adv_positions` VALUES ('4', 'PC_首页幻灯片', '1920', '450', '2020-08-06 15:59:21', '2020-08-06 15:59:34');
INSERT INTO `adv_positions` VALUES ('5', 'PC_幻灯片下广告', '312', '160', '2020-08-06 16:00:16', '2020-08-06 16:00:16');

-- ----------------------------
-- Table structure for agreements
-- ----------------------------
DROP TABLE IF EXISTS `agreements`;
CREATE TABLE `agreements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `ename` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '英文标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of agreements
-- ----------------------------
INSERT INTO `agreements` VALUES ('1', '店铺入驻协议', 'store_join', '<p>213mal5</p>', '2020-07-15 17:02:39', '2020-07-18 22:51:25');

-- ----------------------------
-- Table structure for areas
-- ----------------------------
DROP TABLE IF EXISTS `areas`;
CREATE TABLE `areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '父ID',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地址',
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编码',
  `deep` tinyint(3) unsigned NOT NULL COMMENT '深度',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3496 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of areas
-- ----------------------------
INSERT INTO `areas` VALUES ('1', '0', '北京市', '110000000000', '0', null, '2020-07-22 22:34:55');
INSERT INTO `areas` VALUES ('2', '0', '天津市', '120000000000', '0', null, null);
INSERT INTO `areas` VALUES ('3', '0', '河北省', '130000000000', '0', null, null);
INSERT INTO `areas` VALUES ('4', '0', '山西省', '140000000000', '0', null, null);
INSERT INTO `areas` VALUES ('5', '0', '内蒙古自治区', '150000000000', '0', null, null);
INSERT INTO `areas` VALUES ('6', '0', '辽宁省', '210000000000', '0', null, null);
INSERT INTO `areas` VALUES ('7', '0', '吉林省', '220000000000', '0', null, null);
INSERT INTO `areas` VALUES ('8', '0', '黑龙江省', '230000000000', '0', null, null);
INSERT INTO `areas` VALUES ('9', '0', '上海市', '310000000000', '0', null, null);
INSERT INTO `areas` VALUES ('10', '0', '江苏省', '320000000000', '0', null, null);
INSERT INTO `areas` VALUES ('11', '0', '浙江省', '330000000000', '0', null, null);
INSERT INTO `areas` VALUES ('12', '0', '安徽省', '340000000000', '0', null, null);
INSERT INTO `areas` VALUES ('13', '0', '福建省', '350000000000', '0', null, null);
INSERT INTO `areas` VALUES ('14', '0', '江西省', '360000000000', '0', null, null);
INSERT INTO `areas` VALUES ('15', '0', '山东省', '370000000000', '0', null, null);
INSERT INTO `areas` VALUES ('16', '0', '河南省', '410000000000', '0', null, null);
INSERT INTO `areas` VALUES ('17', '0', '湖北省', '420000000000', '0', null, null);
INSERT INTO `areas` VALUES ('18', '0', '湖南省', '430000000000', '0', null, null);
INSERT INTO `areas` VALUES ('19', '0', '广东省', '440000000000', '0', null, null);
INSERT INTO `areas` VALUES ('20', '0', '广西壮族自治区', '450000000000', '0', null, null);
INSERT INTO `areas` VALUES ('21', '0', '海南省', '460000000000', '0', null, null);
INSERT INTO `areas` VALUES ('22', '0', '重庆市', '500000000000', '0', null, null);
INSERT INTO `areas` VALUES ('23', '0', '四川省', '510000000000', '0', null, null);
INSERT INTO `areas` VALUES ('24', '0', '贵州省', '520000000000', '0', null, null);
INSERT INTO `areas` VALUES ('25', '0', '云南省', '530000000000', '0', null, null);
INSERT INTO `areas` VALUES ('26', '0', '西藏自治区', '540000000000', '0', null, null);
INSERT INTO `areas` VALUES ('27', '0', '陕西省', '610000000000', '0', null, null);
INSERT INTO `areas` VALUES ('28', '0', '甘肃省', '620000000000', '0', null, null);
INSERT INTO `areas` VALUES ('29', '0', '青海省', '630000000000', '0', null, null);
INSERT INTO `areas` VALUES ('30', '0', '宁夏回族自治区', '640000000000', '0', null, null);
INSERT INTO `areas` VALUES ('31', '0', '新疆维吾尔自治区', '650000000000', '0', null, null);
INSERT INTO `areas` VALUES ('32', '310000000000', '市辖区', '310100000000', '1', null, null);
INSERT INTO `areas` VALUES ('33', '110000000000', '市辖区', '110100000000', '1', null, null);
INSERT INTO `areas` VALUES ('34', '140000000000', '太原市', '140100000000', '1', null, null);
INSERT INTO `areas` VALUES ('35', '140000000000', '大同市', '140200000000', '1', null, null);
INSERT INTO `areas` VALUES ('36', '140000000000', '阳泉市', '140300000000', '1', null, null);
INSERT INTO `areas` VALUES ('37', '140000000000', '长治市', '140400000000', '1', null, null);
INSERT INTO `areas` VALUES ('38', '140000000000', '晋城市', '140500000000', '1', null, null);
INSERT INTO `areas` VALUES ('39', '140000000000', '朔州市', '140600000000', '1', null, null);
INSERT INTO `areas` VALUES ('40', '140000000000', '晋中市', '140700000000', '1', null, null);
INSERT INTO `areas` VALUES ('41', '140000000000', '运城市', '140800000000', '1', null, null);
INSERT INTO `areas` VALUES ('42', '140000000000', '忻州市', '140900000000', '1', null, null);
INSERT INTO `areas` VALUES ('43', '140000000000', '临汾市', '141000000000', '1', null, null);
INSERT INTO `areas` VALUES ('44', '140000000000', '吕梁市', '141100000000', '1', null, null);
INSERT INTO `areas` VALUES ('45', '150000000000', '呼和浩特市', '150100000000', '1', null, null);
INSERT INTO `areas` VALUES ('46', '150000000000', '包头市', '150200000000', '1', null, null);
INSERT INTO `areas` VALUES ('47', '150000000000', '乌海市', '150300000000', '1', null, null);
INSERT INTO `areas` VALUES ('48', '150000000000', '赤峰市', '150400000000', '1', null, null);
INSERT INTO `areas` VALUES ('49', '150000000000', '通辽市', '150500000000', '1', null, null);
INSERT INTO `areas` VALUES ('50', '150000000000', '鄂尔多斯市', '150600000000', '1', null, null);
INSERT INTO `areas` VALUES ('51', '150000000000', '呼伦贝尔市', '150700000000', '1', null, null);
INSERT INTO `areas` VALUES ('52', '150000000000', '巴彦淖尔市', '150800000000', '1', null, null);
INSERT INTO `areas` VALUES ('53', '150000000000', '乌兰察布市', '150900000000', '1', null, null);
INSERT INTO `areas` VALUES ('54', '150000000000', '兴安盟', '152200000000', '1', null, null);
INSERT INTO `areas` VALUES ('55', '150000000000', '锡林郭勒盟', '152500000000', '1', null, null);
INSERT INTO `areas` VALUES ('56', '150000000000', '阿拉善盟', '152900000000', '1', null, null);
INSERT INTO `areas` VALUES ('57', '540000000000', '拉萨市', '540100000000', '1', null, null);
INSERT INTO `areas` VALUES ('58', '540000000000', '日喀则市', '540200000000', '1', null, null);
INSERT INTO `areas` VALUES ('59', '540000000000', '昌都市', '540300000000', '1', null, null);
INSERT INTO `areas` VALUES ('60', '540000000000', '林芝市', '540400000000', '1', null, null);
INSERT INTO `areas` VALUES ('61', '540000000000', '山南市', '540500000000', '1', null, null);
INSERT INTO `areas` VALUES ('62', '540000000000', '那曲市', '540600000000', '1', null, null);
INSERT INTO `areas` VALUES ('63', '540000000000', '阿里地区', '542500000000', '1', null, null);
INSERT INTO `areas` VALUES ('64', '620000000000', '兰州市', '620100000000', '1', null, null);
INSERT INTO `areas` VALUES ('65', '620000000000', '嘉峪关市', '620200000000', '1', null, null);
INSERT INTO `areas` VALUES ('66', '620000000000', '金昌市', '620300000000', '1', null, null);
INSERT INTO `areas` VALUES ('67', '620000000000', '白银市', '620400000000', '1', null, null);
INSERT INTO `areas` VALUES ('68', '620000000000', '天水市', '620500000000', '1', null, null);
INSERT INTO `areas` VALUES ('69', '620000000000', '武威市', '620600000000', '1', null, null);
INSERT INTO `areas` VALUES ('70', '620000000000', '张掖市', '620700000000', '1', null, null);
INSERT INTO `areas` VALUES ('71', '620000000000', '平凉市', '620800000000', '1', null, null);
INSERT INTO `areas` VALUES ('72', '620000000000', '酒泉市', '620900000000', '1', null, null);
INSERT INTO `areas` VALUES ('73', '620000000000', '庆阳市', '621000000000', '1', null, null);
INSERT INTO `areas` VALUES ('74', '620000000000', '定西市', '621100000000', '1', null, null);
INSERT INTO `areas` VALUES ('75', '620000000000', '陇南市', '621200000000', '1', null, null);
INSERT INTO `areas` VALUES ('76', '620000000000', '临夏回族自治州', '622900000000', '1', null, null);
INSERT INTO `areas` VALUES ('77', '620000000000', '甘南藏族自治州', '623000000000', '1', null, null);
INSERT INTO `areas` VALUES ('78', '130000000000', '石家庄市', '130100000000', '1', null, null);
INSERT INTO `areas` VALUES ('79', '130000000000', '唐山市', '130200000000', '1', null, null);
INSERT INTO `areas` VALUES ('80', '130000000000', '秦皇岛市', '130300000000', '1', null, null);
INSERT INTO `areas` VALUES ('81', '130000000000', '邯郸市', '130400000000', '1', null, null);
INSERT INTO `areas` VALUES ('82', '130000000000', '邢台市', '130500000000', '1', null, null);
INSERT INTO `areas` VALUES ('83', '130000000000', '保定市', '130600000000', '1', null, null);
INSERT INTO `areas` VALUES ('84', '130000000000', '张家口市', '130700000000', '1', null, null);
INSERT INTO `areas` VALUES ('85', '130000000000', '承德市', '130800000000', '1', null, null);
INSERT INTO `areas` VALUES ('86', '130000000000', '沧州市', '130900000000', '1', null, null);
INSERT INTO `areas` VALUES ('87', '130000000000', '廊坊市', '131000000000', '1', null, null);
INSERT INTO `areas` VALUES ('88', '130000000000', '衡水市', '131100000000', '1', null, null);
INSERT INTO `areas` VALUES ('89', '370000000000', '济南市', '370100000000', '1', null, null);
INSERT INTO `areas` VALUES ('90', '370000000000', '青岛市', '370200000000', '1', null, null);
INSERT INTO `areas` VALUES ('91', '370000000000', '淄博市', '370300000000', '1', null, null);
INSERT INTO `areas` VALUES ('92', '370000000000', '枣庄市', '370400000000', '1', null, null);
INSERT INTO `areas` VALUES ('93', '370000000000', '东营市', '370500000000', '1', null, null);
INSERT INTO `areas` VALUES ('94', '370000000000', '烟台市', '370600000000', '1', null, null);
INSERT INTO `areas` VALUES ('95', '370000000000', '潍坊市', '370700000000', '1', null, null);
INSERT INTO `areas` VALUES ('96', '370000000000', '济宁市', '370800000000', '1', null, null);
INSERT INTO `areas` VALUES ('97', '370000000000', '泰安市', '370900000000', '1', null, null);
INSERT INTO `areas` VALUES ('98', '370000000000', '威海市', '371000000000', '1', null, null);
INSERT INTO `areas` VALUES ('99', '370000000000', '日照市', '371100000000', '1', null, null);
INSERT INTO `areas` VALUES ('100', '370000000000', '临沂市', '371300000000', '1', null, null);
INSERT INTO `areas` VALUES ('101', '370000000000', '德州市', '371400000000', '1', null, null);
INSERT INTO `areas` VALUES ('102', '370000000000', '聊城市', '371500000000', '1', null, null);
INSERT INTO `areas` VALUES ('103', '370000000000', '滨州市', '371600000000', '1', null, null);
INSERT INTO `areas` VALUES ('104', '370000000000', '菏泽市', '371700000000', '1', null, null);
INSERT INTO `areas` VALUES ('105', '120000000000', '市辖区', '120100000000', '1', null, null);
INSERT INTO `areas` VALUES ('106', '210000000000', '沈阳市', '210100000000', '1', null, null);
INSERT INTO `areas` VALUES ('107', '210000000000', '大连市', '210200000000', '1', null, null);
INSERT INTO `areas` VALUES ('108', '210000000000', '鞍山市', '210300000000', '1', null, null);
INSERT INTO `areas` VALUES ('109', '210000000000', '抚顺市', '210400000000', '1', null, null);
INSERT INTO `areas` VALUES ('110', '210000000000', '本溪市', '210500000000', '1', null, null);
INSERT INTO `areas` VALUES ('111', '210000000000', '丹东市', '210600000000', '1', null, null);
INSERT INTO `areas` VALUES ('112', '210000000000', '锦州市', '210700000000', '1', null, null);
INSERT INTO `areas` VALUES ('113', '210000000000', '营口市', '210800000000', '1', null, null);
INSERT INTO `areas` VALUES ('114', '210000000000', '阜新市', '210900000000', '1', null, null);
INSERT INTO `areas` VALUES ('115', '210000000000', '辽阳市', '211000000000', '1', null, null);
INSERT INTO `areas` VALUES ('116', '210000000000', '盘锦市', '211100000000', '1', null, null);
INSERT INTO `areas` VALUES ('117', '210000000000', '铁岭市', '211200000000', '1', null, null);
INSERT INTO `areas` VALUES ('118', '210000000000', '朝阳市', '211300000000', '1', null, null);
INSERT INTO `areas` VALUES ('119', '210000000000', '葫芦岛市', '211400000000', '1', null, null);
INSERT INTO `areas` VALUES ('120', '220000000000', '长春市', '220100000000', '1', null, null);
INSERT INTO `areas` VALUES ('121', '220000000000', '吉林市', '220200000000', '1', null, null);
INSERT INTO `areas` VALUES ('122', '220000000000', '四平市', '220300000000', '1', null, null);
INSERT INTO `areas` VALUES ('123', '220000000000', '辽源市', '220400000000', '1', null, null);
INSERT INTO `areas` VALUES ('124', '220000000000', '通化市', '220500000000', '1', null, null);
INSERT INTO `areas` VALUES ('125', '220000000000', '白山市', '220600000000', '1', null, null);
INSERT INTO `areas` VALUES ('126', '220000000000', '松原市', '220700000000', '1', null, null);
INSERT INTO `areas` VALUES ('127', '220000000000', '白城市', '220800000000', '1', null, null);
INSERT INTO `areas` VALUES ('128', '220000000000', '延边朝鲜族自治州', '222400000000', '1', null, null);
INSERT INTO `areas` VALUES ('129', '230000000000', '哈尔滨市', '230100000000', '1', null, null);
INSERT INTO `areas` VALUES ('130', '230000000000', '齐齐哈尔市', '230200000000', '1', null, null);
INSERT INTO `areas` VALUES ('131', '230000000000', '鸡西市', '230300000000', '1', null, null);
INSERT INTO `areas` VALUES ('132', '230000000000', '鹤岗市', '230400000000', '1', null, null);
INSERT INTO `areas` VALUES ('133', '230000000000', '双鸭山市', '230500000000', '1', null, null);
INSERT INTO `areas` VALUES ('134', '230000000000', '大庆市', '230600000000', '1', null, null);
INSERT INTO `areas` VALUES ('135', '230000000000', '伊春市', '230700000000', '1', null, null);
INSERT INTO `areas` VALUES ('136', '230000000000', '佳木斯市', '230800000000', '1', null, null);
INSERT INTO `areas` VALUES ('137', '230000000000', '七台河市', '230900000000', '1', null, null);
INSERT INTO `areas` VALUES ('138', '230000000000', '牡丹江市', '231000000000', '1', null, null);
INSERT INTO `areas` VALUES ('139', '230000000000', '黑河市', '231100000000', '1', null, null);
INSERT INTO `areas` VALUES ('140', '230000000000', '绥化市', '231200000000', '1', null, null);
INSERT INTO `areas` VALUES ('141', '230000000000', '大兴安岭地区', '232700000000', '1', null, null);
INSERT INTO `areas` VALUES ('142', '330000000000', '杭州市', '330100000000', '1', null, null);
INSERT INTO `areas` VALUES ('143', '330000000000', '宁波市', '330200000000', '1', null, null);
INSERT INTO `areas` VALUES ('144', '330000000000', '温州市', '330300000000', '1', null, null);
INSERT INTO `areas` VALUES ('145', '330000000000', '嘉兴市', '330400000000', '1', null, null);
INSERT INTO `areas` VALUES ('146', '330000000000', '湖州市', '330500000000', '1', null, null);
INSERT INTO `areas` VALUES ('147', '330000000000', '绍兴市', '330600000000', '1', null, null);
INSERT INTO `areas` VALUES ('148', '330000000000', '金华市', '330700000000', '1', null, null);
INSERT INTO `areas` VALUES ('149', '330000000000', '衢州市', '330800000000', '1', null, null);
INSERT INTO `areas` VALUES ('150', '330000000000', '舟山市', '330900000000', '1', null, null);
INSERT INTO `areas` VALUES ('151', '330000000000', '台州市', '331000000000', '1', null, null);
INSERT INTO `areas` VALUES ('152', '330000000000', '丽水市', '331100000000', '1', null, null);
INSERT INTO `areas` VALUES ('153', '350000000000', '福州市', '350100000000', '1', null, null);
INSERT INTO `areas` VALUES ('154', '350000000000', '厦门市', '350200000000', '1', null, null);
INSERT INTO `areas` VALUES ('155', '350000000000', '莆田市', '350300000000', '1', null, null);
INSERT INTO `areas` VALUES ('156', '350000000000', '三明市', '350400000000', '1', null, null);
INSERT INTO `areas` VALUES ('157', '350000000000', '泉州市', '350500000000', '1', null, null);
INSERT INTO `areas` VALUES ('158', '350000000000', '漳州市', '350600000000', '1', null, null);
INSERT INTO `areas` VALUES ('159', '350000000000', '南平市', '350700000000', '1', null, null);
INSERT INTO `areas` VALUES ('160', '350000000000', '龙岩市', '350800000000', '1', null, null);
INSERT INTO `areas` VALUES ('161', '350000000000', '宁德市', '350900000000', '1', null, null);
INSERT INTO `areas` VALUES ('162', '410000000000', '郑州市', '410100000000', '1', null, null);
INSERT INTO `areas` VALUES ('163', '410000000000', '开封市', '410200000000', '1', null, null);
INSERT INTO `areas` VALUES ('164', '410000000000', '洛阳市', '410300000000', '1', null, null);
INSERT INTO `areas` VALUES ('165', '410000000000', '平顶山市', '410400000000', '1', null, null);
INSERT INTO `areas` VALUES ('166', '410000000000', '安阳市', '410500000000', '1', null, null);
INSERT INTO `areas` VALUES ('167', '410000000000', '鹤壁市', '410600000000', '1', null, null);
INSERT INTO `areas` VALUES ('168', '410000000000', '新乡市', '410700000000', '1', null, null);
INSERT INTO `areas` VALUES ('169', '410000000000', '焦作市', '410800000000', '1', null, null);
INSERT INTO `areas` VALUES ('170', '410000000000', '濮阳市', '410900000000', '1', null, null);
INSERT INTO `areas` VALUES ('171', '410000000000', '许昌市', '411000000000', '1', null, null);
INSERT INTO `areas` VALUES ('172', '410000000000', '漯河市', '411100000000', '1', null, null);
INSERT INTO `areas` VALUES ('173', '410000000000', '三门峡市', '411200000000', '1', null, null);
INSERT INTO `areas` VALUES ('174', '410000000000', '南阳市', '411300000000', '1', null, null);
INSERT INTO `areas` VALUES ('175', '410000000000', '商丘市', '411400000000', '1', null, null);
INSERT INTO `areas` VALUES ('176', '410000000000', '信阳市', '411500000000', '1', null, null);
INSERT INTO `areas` VALUES ('177', '410000000000', '周口市', '411600000000', '1', null, null);
INSERT INTO `areas` VALUES ('178', '410000000000', '驻马店市', '411700000000', '1', null, null);
INSERT INTO `areas` VALUES ('179', '410000000000', '省直辖县级行政区划', '419000000000', '1', null, null);
INSERT INTO `areas` VALUES ('180', '420000000000', '武汉市', '420100000000', '1', null, null);
INSERT INTO `areas` VALUES ('181', '420000000000', '黄石市', '420200000000', '1', null, null);
INSERT INTO `areas` VALUES ('182', '420000000000', '十堰市', '420300000000', '1', null, null);
INSERT INTO `areas` VALUES ('183', '420000000000', '宜昌市', '420500000000', '1', null, null);
INSERT INTO `areas` VALUES ('184', '420000000000', '襄阳市', '420600000000', '1', null, null);
INSERT INTO `areas` VALUES ('185', '420000000000', '鄂州市', '420700000000', '1', null, null);
INSERT INTO `areas` VALUES ('186', '420000000000', '荆门市', '420800000000', '1', null, null);
INSERT INTO `areas` VALUES ('187', '420000000000', '孝感市', '420900000000', '1', null, null);
INSERT INTO `areas` VALUES ('188', '420000000000', '荆州市', '421000000000', '1', null, null);
INSERT INTO `areas` VALUES ('189', '420000000000', '黄冈市', '421100000000', '1', null, null);
INSERT INTO `areas` VALUES ('190', '420000000000', '咸宁市', '421200000000', '1', null, null);
INSERT INTO `areas` VALUES ('191', '420000000000', '随州市', '421300000000', '1', null, null);
INSERT INTO `areas` VALUES ('192', '420000000000', '恩施土家族苗族自治州', '422800000000', '1', null, null);
INSERT INTO `areas` VALUES ('193', '420000000000', '省直辖县级行政区划', '429000000000', '1', null, null);
INSERT INTO `areas` VALUES ('194', '430000000000', '长沙市', '430100000000', '1', null, null);
INSERT INTO `areas` VALUES ('195', '430000000000', '株洲市', '430200000000', '1', null, null);
INSERT INTO `areas` VALUES ('196', '430000000000', '湘潭市', '430300000000', '1', null, null);
INSERT INTO `areas` VALUES ('197', '430000000000', '衡阳市', '430400000000', '1', null, null);
INSERT INTO `areas` VALUES ('198', '430000000000', '邵阳市', '430500000000', '1', null, null);
INSERT INTO `areas` VALUES ('199', '430000000000', '岳阳市', '430600000000', '1', null, null);
INSERT INTO `areas` VALUES ('200', '430000000000', '常德市', '430700000000', '1', null, null);
INSERT INTO `areas` VALUES ('201', '430000000000', '张家界市', '430800000000', '1', null, null);
INSERT INTO `areas` VALUES ('202', '430000000000', '益阳市', '430900000000', '1', null, null);
INSERT INTO `areas` VALUES ('203', '430000000000', '郴州市', '431000000000', '1', null, null);
INSERT INTO `areas` VALUES ('204', '430000000000', '永州市', '431100000000', '1', null, null);
INSERT INTO `areas` VALUES ('205', '430000000000', '怀化市', '431200000000', '1', null, null);
INSERT INTO `areas` VALUES ('206', '430000000000', '娄底市', '431300000000', '1', null, null);
INSERT INTO `areas` VALUES ('207', '430000000000', '湘西土家族苗族自治州', '433100000000', '1', null, null);
INSERT INTO `areas` VALUES ('208', '450000000000', '南宁市', '450100000000', '1', null, null);
INSERT INTO `areas` VALUES ('209', '450000000000', '柳州市', '450200000000', '1', null, null);
INSERT INTO `areas` VALUES ('210', '450000000000', '桂林市', '450300000000', '1', null, null);
INSERT INTO `areas` VALUES ('211', '450000000000', '梧州市', '450400000000', '1', null, null);
INSERT INTO `areas` VALUES ('212', '450000000000', '北海市', '450500000000', '1', null, null);
INSERT INTO `areas` VALUES ('213', '450000000000', '防城港市', '450600000000', '1', null, null);
INSERT INTO `areas` VALUES ('214', '450000000000', '钦州市', '450700000000', '1', null, null);
INSERT INTO `areas` VALUES ('215', '450000000000', '贵港市', '450800000000', '1', null, null);
INSERT INTO `areas` VALUES ('216', '450000000000', '玉林市', '450900000000', '1', null, null);
INSERT INTO `areas` VALUES ('217', '450000000000', '百色市', '451000000000', '1', null, null);
INSERT INTO `areas` VALUES ('218', '450000000000', '贺州市', '451100000000', '1', null, null);
INSERT INTO `areas` VALUES ('219', '450000000000', '河池市', '451200000000', '1', null, null);
INSERT INTO `areas` VALUES ('220', '450000000000', '来宾市', '451300000000', '1', null, null);
INSERT INTO `areas` VALUES ('221', '450000000000', '崇左市', '451400000000', '1', null, null);
INSERT INTO `areas` VALUES ('222', '510000000000', '成都市', '510100000000', '1', null, null);
INSERT INTO `areas` VALUES ('223', '510000000000', '自贡市', '510300000000', '1', null, null);
INSERT INTO `areas` VALUES ('224', '510000000000', '攀枝花市', '510400000000', '1', null, null);
INSERT INTO `areas` VALUES ('225', '510000000000', '泸州市', '510500000000', '1', null, null);
INSERT INTO `areas` VALUES ('226', '510000000000', '德阳市', '510600000000', '1', null, null);
INSERT INTO `areas` VALUES ('227', '510000000000', '绵阳市', '510700000000', '1', null, null);
INSERT INTO `areas` VALUES ('228', '510000000000', '广元市', '510800000000', '1', null, null);
INSERT INTO `areas` VALUES ('229', '510000000000', '遂宁市', '510900000000', '1', null, null);
INSERT INTO `areas` VALUES ('230', '510000000000', '内江市', '511000000000', '1', null, null);
INSERT INTO `areas` VALUES ('231', '510000000000', '乐山市', '511100000000', '1', null, null);
INSERT INTO `areas` VALUES ('232', '510000000000', '南充市', '511300000000', '1', null, null);
INSERT INTO `areas` VALUES ('233', '510000000000', '眉山市', '511400000000', '1', null, null);
INSERT INTO `areas` VALUES ('234', '510000000000', '宜宾市', '511500000000', '1', null, null);
INSERT INTO `areas` VALUES ('235', '510000000000', '广安市', '511600000000', '1', null, null);
INSERT INTO `areas` VALUES ('236', '510000000000', '达州市', '511700000000', '1', null, null);
INSERT INTO `areas` VALUES ('237', '510000000000', '雅安市', '511800000000', '1', null, null);
INSERT INTO `areas` VALUES ('238', '510000000000', '巴中市', '511900000000', '1', null, null);
INSERT INTO `areas` VALUES ('239', '510000000000', '资阳市', '512000000000', '1', null, null);
INSERT INTO `areas` VALUES ('240', '510000000000', '阿坝藏族羌族自治州', '513200000000', '1', null, null);
INSERT INTO `areas` VALUES ('241', '510000000000', '甘孜藏族自治州', '513300000000', '1', null, null);
INSERT INTO `areas` VALUES ('242', '510000000000', '凉山彝族自治州', '513400000000', '1', null, null);
INSERT INTO `areas` VALUES ('243', '520000000000', '贵阳市', '520100000000', '1', null, null);
INSERT INTO `areas` VALUES ('244', '520000000000', '六盘水市', '520200000000', '1', null, null);
INSERT INTO `areas` VALUES ('245', '520000000000', '遵义市', '520300000000', '1', null, null);
INSERT INTO `areas` VALUES ('246', '520000000000', '安顺市', '520400000000', '1', null, null);
INSERT INTO `areas` VALUES ('247', '520000000000', '毕节市', '520500000000', '1', null, null);
INSERT INTO `areas` VALUES ('248', '520000000000', '铜仁市', '520600000000', '1', null, null);
INSERT INTO `areas` VALUES ('249', '520000000000', '黔西南布依族苗族自治州', '522300000000', '1', null, null);
INSERT INTO `areas` VALUES ('250', '520000000000', '黔东南苗族侗族自治州', '522600000000', '1', null, null);
INSERT INTO `areas` VALUES ('251', '520000000000', '黔南布依族苗族自治州', '522700000000', '1', null, null);
INSERT INTO `areas` VALUES ('252', '530000000000', '昆明市', '530100000000', '1', null, null);
INSERT INTO `areas` VALUES ('253', '530000000000', '曲靖市', '530300000000', '1', null, null);
INSERT INTO `areas` VALUES ('254', '530000000000', '玉溪市', '530400000000', '1', null, null);
INSERT INTO `areas` VALUES ('255', '530000000000', '保山市', '530500000000', '1', null, null);
INSERT INTO `areas` VALUES ('256', '530000000000', '昭通市', '530600000000', '1', null, null);
INSERT INTO `areas` VALUES ('257', '530000000000', '丽江市', '530700000000', '1', null, null);
INSERT INTO `areas` VALUES ('258', '530000000000', '普洱市', '530800000000', '1', null, null);
INSERT INTO `areas` VALUES ('259', '530000000000', '临沧市', '530900000000', '1', null, null);
INSERT INTO `areas` VALUES ('260', '530000000000', '楚雄彝族自治州', '532300000000', '1', null, null);
INSERT INTO `areas` VALUES ('261', '530000000000', '红河哈尼族彝族自治州', '532500000000', '1', null, null);
INSERT INTO `areas` VALUES ('262', '530000000000', '文山壮族苗族自治州', '532600000000', '1', null, null);
INSERT INTO `areas` VALUES ('263', '530000000000', '西双版纳傣族自治州', '532800000000', '1', null, null);
INSERT INTO `areas` VALUES ('264', '530000000000', '大理白族自治州', '532900000000', '1', null, null);
INSERT INTO `areas` VALUES ('265', '530000000000', '德宏傣族景颇族自治州', '533100000000', '1', null, null);
INSERT INTO `areas` VALUES ('266', '530000000000', '怒江傈僳族自治州', '533300000000', '1', null, null);
INSERT INTO `areas` VALUES ('267', '530000000000', '迪庆藏族自治州', '533400000000', '1', null, null);
INSERT INTO `areas` VALUES ('268', '610000000000', '西安市', '610100000000', '1', null, null);
INSERT INTO `areas` VALUES ('269', '610000000000', '铜川市', '610200000000', '1', null, null);
INSERT INTO `areas` VALUES ('270', '610000000000', '宝鸡市', '610300000000', '1', null, null);
INSERT INTO `areas` VALUES ('271', '610000000000', '咸阳市', '610400000000', '1', null, null);
INSERT INTO `areas` VALUES ('272', '610000000000', '渭南市', '610500000000', '1', null, null);
INSERT INTO `areas` VALUES ('273', '610000000000', '延安市', '610600000000', '1', null, null);
INSERT INTO `areas` VALUES ('274', '610000000000', '汉中市', '610700000000', '1', null, null);
INSERT INTO `areas` VALUES ('275', '610000000000', '榆林市', '610800000000', '1', null, null);
INSERT INTO `areas` VALUES ('276', '610000000000', '安康市', '610900000000', '1', null, null);
INSERT INTO `areas` VALUES ('277', '610000000000', '商洛市', '611000000000', '1', null, null);
INSERT INTO `areas` VALUES ('278', '630000000000', '西宁市', '630100000000', '1', null, null);
INSERT INTO `areas` VALUES ('279', '630000000000', '海东市', '630200000000', '1', null, null);
INSERT INTO `areas` VALUES ('280', '630000000000', '海北藏族自治州', '632200000000', '1', null, null);
INSERT INTO `areas` VALUES ('281', '630000000000', '黄南藏族自治州', '632300000000', '1', null, null);
INSERT INTO `areas` VALUES ('282', '630000000000', '海南藏族自治州', '632500000000', '1', null, null);
INSERT INTO `areas` VALUES ('283', '630000000000', '果洛藏族自治州', '632600000000', '1', null, null);
INSERT INTO `areas` VALUES ('284', '630000000000', '玉树藏族自治州', '632700000000', '1', null, null);
INSERT INTO `areas` VALUES ('285', '630000000000', '海西蒙古族藏族自治州', '632800000000', '1', null, null);
INSERT INTO `areas` VALUES ('286', '640000000000', '银川市', '640100000000', '1', null, null);
INSERT INTO `areas` VALUES ('287', '640000000000', '石嘴山市', '640200000000', '1', null, null);
INSERT INTO `areas` VALUES ('288', '640000000000', '吴忠市', '640300000000', '1', null, null);
INSERT INTO `areas` VALUES ('289', '640000000000', '固原市', '640400000000', '1', null, null);
INSERT INTO `areas` VALUES ('290', '640000000000', '中卫市', '640500000000', '1', null, null);
INSERT INTO `areas` VALUES ('291', '650000000000', '乌鲁木齐市', '650100000000', '1', null, null);
INSERT INTO `areas` VALUES ('292', '650000000000', '克拉玛依市', '650200000000', '1', null, null);
INSERT INTO `areas` VALUES ('293', '650000000000', '吐鲁番市', '650400000000', '1', null, null);
INSERT INTO `areas` VALUES ('294', '650000000000', '哈密市', '650500000000', '1', null, null);
INSERT INTO `areas` VALUES ('295', '650000000000', '昌吉回族自治州', '652300000000', '1', null, null);
INSERT INTO `areas` VALUES ('296', '650000000000', '博尔塔拉蒙古自治州', '652700000000', '1', null, null);
INSERT INTO `areas` VALUES ('297', '650000000000', '巴音郭楞蒙古自治州', '652800000000', '1', null, null);
INSERT INTO `areas` VALUES ('298', '650000000000', '阿克苏地区', '652900000000', '1', null, null);
INSERT INTO `areas` VALUES ('299', '650000000000', '克孜勒苏柯尔克孜自治州', '653000000000', '1', null, null);
INSERT INTO `areas` VALUES ('300', '650000000000', '喀什地区', '653100000000', '1', null, null);
INSERT INTO `areas` VALUES ('301', '650000000000', '和田地区', '653200000000', '1', null, null);
INSERT INTO `areas` VALUES ('302', '650000000000', '伊犁哈萨克自治州', '654000000000', '1', null, null);
INSERT INTO `areas` VALUES ('303', '650000000000', '塔城地区', '654200000000', '1', null, null);
INSERT INTO `areas` VALUES ('304', '650000000000', '阿勒泰地区', '654300000000', '1', null, null);
INSERT INTO `areas` VALUES ('305', '650000000000', '自治区直辖县级行政区划', '659000000000', '1', null, null);
INSERT INTO `areas` VALUES ('306', '440000000000', '广州市', '440100000000', '1', null, null);
INSERT INTO `areas` VALUES ('307', '440000000000', '韶关市', '440200000000', '1', null, null);
INSERT INTO `areas` VALUES ('308', '440000000000', '深圳市', '440300000000', '1', null, null);
INSERT INTO `areas` VALUES ('309', '440000000000', '珠海市', '440400000000', '1', null, null);
INSERT INTO `areas` VALUES ('310', '440000000000', '汕头市', '440500000000', '1', null, null);
INSERT INTO `areas` VALUES ('311', '440000000000', '佛山市', '440600000000', '1', null, null);
INSERT INTO `areas` VALUES ('312', '440000000000', '江门市', '440700000000', '1', null, null);
INSERT INTO `areas` VALUES ('313', '440000000000', '湛江市', '440800000000', '1', null, null);
INSERT INTO `areas` VALUES ('314', '440000000000', '茂名市', '440900000000', '1', null, null);
INSERT INTO `areas` VALUES ('315', '440000000000', '肇庆市', '441200000000', '1', null, null);
INSERT INTO `areas` VALUES ('316', '440000000000', '惠州市', '441300000000', '1', null, null);
INSERT INTO `areas` VALUES ('317', '440000000000', '梅州市', '441400000000', '1', null, null);
INSERT INTO `areas` VALUES ('318', '440000000000', '汕尾市', '441500000000', '1', null, null);
INSERT INTO `areas` VALUES ('319', '440000000000', '河源市', '441600000000', '1', null, null);
INSERT INTO `areas` VALUES ('320', '440000000000', '阳江市', '441700000000', '1', null, null);
INSERT INTO `areas` VALUES ('321', '440000000000', '清远市', '441800000000', '1', null, null);
INSERT INTO `areas` VALUES ('322', '440000000000', '东莞市', '441900000000', '1', null, null);
INSERT INTO `areas` VALUES ('323', '440000000000', '中山市', '442000000000', '1', null, null);
INSERT INTO `areas` VALUES ('324', '440000000000', '潮州市', '445100000000', '1', null, null);
INSERT INTO `areas` VALUES ('325', '440000000000', '揭阳市', '445200000000', '1', null, null);
INSERT INTO `areas` VALUES ('326', '440000000000', '云浮市', '445300000000', '1', null, null);
INSERT INTO `areas` VALUES ('327', '460000000000', '海口市', '460100000000', '1', null, null);
INSERT INTO `areas` VALUES ('328', '460000000000', '三亚市', '460200000000', '1', null, null);
INSERT INTO `areas` VALUES ('329', '460000000000', '三沙市', '460300000000', '1', null, null);
INSERT INTO `areas` VALUES ('330', '460000000000', '儋州市', '460400000000', '1', null, null);
INSERT INTO `areas` VALUES ('331', '460000000000', '省直辖县级行政区划', '469000000000', '1', null, null);
INSERT INTO `areas` VALUES ('332', '500000000000', '市辖区', '500100000000', '1', null, null);
INSERT INTO `areas` VALUES ('333', '500000000000', '县', '500200000000', '1', null, null);
INSERT INTO `areas` VALUES ('334', '340000000000', '合肥市', '340100000000', '1', null, null);
INSERT INTO `areas` VALUES ('335', '340000000000', '芜湖市', '340200000000', '1', null, null);
INSERT INTO `areas` VALUES ('336', '340000000000', '蚌埠市', '340300000000', '1', null, null);
INSERT INTO `areas` VALUES ('337', '340000000000', '淮南市', '340400000000', '1', null, null);
INSERT INTO `areas` VALUES ('338', '340000000000', '马鞍山市', '340500000000', '1', null, null);
INSERT INTO `areas` VALUES ('339', '340000000000', '淮北市', '340600000000', '1', null, null);
INSERT INTO `areas` VALUES ('340', '340000000000', '铜陵市', '340700000000', '1', null, null);
INSERT INTO `areas` VALUES ('341', '340000000000', '安庆市', '340800000000', '1', null, null);
INSERT INTO `areas` VALUES ('342', '340000000000', '黄山市', '341000000000', '1', null, null);
INSERT INTO `areas` VALUES ('343', '340000000000', '滁州市', '341100000000', '1', null, null);
INSERT INTO `areas` VALUES ('344', '340000000000', '阜阳市', '341200000000', '1', null, null);
INSERT INTO `areas` VALUES ('345', '340000000000', '宿州市', '341300000000', '1', null, null);
INSERT INTO `areas` VALUES ('346', '340000000000', '六安市', '341500000000', '1', null, null);
INSERT INTO `areas` VALUES ('347', '340000000000', '亳州市', '341600000000', '1', null, null);
INSERT INTO `areas` VALUES ('348', '340000000000', '池州市', '341700000000', '1', null, null);
INSERT INTO `areas` VALUES ('349', '340000000000', '宣城市', '341800000000', '1', null, null);
INSERT INTO `areas` VALUES ('350', '320000000000', '南京市', '320100000000', '1', null, null);
INSERT INTO `areas` VALUES ('351', '320000000000', '无锡市', '320200000000', '1', null, null);
INSERT INTO `areas` VALUES ('352', '320000000000', '徐州市', '320300000000', '1', null, null);
INSERT INTO `areas` VALUES ('353', '320000000000', '常州市', '320400000000', '1', null, null);
INSERT INTO `areas` VALUES ('354', '320000000000', '苏州市', '320500000000', '1', null, null);
INSERT INTO `areas` VALUES ('355', '320000000000', '南通市', '320600000000', '1', null, null);
INSERT INTO `areas` VALUES ('356', '320000000000', '连云港市', '320700000000', '1', null, null);
INSERT INTO `areas` VALUES ('357', '320000000000', '淮安市', '320800000000', '1', null, null);
INSERT INTO `areas` VALUES ('358', '320000000000', '盐城市', '320900000000', '1', null, null);
INSERT INTO `areas` VALUES ('359', '320000000000', '扬州市', '321000000000', '1', null, null);
INSERT INTO `areas` VALUES ('360', '320000000000', '镇江市', '321100000000', '1', null, null);
INSERT INTO `areas` VALUES ('361', '320000000000', '泰州市', '321200000000', '1', null, null);
INSERT INTO `areas` VALUES ('362', '320000000000', '宿迁市', '321300000000', '1', null, null);
INSERT INTO `areas` VALUES ('363', '141100000000', '市辖区', '141101000000', '2', null, null);
INSERT INTO `areas` VALUES ('364', '141100000000', '离石区', '141102000000', '2', null, null);
INSERT INTO `areas` VALUES ('365', '141100000000', '文水县', '141121000000', '2', null, null);
INSERT INTO `areas` VALUES ('366', '141100000000', '交城县', '141122000000', '2', null, null);
INSERT INTO `areas` VALUES ('367', '141100000000', '兴县', '141123000000', '2', null, null);
INSERT INTO `areas` VALUES ('368', '141100000000', '临县', '141124000000', '2', null, null);
INSERT INTO `areas` VALUES ('369', '141100000000', '柳林县', '141125000000', '2', null, null);
INSERT INTO `areas` VALUES ('370', '141100000000', '石楼县', '141126000000', '2', null, null);
INSERT INTO `areas` VALUES ('371', '141100000000', '岚县', '141127000000', '2', null, null);
INSERT INTO `areas` VALUES ('372', '141100000000', '方山县', '141128000000', '2', null, null);
INSERT INTO `areas` VALUES ('373', '141100000000', '中阳县', '141129000000', '2', null, null);
INSERT INTO `areas` VALUES ('374', '141100000000', '交口县', '141130000000', '2', null, null);
INSERT INTO `areas` VALUES ('375', '141100000000', '孝义市', '141181000000', '2', null, null);
INSERT INTO `areas` VALUES ('376', '141100000000', '汾阳市', '141182000000', '2', null, null);
INSERT INTO `areas` VALUES ('377', '140200000000', '市辖区', '140201000000', '2', null, null);
INSERT INTO `areas` VALUES ('378', '140200000000', '新荣区', '140212000000', '2', null, null);
INSERT INTO `areas` VALUES ('379', '140200000000', '平城区', '140213000000', '2', null, null);
INSERT INTO `areas` VALUES ('380', '140200000000', '云冈区', '140214000000', '2', null, null);
INSERT INTO `areas` VALUES ('381', '140200000000', '云州区', '140215000000', '2', null, null);
INSERT INTO `areas` VALUES ('382', '140200000000', '阳高县', '140221000000', '2', null, null);
INSERT INTO `areas` VALUES ('383', '140200000000', '天镇县', '140222000000', '2', null, null);
INSERT INTO `areas` VALUES ('384', '140200000000', '广灵县', '140223000000', '2', null, null);
INSERT INTO `areas` VALUES ('385', '140200000000', '灵丘县', '140224000000', '2', null, null);
INSERT INTO `areas` VALUES ('386', '140200000000', '浑源县', '140225000000', '2', null, null);
INSERT INTO `areas` VALUES ('387', '140200000000', '左云县', '140226000000', '2', null, null);
INSERT INTO `areas` VALUES ('388', '140200000000', '山西大同经济开发区', '140271000000', '2', null, null);
INSERT INTO `areas` VALUES ('389', '140500000000', '市辖区', '140501000000', '2', null, null);
INSERT INTO `areas` VALUES ('390', '140500000000', '城区', '140502000000', '2', null, null);
INSERT INTO `areas` VALUES ('391', '140500000000', '沁水县', '140521000000', '2', null, null);
INSERT INTO `areas` VALUES ('392', '140500000000', '阳城县', '140522000000', '2', null, null);
INSERT INTO `areas` VALUES ('393', '140500000000', '陵川县', '140524000000', '2', null, null);
INSERT INTO `areas` VALUES ('394', '140500000000', '泽州县', '140525000000', '2', null, null);
INSERT INTO `areas` VALUES ('395', '140500000000', '高平市', '140581000000', '2', null, null);
INSERT INTO `areas` VALUES ('396', '152500000000', '二连浩特市', '152501000000', '2', null, null);
INSERT INTO `areas` VALUES ('397', '152500000000', '锡林浩特市', '152502000000', '2', null, null);
INSERT INTO `areas` VALUES ('398', '152500000000', '阿巴嘎旗', '152522000000', '2', null, null);
INSERT INTO `areas` VALUES ('399', '152500000000', '苏尼特左旗', '152523000000', '2', null, null);
INSERT INTO `areas` VALUES ('400', '152500000000', '苏尼特右旗', '152524000000', '2', null, null);
INSERT INTO `areas` VALUES ('401', '152500000000', '东乌珠穆沁旗', '152525000000', '2', null, null);
INSERT INTO `areas` VALUES ('402', '152500000000', '西乌珠穆沁旗', '152526000000', '2', null, null);
INSERT INTO `areas` VALUES ('403', '152500000000', '太仆寺旗', '152527000000', '2', null, null);
INSERT INTO `areas` VALUES ('404', '152500000000', '镶黄旗', '152528000000', '2', null, null);
INSERT INTO `areas` VALUES ('405', '152500000000', '正镶白旗', '152529000000', '2', null, null);
INSERT INTO `areas` VALUES ('406', '152500000000', '正蓝旗', '152530000000', '2', null, null);
INSERT INTO `areas` VALUES ('407', '152500000000', '多伦县', '152531000000', '2', null, null);
INSERT INTO `areas` VALUES ('408', '152500000000', '乌拉盖管委会', '152571000000', '2', null, null);
INSERT INTO `areas` VALUES ('409', '140100000000', '市辖区', '140101000000', '2', null, null);
INSERT INTO `areas` VALUES ('410', '140100000000', '小店区', '140105000000', '2', null, null);
INSERT INTO `areas` VALUES ('411', '140100000000', '迎泽区', '140106000000', '2', null, null);
INSERT INTO `areas` VALUES ('412', '140100000000', '杏花岭区', '140107000000', '2', null, null);
INSERT INTO `areas` VALUES ('413', '140100000000', '尖草坪区', '140108000000', '2', null, null);
INSERT INTO `areas` VALUES ('414', '140100000000', '万柏林区', '140109000000', '2', null, null);
INSERT INTO `areas` VALUES ('415', '140100000000', '晋源区', '140110000000', '2', null, null);
INSERT INTO `areas` VALUES ('416', '140100000000', '清徐县', '140121000000', '2', null, null);
INSERT INTO `areas` VALUES ('417', '140100000000', '阳曲县', '140122000000', '2', null, null);
INSERT INTO `areas` VALUES ('418', '140100000000', '娄烦县', '140123000000', '2', null, null);
INSERT INTO `areas` VALUES ('419', '140100000000', '山西转型综合改革示范区', '140171000000', '2', null, null);
INSERT INTO `areas` VALUES ('420', '140100000000', '古交市', '140181000000', '2', null, null);
INSERT INTO `areas` VALUES ('421', '140600000000', '市辖区', '140601000000', '2', null, null);
INSERT INTO `areas` VALUES ('422', '140600000000', '朔城区', '140602000000', '2', null, null);
INSERT INTO `areas` VALUES ('423', '140600000000', '平鲁区', '140603000000', '2', null, null);
INSERT INTO `areas` VALUES ('424', '140600000000', '山阴县', '140621000000', '2', null, null);
INSERT INTO `areas` VALUES ('425', '140600000000', '应县', '140622000000', '2', null, null);
INSERT INTO `areas` VALUES ('426', '140600000000', '右玉县', '140623000000', '2', null, null);
INSERT INTO `areas` VALUES ('427', '140600000000', '山西朔州经济开发区', '140671000000', '2', null, null);
INSERT INTO `areas` VALUES ('428', '140600000000', '怀仁市', '140681000000', '2', null, null);
INSERT INTO `areas` VALUES ('429', '140700000000', '市辖区', '140701000000', '2', null, null);
INSERT INTO `areas` VALUES ('430', '140700000000', '榆次区', '140702000000', '2', null, null);
INSERT INTO `areas` VALUES ('431', '140700000000', '榆社县', '140721000000', '2', null, null);
INSERT INTO `areas` VALUES ('432', '140700000000', '左权县', '140722000000', '2', null, null);
INSERT INTO `areas` VALUES ('433', '140700000000', '和顺县', '140723000000', '2', null, null);
INSERT INTO `areas` VALUES ('434', '140700000000', '昔阳县', '140724000000', '2', null, null);
INSERT INTO `areas` VALUES ('435', '140700000000', '寿阳县', '140725000000', '2', null, null);
INSERT INTO `areas` VALUES ('436', '140700000000', '太谷县', '140726000000', '2', null, null);
INSERT INTO `areas` VALUES ('437', '140700000000', '祁县', '140727000000', '2', null, null);
INSERT INTO `areas` VALUES ('438', '140700000000', '平遥县', '140728000000', '2', null, null);
INSERT INTO `areas` VALUES ('439', '140700000000', '灵石县', '140729000000', '2', null, null);
INSERT INTO `areas` VALUES ('440', '140700000000', '介休市', '140781000000', '2', null, null);
INSERT INTO `areas` VALUES ('441', '140800000000', '市辖区', '140801000000', '2', null, null);
INSERT INTO `areas` VALUES ('442', '140800000000', '盐湖区', '140802000000', '2', null, null);
INSERT INTO `areas` VALUES ('443', '140800000000', '临猗县', '140821000000', '2', null, null);
INSERT INTO `areas` VALUES ('444', '140800000000', '万荣县', '140822000000', '2', null, null);
INSERT INTO `areas` VALUES ('445', '140800000000', '闻喜县', '140823000000', '2', null, null);
INSERT INTO `areas` VALUES ('446', '140800000000', '稷山县', '140824000000', '2', null, null);
INSERT INTO `areas` VALUES ('447', '140800000000', '新绛县', '140825000000', '2', null, null);
INSERT INTO `areas` VALUES ('448', '140800000000', '绛县', '140826000000', '2', null, null);
INSERT INTO `areas` VALUES ('449', '140800000000', '垣曲县', '140827000000', '2', null, null);
INSERT INTO `areas` VALUES ('450', '140800000000', '夏县', '140828000000', '2', null, null);
INSERT INTO `areas` VALUES ('451', '140800000000', '平陆县', '140829000000', '2', null, null);
INSERT INTO `areas` VALUES ('452', '140800000000', '芮城县', '140830000000', '2', null, null);
INSERT INTO `areas` VALUES ('453', '140800000000', '永济市', '140881000000', '2', null, null);
INSERT INTO `areas` VALUES ('454', '140800000000', '河津市', '140882000000', '2', null, null);
INSERT INTO `areas` VALUES ('455', '150100000000', '市辖区', '150101000000', '2', null, null);
INSERT INTO `areas` VALUES ('456', '150100000000', '新城区', '150102000000', '2', null, null);
INSERT INTO `areas` VALUES ('457', '150100000000', '回民区', '150103000000', '2', null, null);
INSERT INTO `areas` VALUES ('458', '150100000000', '玉泉区', '150104000000', '2', null, null);
INSERT INTO `areas` VALUES ('459', '150100000000', '赛罕区', '150105000000', '2', null, null);
INSERT INTO `areas` VALUES ('460', '150100000000', '土默特左旗', '150121000000', '2', null, null);
INSERT INTO `areas` VALUES ('461', '150100000000', '托克托县', '150122000000', '2', null, null);
INSERT INTO `areas` VALUES ('462', '150100000000', '和林格尔县', '150123000000', '2', null, null);
INSERT INTO `areas` VALUES ('463', '150100000000', '清水河县', '150124000000', '2', null, null);
INSERT INTO `areas` VALUES ('464', '150100000000', '武川县', '150125000000', '2', null, null);
INSERT INTO `areas` VALUES ('465', '150100000000', '呼和浩特金海工业园区', '150171000000', '2', null, null);
INSERT INTO `areas` VALUES ('466', '150100000000', '呼和浩特经济技术开发区', '150172000000', '2', null, null);
INSERT INTO `areas` VALUES ('467', '150200000000', '市辖区', '150201000000', '2', null, null);
INSERT INTO `areas` VALUES ('468', '150200000000', '东河区', '150202000000', '2', null, null);
INSERT INTO `areas` VALUES ('469', '150200000000', '昆都仑区', '150203000000', '2', null, null);
INSERT INTO `areas` VALUES ('470', '150200000000', '青山区', '150204000000', '2', null, null);
INSERT INTO `areas` VALUES ('471', '150200000000', '石拐区', '150205000000', '2', null, null);
INSERT INTO `areas` VALUES ('472', '150200000000', '白云鄂博矿区', '150206000000', '2', null, null);
INSERT INTO `areas` VALUES ('473', '150200000000', '九原区', '150207000000', '2', null, null);
INSERT INTO `areas` VALUES ('474', '150200000000', '土默特右旗', '150221000000', '2', null, null);
INSERT INTO `areas` VALUES ('475', '150200000000', '固阳县', '150222000000', '2', null, null);
INSERT INTO `areas` VALUES ('476', '150200000000', '达尔罕茂明安联合旗', '150223000000', '2', null, null);
INSERT INTO `areas` VALUES ('477', '150200000000', '包头稀土高新技术产业开发区', '150271000000', '2', null, null);
INSERT INTO `areas` VALUES ('478', '150900000000', '市辖区', '150901000000', '2', null, null);
INSERT INTO `areas` VALUES ('479', '150900000000', '集宁区', '150902000000', '2', null, null);
INSERT INTO `areas` VALUES ('480', '150900000000', '卓资县', '150921000000', '2', null, null);
INSERT INTO `areas` VALUES ('481', '150900000000', '化德县', '150922000000', '2', null, null);
INSERT INTO `areas` VALUES ('482', '150900000000', '商都县', '150923000000', '2', null, null);
INSERT INTO `areas` VALUES ('483', '150900000000', '兴和县', '150924000000', '2', null, null);
INSERT INTO `areas` VALUES ('484', '150900000000', '凉城县', '150925000000', '2', null, null);
INSERT INTO `areas` VALUES ('485', '150900000000', '察哈尔右翼前旗', '150926000000', '2', null, null);
INSERT INTO `areas` VALUES ('486', '150900000000', '察哈尔右翼中旗', '150927000000', '2', null, null);
INSERT INTO `areas` VALUES ('487', '150900000000', '察哈尔右翼后旗', '150928000000', '2', null, null);
INSERT INTO `areas` VALUES ('488', '150900000000', '四子王旗', '150929000000', '2', null, null);
INSERT INTO `areas` VALUES ('489', '150900000000', '丰镇市', '150981000000', '2', null, null);
INSERT INTO `areas` VALUES ('490', '152200000000', '乌兰浩特市', '152201000000', '2', null, null);
INSERT INTO `areas` VALUES ('491', '152200000000', '阿尔山市', '152202000000', '2', null, null);
INSERT INTO `areas` VALUES ('492', '152200000000', '科尔沁右翼前旗', '152221000000', '2', null, null);
INSERT INTO `areas` VALUES ('493', '152200000000', '科尔沁右翼中旗', '152222000000', '2', null, null);
INSERT INTO `areas` VALUES ('494', '152200000000', '扎赉特旗', '152223000000', '2', null, null);
INSERT INTO `areas` VALUES ('495', '152200000000', '突泉县', '152224000000', '2', null, null);
INSERT INTO `areas` VALUES ('496', '152900000000', '阿拉善左旗', '152921000000', '2', null, null);
INSERT INTO `areas` VALUES ('497', '152900000000', '阿拉善右旗', '152922000000', '2', null, null);
INSERT INTO `areas` VALUES ('498', '152900000000', '额济纳旗', '152923000000', '2', null, null);
INSERT INTO `areas` VALUES ('499', '152900000000', '内蒙古阿拉善经济开发区', '152971000000', '2', null, null);
INSERT INTO `areas` VALUES ('500', '540300000000', '卡若区', '540302000000', '2', null, null);
INSERT INTO `areas` VALUES ('501', '540300000000', '江达县', '540321000000', '2', null, null);
INSERT INTO `areas` VALUES ('502', '540300000000', '贡觉县', '540322000000', '2', null, null);
INSERT INTO `areas` VALUES ('503', '540300000000', '类乌齐县', '540323000000', '2', null, null);
INSERT INTO `areas` VALUES ('504', '540300000000', '丁青县', '540324000000', '2', null, null);
INSERT INTO `areas` VALUES ('505', '540300000000', '察雅县', '540325000000', '2', null, null);
INSERT INTO `areas` VALUES ('506', '540300000000', '八宿县', '540326000000', '2', null, null);
INSERT INTO `areas` VALUES ('507', '540300000000', '左贡县', '540327000000', '2', null, null);
INSERT INTO `areas` VALUES ('508', '540300000000', '芒康县', '540328000000', '2', null, null);
INSERT INTO `areas` VALUES ('509', '540300000000', '洛隆县', '540329000000', '2', null, null);
INSERT INTO `areas` VALUES ('510', '540300000000', '边坝县', '540330000000', '2', null, null);
INSERT INTO `areas` VALUES ('511', '540400000000', '巴宜区', '540402000000', '2', null, null);
INSERT INTO `areas` VALUES ('512', '540400000000', '工布江达县', '540421000000', '2', null, null);
INSERT INTO `areas` VALUES ('513', '540400000000', '米林县', '540422000000', '2', null, null);
INSERT INTO `areas` VALUES ('514', '540400000000', '墨脱县', '540423000000', '2', null, null);
INSERT INTO `areas` VALUES ('515', '540400000000', '波密县', '540424000000', '2', null, null);
INSERT INTO `areas` VALUES ('516', '540400000000', '察隅县', '540425000000', '2', null, null);
INSERT INTO `areas` VALUES ('517', '540400000000', '朗县', '540426000000', '2', null, null);
INSERT INTO `areas` VALUES ('518', '542500000000', '普兰县', '542521000000', '2', null, null);
INSERT INTO `areas` VALUES ('519', '542500000000', '札达县', '542522000000', '2', null, null);
INSERT INTO `areas` VALUES ('520', '542500000000', '噶尔县', '542523000000', '2', null, null);
INSERT INTO `areas` VALUES ('521', '542500000000', '日土县', '542524000000', '2', null, null);
INSERT INTO `areas` VALUES ('522', '542500000000', '革吉县', '542525000000', '2', null, null);
INSERT INTO `areas` VALUES ('523', '542500000000', '改则县', '542526000000', '2', null, null);
INSERT INTO `areas` VALUES ('524', '542500000000', '措勤县', '542527000000', '2', null, null);
INSERT INTO `areas` VALUES ('525', '620200000000', '市辖区', '620201000000', '2', null, null);
INSERT INTO `areas` VALUES ('526', '620400000000', '市辖区', '620401000000', '2', null, null);
INSERT INTO `areas` VALUES ('527', '620400000000', '白银区', '620402000000', '2', null, null);
INSERT INTO `areas` VALUES ('528', '620400000000', '平川区', '620403000000', '2', null, null);
INSERT INTO `areas` VALUES ('529', '620400000000', '靖远县', '620421000000', '2', null, null);
INSERT INTO `areas` VALUES ('530', '620400000000', '会宁县', '620422000000', '2', null, null);
INSERT INTO `areas` VALUES ('531', '620400000000', '景泰县', '620423000000', '2', null, null);
INSERT INTO `areas` VALUES ('532', '140300000000', '市辖区', '140301000000', '2', null, null);
INSERT INTO `areas` VALUES ('533', '140300000000', '城区', '140302000000', '2', null, null);
INSERT INTO `areas` VALUES ('534', '140300000000', '矿区', '140303000000', '2', null, null);
INSERT INTO `areas` VALUES ('535', '140300000000', '郊区', '140311000000', '2', null, null);
INSERT INTO `areas` VALUES ('536', '140300000000', '平定县', '140321000000', '2', null, null);
INSERT INTO `areas` VALUES ('537', '140300000000', '盂县', '140322000000', '2', null, null);
INSERT INTO `areas` VALUES ('538', '140400000000', '市辖区', '140401000000', '2', null, null);
INSERT INTO `areas` VALUES ('539', '140400000000', '潞州区', '140403000000', '2', null, null);
INSERT INTO `areas` VALUES ('540', '140400000000', '上党区', '140404000000', '2', null, null);
INSERT INTO `areas` VALUES ('541', '140400000000', '屯留区', '140405000000', '2', null, null);
INSERT INTO `areas` VALUES ('542', '140400000000', '潞城区', '140406000000', '2', null, null);
INSERT INTO `areas` VALUES ('543', '140400000000', '襄垣县', '140423000000', '2', null, null);
INSERT INTO `areas` VALUES ('544', '140400000000', '平顺县', '140425000000', '2', null, null);
INSERT INTO `areas` VALUES ('545', '140400000000', '黎城县', '140426000000', '2', null, null);
INSERT INTO `areas` VALUES ('546', '140400000000', '壶关县', '140427000000', '2', null, null);
INSERT INTO `areas` VALUES ('547', '140400000000', '长子县', '140428000000', '2', null, null);
INSERT INTO `areas` VALUES ('548', '140400000000', '武乡县', '140429000000', '2', null, null);
INSERT INTO `areas` VALUES ('549', '140400000000', '沁县', '140430000000', '2', null, null);
INSERT INTO `areas` VALUES ('550', '140400000000', '沁源县', '140431000000', '2', null, null);
INSERT INTO `areas` VALUES ('551', '140400000000', '山西长治高新技术产业园区', '140471000000', '2', null, null);
INSERT INTO `areas` VALUES ('552', '150300000000', '市辖区', '150301000000', '2', null, null);
INSERT INTO `areas` VALUES ('553', '150300000000', '海勃湾区', '150302000000', '2', null, null);
INSERT INTO `areas` VALUES ('554', '150300000000', '海南区', '150303000000', '2', null, null);
INSERT INTO `areas` VALUES ('555', '150300000000', '乌达区', '150304000000', '2', null, null);
INSERT INTO `areas` VALUES ('556', '150500000000', '市辖区', '150501000000', '2', null, null);
INSERT INTO `areas` VALUES ('557', '150500000000', '科尔沁区', '150502000000', '2', null, null);
INSERT INTO `areas` VALUES ('558', '150500000000', '科尔沁左翼中旗', '150521000000', '2', null, null);
INSERT INTO `areas` VALUES ('559', '150500000000', '科尔沁左翼后旗', '150522000000', '2', null, null);
INSERT INTO `areas` VALUES ('560', '150500000000', '开鲁县', '150523000000', '2', null, null);
INSERT INTO `areas` VALUES ('561', '150500000000', '库伦旗', '150524000000', '2', null, null);
INSERT INTO `areas` VALUES ('562', '150500000000', '奈曼旗', '150525000000', '2', null, null);
INSERT INTO `areas` VALUES ('563', '150500000000', '扎鲁特旗', '150526000000', '2', null, null);
INSERT INTO `areas` VALUES ('564', '150500000000', '通辽经济技术开发区', '150571000000', '2', null, null);
INSERT INTO `areas` VALUES ('565', '150500000000', '霍林郭勒市', '150581000000', '2', null, null);
INSERT INTO `areas` VALUES ('566', '150700000000', '市辖区', '150701000000', '2', null, null);
INSERT INTO `areas` VALUES ('567', '150700000000', '海拉尔区', '150702000000', '2', null, null);
INSERT INTO `areas` VALUES ('568', '150700000000', '扎赉诺尔区', '150703000000', '2', null, null);
INSERT INTO `areas` VALUES ('569', '150700000000', '阿荣旗', '150721000000', '2', null, null);
INSERT INTO `areas` VALUES ('570', '150700000000', '莫力达瓦达斡尔族自治旗', '150722000000', '2', null, null);
INSERT INTO `areas` VALUES ('571', '150700000000', '鄂伦春自治旗', '150723000000', '2', null, null);
INSERT INTO `areas` VALUES ('572', '150700000000', '鄂温克族自治旗', '150724000000', '2', null, null);
INSERT INTO `areas` VALUES ('573', '150700000000', '陈巴尔虎旗', '150725000000', '2', null, null);
INSERT INTO `areas` VALUES ('574', '150700000000', '新巴尔虎左旗', '150726000000', '2', null, null);
INSERT INTO `areas` VALUES ('575', '150700000000', '新巴尔虎右旗', '150727000000', '2', null, null);
INSERT INTO `areas` VALUES ('576', '150700000000', '满洲里市', '150781000000', '2', null, null);
INSERT INTO `areas` VALUES ('577', '150700000000', '牙克石市', '150782000000', '2', null, null);
INSERT INTO `areas` VALUES ('578', '150700000000', '扎兰屯市', '150783000000', '2', null, null);
INSERT INTO `areas` VALUES ('579', '150700000000', '额尔古纳市', '150784000000', '2', null, null);
INSERT INTO `areas` VALUES ('580', '150700000000', '根河市', '150785000000', '2', null, null);
INSERT INTO `areas` VALUES ('581', '150800000000', '市辖区', '150801000000', '2', null, null);
INSERT INTO `areas` VALUES ('582', '150800000000', '临河区', '150802000000', '2', null, null);
INSERT INTO `areas` VALUES ('583', '150800000000', '五原县', '150821000000', '2', null, null);
INSERT INTO `areas` VALUES ('584', '150800000000', '磴口县', '150822000000', '2', null, null);
INSERT INTO `areas` VALUES ('585', '150800000000', '乌拉特前旗', '150823000000', '2', null, null);
INSERT INTO `areas` VALUES ('586', '150800000000', '乌拉特中旗', '150824000000', '2', null, null);
INSERT INTO `areas` VALUES ('587', '150800000000', '乌拉特后旗', '150825000000', '2', null, null);
INSERT INTO `areas` VALUES ('588', '150800000000', '杭锦后旗', '150826000000', '2', null, null);
INSERT INTO `areas` VALUES ('589', '620100000000', '市辖区', '620101000000', '2', null, null);
INSERT INTO `areas` VALUES ('590', '620100000000', '城关区', '620102000000', '2', null, null);
INSERT INTO `areas` VALUES ('591', '620100000000', '七里河区', '620103000000', '2', null, null);
INSERT INTO `areas` VALUES ('592', '620100000000', '西固区', '620104000000', '2', null, null);
INSERT INTO `areas` VALUES ('593', '620100000000', '安宁区', '620105000000', '2', null, null);
INSERT INTO `areas` VALUES ('594', '620100000000', '红古区', '620111000000', '2', null, null);
INSERT INTO `areas` VALUES ('595', '620100000000', '永登县', '620121000000', '2', null, null);
INSERT INTO `areas` VALUES ('596', '620100000000', '皋兰县', '620122000000', '2', null, null);
INSERT INTO `areas` VALUES ('597', '620100000000', '榆中县', '620123000000', '2', null, null);
INSERT INTO `areas` VALUES ('598', '620100000000', '兰州新区', '620171000000', '2', null, null);
INSERT INTO `areas` VALUES ('599', '620500000000', '市辖区', '620501000000', '2', null, null);
INSERT INTO `areas` VALUES ('600', '620500000000', '秦州区', '620502000000', '2', null, null);
INSERT INTO `areas` VALUES ('601', '620500000000', '麦积区', '620503000000', '2', null, null);
INSERT INTO `areas` VALUES ('602', '620500000000', '清水县', '620521000000', '2', null, null);
INSERT INTO `areas` VALUES ('603', '620500000000', '秦安县', '620522000000', '2', null, null);
INSERT INTO `areas` VALUES ('604', '620500000000', '甘谷县', '620523000000', '2', null, null);
INSERT INTO `areas` VALUES ('605', '620500000000', '武山县', '620524000000', '2', null, null);
INSERT INTO `areas` VALUES ('606', '620500000000', '张家川回族自治县', '620525000000', '2', null, null);
INSERT INTO `areas` VALUES ('607', '620600000000', '市辖区', '620601000000', '2', null, null);
INSERT INTO `areas` VALUES ('608', '620600000000', '凉州区', '620602000000', '2', null, null);
INSERT INTO `areas` VALUES ('609', '620600000000', '民勤县', '620621000000', '2', null, null);
INSERT INTO `areas` VALUES ('610', '620600000000', '古浪县', '620622000000', '2', null, null);
INSERT INTO `areas` VALUES ('611', '620600000000', '天祝藏族自治县', '620623000000', '2', null, null);
INSERT INTO `areas` VALUES ('612', '620700000000', '市辖区', '620701000000', '2', null, null);
INSERT INTO `areas` VALUES ('613', '620700000000', '甘州区', '620702000000', '2', null, null);
INSERT INTO `areas` VALUES ('614', '620700000000', '肃南裕固族自治县', '620721000000', '2', null, null);
INSERT INTO `areas` VALUES ('615', '620700000000', '民乐县', '620722000000', '2', null, null);
INSERT INTO `areas` VALUES ('616', '620700000000', '临泽县', '620723000000', '2', null, null);
INSERT INTO `areas` VALUES ('617', '620700000000', '高台县', '620724000000', '2', null, null);
INSERT INTO `areas` VALUES ('618', '620700000000', '山丹县', '620725000000', '2', null, null);
INSERT INTO `areas` VALUES ('619', '620800000000', '市辖区', '620801000000', '2', null, null);
INSERT INTO `areas` VALUES ('620', '620800000000', '崆峒区', '620802000000', '2', null, null);
INSERT INTO `areas` VALUES ('621', '620800000000', '泾川县', '620821000000', '2', null, null);
INSERT INTO `areas` VALUES ('622', '620800000000', '灵台县', '620822000000', '2', null, null);
INSERT INTO `areas` VALUES ('623', '620800000000', '崇信县', '620823000000', '2', null, null);
INSERT INTO `areas` VALUES ('624', '620800000000', '庄浪县', '620825000000', '2', null, null);
INSERT INTO `areas` VALUES ('625', '620800000000', '静宁县', '620826000000', '2', null, null);
INSERT INTO `areas` VALUES ('626', '620800000000', '华亭市', '620881000000', '2', null, null);
INSERT INTO `areas` VALUES ('627', '621000000000', '市辖区', '621001000000', '2', null, null);
INSERT INTO `areas` VALUES ('628', '621000000000', '西峰区', '621002000000', '2', null, null);
INSERT INTO `areas` VALUES ('629', '621000000000', '庆城县', '621021000000', '2', null, null);
INSERT INTO `areas` VALUES ('630', '621000000000', '环县', '621022000000', '2', null, null);
INSERT INTO `areas` VALUES ('631', '621000000000', '华池县', '621023000000', '2', null, null);
INSERT INTO `areas` VALUES ('632', '621000000000', '合水县', '621024000000', '2', null, null);
INSERT INTO `areas` VALUES ('633', '621000000000', '正宁县', '621025000000', '2', null, null);
INSERT INTO `areas` VALUES ('634', '621000000000', '宁县', '621026000000', '2', null, null);
INSERT INTO `areas` VALUES ('635', '621000000000', '镇原县', '621027000000', '2', null, null);
INSERT INTO `areas` VALUES ('636', '130400000000', '市辖区', '130401000000', '2', null, null);
INSERT INTO `areas` VALUES ('637', '130400000000', '邯山区', '130402000000', '2', null, null);
INSERT INTO `areas` VALUES ('638', '130400000000', '丛台区', '130403000000', '2', null, null);
INSERT INTO `areas` VALUES ('639', '130400000000', '复兴区', '130404000000', '2', null, null);
INSERT INTO `areas` VALUES ('640', '130400000000', '峰峰矿区', '130406000000', '2', null, null);
INSERT INTO `areas` VALUES ('641', '130400000000', '肥乡区', '130407000000', '2', null, null);
INSERT INTO `areas` VALUES ('642', '130400000000', '永年区', '130408000000', '2', null, null);
INSERT INTO `areas` VALUES ('643', '130400000000', '临漳县', '130423000000', '2', null, null);
INSERT INTO `areas` VALUES ('644', '130400000000', '成安县', '130424000000', '2', null, null);
INSERT INTO `areas` VALUES ('645', '130400000000', '大名县', '130425000000', '2', null, null);
INSERT INTO `areas` VALUES ('646', '130400000000', '涉县', '130426000000', '2', null, null);
INSERT INTO `areas` VALUES ('647', '130400000000', '磁县', '130427000000', '2', null, null);
INSERT INTO `areas` VALUES ('648', '130400000000', '邱县', '130430000000', '2', null, null);
INSERT INTO `areas` VALUES ('649', '130400000000', '鸡泽县', '130431000000', '2', null, null);
INSERT INTO `areas` VALUES ('650', '130400000000', '广平县', '130432000000', '2', null, null);
INSERT INTO `areas` VALUES ('651', '130400000000', '馆陶县', '130433000000', '2', null, null);
INSERT INTO `areas` VALUES ('652', '130400000000', '魏县', '130434000000', '2', null, null);
INSERT INTO `areas` VALUES ('653', '130400000000', '曲周县', '130435000000', '2', null, null);
INSERT INTO `areas` VALUES ('654', '130400000000', '邯郸经济技术开发区', '130471000000', '2', null, null);
INSERT INTO `areas` VALUES ('655', '130400000000', '邯郸冀南新区', '130473000000', '2', null, null);
INSERT INTO `areas` VALUES ('656', '130400000000', '武安市', '130481000000', '2', null, null);
INSERT INTO `areas` VALUES ('657', '131000000000', '市辖区', '131001000000', '2', null, null);
INSERT INTO `areas` VALUES ('658', '131000000000', '安次区', '131002000000', '2', null, null);
INSERT INTO `areas` VALUES ('659', '131000000000', '广阳区', '131003000000', '2', null, null);
INSERT INTO `areas` VALUES ('660', '131000000000', '固安县', '131022000000', '2', null, null);
INSERT INTO `areas` VALUES ('661', '131000000000', '永清县', '131023000000', '2', null, null);
INSERT INTO `areas` VALUES ('662', '131000000000', '香河县', '131024000000', '2', null, null);
INSERT INTO `areas` VALUES ('663', '131000000000', '大城县', '131025000000', '2', null, null);
INSERT INTO `areas` VALUES ('664', '131000000000', '文安县', '131026000000', '2', null, null);
INSERT INTO `areas` VALUES ('665', '131000000000', '大厂回族自治县', '131028000000', '2', null, null);
INSERT INTO `areas` VALUES ('666', '131000000000', '廊坊经济技术开发区', '131071000000', '2', null, null);
INSERT INTO `areas` VALUES ('667', '131000000000', '霸州市', '131081000000', '2', null, null);
INSERT INTO `areas` VALUES ('668', '131000000000', '三河市', '131082000000', '2', null, null);
INSERT INTO `areas` VALUES ('669', '370200000000', '市辖区', '370201000000', '2', null, null);
INSERT INTO `areas` VALUES ('670', '370200000000', '市南区', '370202000000', '2', null, null);
INSERT INTO `areas` VALUES ('671', '370200000000', '市北区', '370203000000', '2', null, null);
INSERT INTO `areas` VALUES ('672', '370200000000', '黄岛区', '370211000000', '2', null, null);
INSERT INTO `areas` VALUES ('673', '370200000000', '崂山区', '370212000000', '2', null, null);
INSERT INTO `areas` VALUES ('674', '370200000000', '李沧区', '370213000000', '2', null, null);
INSERT INTO `areas` VALUES ('675', '370200000000', '城阳区', '370214000000', '2', null, null);
INSERT INTO `areas` VALUES ('676', '370200000000', '即墨区', '370215000000', '2', null, null);
INSERT INTO `areas` VALUES ('677', '370200000000', '青岛高新技术产业开发区', '370271000000', '2', null, null);
INSERT INTO `areas` VALUES ('678', '370200000000', '胶州市', '370281000000', '2', null, null);
INSERT INTO `areas` VALUES ('679', '370200000000', '平度市', '370283000000', '2', null, null);
INSERT INTO `areas` VALUES ('680', '370200000000', '莱西市', '370285000000', '2', null, null);
INSERT INTO `areas` VALUES ('681', '110100000000', '东城区', '110101000000', '2', null, '2020-07-22 23:54:52');
INSERT INTO `areas` VALUES ('682', '110100000000', '西城区', '110102000000', '2', null, null);
INSERT INTO `areas` VALUES ('683', '110100000000', '朝阳区', '110105000000', '2', null, null);
INSERT INTO `areas` VALUES ('684', '110100000000', '丰台区', '110106000000', '2', null, null);
INSERT INTO `areas` VALUES ('685', '110100000000', '石景山区', '110107000000', '2', null, null);
INSERT INTO `areas` VALUES ('686', '110100000000', '海淀区', '110108000000', '2', null, null);
INSERT INTO `areas` VALUES ('687', '110100000000', '门头沟区', '110109000000', '2', null, null);
INSERT INTO `areas` VALUES ('688', '110100000000', '房山区', '110111000000', '2', null, null);
INSERT INTO `areas` VALUES ('689', '110100000000', '通州区', '110112000000', '2', null, null);
INSERT INTO `areas` VALUES ('690', '110100000000', '顺义区', '110113000000', '2', null, null);
INSERT INTO `areas` VALUES ('691', '110100000000', '昌平区', '110114000000', '2', null, null);
INSERT INTO `areas` VALUES ('692', '110100000000', '大兴区', '110115000000', '2', null, null);
INSERT INTO `areas` VALUES ('693', '110100000000', '怀柔区', '110116000000', '2', null, null);
INSERT INTO `areas` VALUES ('694', '110100000000', '平谷区', '110117000000', '2', null, null);
INSERT INTO `areas` VALUES ('695', '110100000000', '密云区', '110118000000', '2', null, null);
INSERT INTO `areas` VALUES ('696', '110100000000', '延庆区', '110119000000', '2', null, null);
INSERT INTO `areas` VALUES ('697', '140900000000', '市辖区', '140901000000', '2', null, null);
INSERT INTO `areas` VALUES ('698', '140900000000', '忻府区', '140902000000', '2', null, null);
INSERT INTO `areas` VALUES ('699', '140900000000', '定襄县', '140921000000', '2', null, null);
INSERT INTO `areas` VALUES ('700', '140900000000', '五台县', '140922000000', '2', null, null);
INSERT INTO `areas` VALUES ('701', '140900000000', '代县', '140923000000', '2', null, null);
INSERT INTO `areas` VALUES ('702', '140900000000', '繁峙县', '140924000000', '2', null, null);
INSERT INTO `areas` VALUES ('703', '140900000000', '宁武县', '140925000000', '2', null, null);
INSERT INTO `areas` VALUES ('704', '140900000000', '静乐县', '140926000000', '2', null, null);
INSERT INTO `areas` VALUES ('705', '140900000000', '神池县', '140927000000', '2', null, null);
INSERT INTO `areas` VALUES ('706', '140900000000', '五寨县', '140928000000', '2', null, null);
INSERT INTO `areas` VALUES ('707', '140900000000', '岢岚县', '140929000000', '2', null, null);
INSERT INTO `areas` VALUES ('708', '140900000000', '河曲县', '140930000000', '2', null, null);
INSERT INTO `areas` VALUES ('709', '140900000000', '保德县', '140931000000', '2', null, null);
INSERT INTO `areas` VALUES ('710', '140900000000', '偏关县', '140932000000', '2', null, null);
INSERT INTO `areas` VALUES ('711', '140900000000', '五台山风景名胜区', '140971000000', '2', null, null);
INSERT INTO `areas` VALUES ('712', '140900000000', '原平市', '140981000000', '2', null, null);
INSERT INTO `areas` VALUES ('713', '141000000000', '市辖区', '141001000000', '2', null, null);
INSERT INTO `areas` VALUES ('714', '141000000000', '尧都区', '141002000000', '2', null, null);
INSERT INTO `areas` VALUES ('715', '141000000000', '曲沃县', '141021000000', '2', null, null);
INSERT INTO `areas` VALUES ('716', '141000000000', '翼城县', '141022000000', '2', null, null);
INSERT INTO `areas` VALUES ('717', '141000000000', '襄汾县', '141023000000', '2', null, null);
INSERT INTO `areas` VALUES ('718', '141000000000', '洪洞县', '141024000000', '2', null, null);
INSERT INTO `areas` VALUES ('719', '141000000000', '古县', '141025000000', '2', null, null);
INSERT INTO `areas` VALUES ('720', '141000000000', '安泽县', '141026000000', '2', null, null);
INSERT INTO `areas` VALUES ('721', '141000000000', '浮山县', '141027000000', '2', null, null);
INSERT INTO `areas` VALUES ('722', '141000000000', '吉县', '141028000000', '2', null, null);
INSERT INTO `areas` VALUES ('723', '141000000000', '乡宁县', '141029000000', '2', null, null);
INSERT INTO `areas` VALUES ('724', '141000000000', '大宁县', '141030000000', '2', null, null);
INSERT INTO `areas` VALUES ('725', '141000000000', '隰县', '141031000000', '2', null, null);
INSERT INTO `areas` VALUES ('726', '141000000000', '永和县', '141032000000', '2', null, null);
INSERT INTO `areas` VALUES ('727', '141000000000', '蒲县', '141033000000', '2', null, null);
INSERT INTO `areas` VALUES ('728', '141000000000', '汾西县', '141034000000', '2', null, null);
INSERT INTO `areas` VALUES ('729', '141000000000', '侯马市', '141081000000', '2', null, null);
INSERT INTO `areas` VALUES ('730', '141000000000', '霍州市', '141082000000', '2', null, null);
INSERT INTO `areas` VALUES ('731', '150400000000', '市辖区', '150401000000', '2', null, null);
INSERT INTO `areas` VALUES ('732', '150400000000', '红山区', '150402000000', '2', null, null);
INSERT INTO `areas` VALUES ('733', '150400000000', '元宝山区', '150403000000', '2', null, null);
INSERT INTO `areas` VALUES ('734', '150400000000', '松山区', '150404000000', '2', null, null);
INSERT INTO `areas` VALUES ('735', '150400000000', '阿鲁科尔沁旗', '150421000000', '2', null, null);
INSERT INTO `areas` VALUES ('736', '150400000000', '巴林左旗', '150422000000', '2', null, null);
INSERT INTO `areas` VALUES ('737', '150400000000', '巴林右旗', '150423000000', '2', null, null);
INSERT INTO `areas` VALUES ('738', '150400000000', '林西县', '150424000000', '2', null, null);
INSERT INTO `areas` VALUES ('739', '150400000000', '克什克腾旗', '150425000000', '2', null, null);
INSERT INTO `areas` VALUES ('740', '150400000000', '翁牛特旗', '150426000000', '2', null, null);
INSERT INTO `areas` VALUES ('741', '150400000000', '喀喇沁旗', '150428000000', '2', null, null);
INSERT INTO `areas` VALUES ('742', '150400000000', '宁城县', '150429000000', '2', null, null);
INSERT INTO `areas` VALUES ('743', '150400000000', '敖汉旗', '150430000000', '2', null, null);
INSERT INTO `areas` VALUES ('744', '540200000000', '桑珠孜区', '540202000000', '2', null, null);
INSERT INTO `areas` VALUES ('745', '540200000000', '南木林县', '540221000000', '2', null, null);
INSERT INTO `areas` VALUES ('746', '540200000000', '江孜县', '540222000000', '2', null, null);
INSERT INTO `areas` VALUES ('747', '540200000000', '定日县', '540223000000', '2', null, null);
INSERT INTO `areas` VALUES ('748', '540200000000', '萨迦县', '540224000000', '2', null, null);
INSERT INTO `areas` VALUES ('749', '540200000000', '拉孜县', '540225000000', '2', null, null);
INSERT INTO `areas` VALUES ('750', '540200000000', '昂仁县', '540226000000', '2', null, null);
INSERT INTO `areas` VALUES ('751', '540200000000', '谢通门县', '540227000000', '2', null, null);
INSERT INTO `areas` VALUES ('752', '540200000000', '白朗县', '540228000000', '2', null, null);
INSERT INTO `areas` VALUES ('753', '540200000000', '仁布县', '540229000000', '2', null, null);
INSERT INTO `areas` VALUES ('754', '540200000000', '康马县', '540230000000', '2', null, null);
INSERT INTO `areas` VALUES ('755', '540200000000', '定结县', '540231000000', '2', null, null);
INSERT INTO `areas` VALUES ('756', '540200000000', '仲巴县', '540232000000', '2', null, null);
INSERT INTO `areas` VALUES ('757', '540200000000', '亚东县', '540233000000', '2', null, null);
INSERT INTO `areas` VALUES ('758', '540200000000', '吉隆县', '540234000000', '2', null, null);
INSERT INTO `areas` VALUES ('759', '540200000000', '聂拉木县', '540235000000', '2', null, null);
INSERT INTO `areas` VALUES ('760', '540200000000', '萨嘎县', '540236000000', '2', null, null);
INSERT INTO `areas` VALUES ('761', '540200000000', '岗巴县', '540237000000', '2', null, null);
INSERT INTO `areas` VALUES ('762', '540500000000', '市辖区', '540501000000', '2', null, null);
INSERT INTO `areas` VALUES ('763', '540500000000', '乃东区', '540502000000', '2', null, null);
INSERT INTO `areas` VALUES ('764', '540500000000', '扎囊县', '540521000000', '2', null, null);
INSERT INTO `areas` VALUES ('765', '540500000000', '贡嘎县', '540522000000', '2', null, null);
INSERT INTO `areas` VALUES ('766', '540500000000', '桑日县', '540523000000', '2', null, null);
INSERT INTO `areas` VALUES ('767', '540500000000', '琼结县', '540524000000', '2', null, null);
INSERT INTO `areas` VALUES ('768', '540500000000', '曲松县', '540525000000', '2', null, null);
INSERT INTO `areas` VALUES ('769', '540500000000', '措美县', '540526000000', '2', null, null);
INSERT INTO `areas` VALUES ('770', '540500000000', '洛扎县', '540527000000', '2', null, null);
INSERT INTO `areas` VALUES ('771', '540500000000', '加查县', '540528000000', '2', null, null);
INSERT INTO `areas` VALUES ('772', '540500000000', '隆子县', '540529000000', '2', null, null);
INSERT INTO `areas` VALUES ('773', '540500000000', '错那县', '540530000000', '2', null, null);
INSERT INTO `areas` VALUES ('774', '540500000000', '浪卡子县', '540531000000', '2', null, null);
INSERT INTO `areas` VALUES ('775', '620300000000', '市辖区', '620301000000', '2', null, null);
INSERT INTO `areas` VALUES ('776', '620300000000', '金川区', '620302000000', '2', null, null);
INSERT INTO `areas` VALUES ('777', '620300000000', '永昌县', '620321000000', '2', null, null);
INSERT INTO `areas` VALUES ('778', '620900000000', '市辖区', '620901000000', '2', null, null);
INSERT INTO `areas` VALUES ('779', '620900000000', '肃州区', '620902000000', '2', null, null);
INSERT INTO `areas` VALUES ('780', '620900000000', '金塔县', '620921000000', '2', null, null);
INSERT INTO `areas` VALUES ('781', '620900000000', '瓜州县', '620922000000', '2', null, null);
INSERT INTO `areas` VALUES ('782', '620900000000', '肃北蒙古族自治县', '620923000000', '2', null, null);
INSERT INTO `areas` VALUES ('783', '620900000000', '阿克塞哈萨克族自治县', '620924000000', '2', null, null);
INSERT INTO `areas` VALUES ('784', '620900000000', '玉门市', '620981000000', '2', null, null);
INSERT INTO `areas` VALUES ('785', '620900000000', '敦煌市', '620982000000', '2', null, null);
INSERT INTO `areas` VALUES ('786', '621100000000', '市辖区', '621101000000', '2', null, null);
INSERT INTO `areas` VALUES ('787', '621100000000', '安定区', '621102000000', '2', null, null);
INSERT INTO `areas` VALUES ('788', '621100000000', '通渭县', '621121000000', '2', null, null);
INSERT INTO `areas` VALUES ('789', '621100000000', '陇西县', '621122000000', '2', null, null);
INSERT INTO `areas` VALUES ('790', '621100000000', '渭源县', '621123000000', '2', null, null);
INSERT INTO `areas` VALUES ('791', '621100000000', '临洮县', '621124000000', '2', null, null);
INSERT INTO `areas` VALUES ('792', '621100000000', '漳县', '621125000000', '2', null, null);
INSERT INTO `areas` VALUES ('793', '621100000000', '岷县', '621126000000', '2', null, null);
INSERT INTO `areas` VALUES ('794', '621200000000', '市辖区', '621201000000', '2', null, null);
INSERT INTO `areas` VALUES ('795', '621200000000', '武都区', '621202000000', '2', null, null);
INSERT INTO `areas` VALUES ('796', '621200000000', '成县', '621221000000', '2', null, null);
INSERT INTO `areas` VALUES ('797', '621200000000', '文县', '621222000000', '2', null, null);
INSERT INTO `areas` VALUES ('798', '621200000000', '宕昌县', '621223000000', '2', null, null);
INSERT INTO `areas` VALUES ('799', '621200000000', '康县', '621224000000', '2', null, null);
INSERT INTO `areas` VALUES ('800', '621200000000', '西和县', '621225000000', '2', null, null);
INSERT INTO `areas` VALUES ('801', '621200000000', '礼县', '621226000000', '2', null, null);
INSERT INTO `areas` VALUES ('802', '621200000000', '徽县', '621227000000', '2', null, null);
INSERT INTO `areas` VALUES ('803', '621200000000', '两当县', '621228000000', '2', null, null);
INSERT INTO `areas` VALUES ('804', '622900000000', '临夏市', '622901000000', '2', null, null);
INSERT INTO `areas` VALUES ('805', '622900000000', '临夏县', '622921000000', '2', null, null);
INSERT INTO `areas` VALUES ('806', '622900000000', '康乐县', '622922000000', '2', null, null);
INSERT INTO `areas` VALUES ('807', '622900000000', '永靖县', '622923000000', '2', null, null);
INSERT INTO `areas` VALUES ('808', '622900000000', '广河县', '622924000000', '2', null, null);
INSERT INTO `areas` VALUES ('809', '622900000000', '和政县', '622925000000', '2', null, null);
INSERT INTO `areas` VALUES ('810', '622900000000', '东乡族自治县', '622926000000', '2', null, null);
INSERT INTO `areas` VALUES ('811', '622900000000', '积石山保安族东乡族撒拉族自治县', '622927000000', '2', null, null);
INSERT INTO `areas` VALUES ('812', '623000000000', '合作市', '623001000000', '2', null, null);
INSERT INTO `areas` VALUES ('813', '623000000000', '临潭县', '623021000000', '2', null, null);
INSERT INTO `areas` VALUES ('814', '623000000000', '卓尼县', '623022000000', '2', null, null);
INSERT INTO `areas` VALUES ('815', '623000000000', '舟曲县', '623023000000', '2', null, null);
INSERT INTO `areas` VALUES ('816', '623000000000', '迭部县', '623024000000', '2', null, null);
INSERT INTO `areas` VALUES ('817', '623000000000', '玛曲县', '623025000000', '2', null, null);
INSERT INTO `areas` VALUES ('818', '623000000000', '碌曲县', '623026000000', '2', null, null);
INSERT INTO `areas` VALUES ('819', '623000000000', '夏河县', '623027000000', '2', null, null);
INSERT INTO `areas` VALUES ('820', '130700000000', '市辖区', '130701000000', '2', null, null);
INSERT INTO `areas` VALUES ('821', '130700000000', '桥东区', '130702000000', '2', null, null);
INSERT INTO `areas` VALUES ('822', '130700000000', '桥西区', '130703000000', '2', null, null);
INSERT INTO `areas` VALUES ('823', '130700000000', '宣化区', '130705000000', '2', null, null);
INSERT INTO `areas` VALUES ('824', '130700000000', '下花园区', '130706000000', '2', null, null);
INSERT INTO `areas` VALUES ('825', '130700000000', '万全区', '130708000000', '2', null, null);
INSERT INTO `areas` VALUES ('826', '130700000000', '崇礼区', '130709000000', '2', null, null);
INSERT INTO `areas` VALUES ('827', '130700000000', '张北县', '130722000000', '2', null, null);
INSERT INTO `areas` VALUES ('828', '130700000000', '康保县', '130723000000', '2', null, null);
INSERT INTO `areas` VALUES ('829', '130700000000', '沽源县', '130724000000', '2', null, null);
INSERT INTO `areas` VALUES ('830', '130700000000', '尚义县', '130725000000', '2', null, null);
INSERT INTO `areas` VALUES ('831', '130700000000', '蔚县', '130726000000', '2', null, null);
INSERT INTO `areas` VALUES ('832', '130700000000', '阳原县', '130727000000', '2', null, null);
INSERT INTO `areas` VALUES ('833', '130700000000', '怀安县', '130728000000', '2', null, null);
INSERT INTO `areas` VALUES ('834', '130700000000', '怀来县', '130730000000', '2', null, null);
INSERT INTO `areas` VALUES ('835', '130700000000', '涿鹿县', '130731000000', '2', null, null);
INSERT INTO `areas` VALUES ('836', '130700000000', '赤城县', '130732000000', '2', null, null);
INSERT INTO `areas` VALUES ('837', '130700000000', '张家口经济开发区', '130771000000', '2', null, null);
INSERT INTO `areas` VALUES ('838', '130700000000', '张家口市察北管理区', '130772000000', '2', null, null);
INSERT INTO `areas` VALUES ('839', '130700000000', '张家口市塞北管理区', '130773000000', '2', null, null);
INSERT INTO `areas` VALUES ('840', '130800000000', '市辖区', '130801000000', '2', null, null);
INSERT INTO `areas` VALUES ('841', '130800000000', '双桥区', '130802000000', '2', null, null);
INSERT INTO `areas` VALUES ('842', '130800000000', '双滦区', '130803000000', '2', null, null);
INSERT INTO `areas` VALUES ('843', '130800000000', '鹰手营子矿区', '130804000000', '2', null, null);
INSERT INTO `areas` VALUES ('844', '130800000000', '承德县', '130821000000', '2', null, null);
INSERT INTO `areas` VALUES ('845', '130800000000', '兴隆县', '130822000000', '2', null, null);
INSERT INTO `areas` VALUES ('846', '130800000000', '滦平县', '130824000000', '2', null, null);
INSERT INTO `areas` VALUES ('847', '130800000000', '隆化县', '130825000000', '2', null, null);
INSERT INTO `areas` VALUES ('848', '130800000000', '丰宁满族自治县', '130826000000', '2', null, null);
INSERT INTO `areas` VALUES ('849', '130800000000', '宽城满族自治县', '130827000000', '2', null, null);
INSERT INTO `areas` VALUES ('850', '130800000000', '围场满族蒙古族自治县', '130828000000', '2', null, null);
INSERT INTO `areas` VALUES ('851', '130800000000', '承德高新技术产业开发区', '130871000000', '2', null, null);
INSERT INTO `areas` VALUES ('852', '130800000000', '平泉市', '130881000000', '2', null, null);
INSERT INTO `areas` VALUES ('853', '130900000000', '市辖区', '130901000000', '2', null, null);
INSERT INTO `areas` VALUES ('854', '130900000000', '新华区', '130902000000', '2', null, null);
INSERT INTO `areas` VALUES ('855', '130900000000', '运河区', '130903000000', '2', null, null);
INSERT INTO `areas` VALUES ('856', '130900000000', '沧县', '130921000000', '2', null, null);
INSERT INTO `areas` VALUES ('857', '130900000000', '青县', '130922000000', '2', null, null);
INSERT INTO `areas` VALUES ('858', '130900000000', '东光县', '130923000000', '2', null, null);
INSERT INTO `areas` VALUES ('859', '130900000000', '海兴县', '130924000000', '2', null, null);
INSERT INTO `areas` VALUES ('860', '130900000000', '盐山县', '130925000000', '2', null, null);
INSERT INTO `areas` VALUES ('861', '130900000000', '肃宁县', '130926000000', '2', null, null);
INSERT INTO `areas` VALUES ('862', '130900000000', '南皮县', '130927000000', '2', null, null);
INSERT INTO `areas` VALUES ('863', '130900000000', '吴桥县', '130928000000', '2', null, null);
INSERT INTO `areas` VALUES ('864', '130900000000', '献县', '130929000000', '2', null, null);
INSERT INTO `areas` VALUES ('865', '130900000000', '孟村回族自治县', '130930000000', '2', null, null);
INSERT INTO `areas` VALUES ('866', '130900000000', '河北沧州经济开发区', '130971000000', '2', null, null);
INSERT INTO `areas` VALUES ('867', '130900000000', '沧州高新技术产业开发区', '130972000000', '2', null, null);
INSERT INTO `areas` VALUES ('868', '130900000000', '沧州渤海新区', '130973000000', '2', null, null);
INSERT INTO `areas` VALUES ('869', '130900000000', '泊头市', '130981000000', '2', null, null);
INSERT INTO `areas` VALUES ('870', '130900000000', '任丘市', '130982000000', '2', null, null);
INSERT INTO `areas` VALUES ('871', '130900000000', '黄骅市', '130983000000', '2', null, null);
INSERT INTO `areas` VALUES ('872', '130900000000', '河间市', '130984000000', '2', null, null);
INSERT INTO `areas` VALUES ('873', '370300000000', '市辖区', '370301000000', '2', null, null);
INSERT INTO `areas` VALUES ('874', '370300000000', '淄川区', '370302000000', '2', null, null);
INSERT INTO `areas` VALUES ('875', '370300000000', '张店区', '370303000000', '2', null, null);
INSERT INTO `areas` VALUES ('876', '370300000000', '博山区', '370304000000', '2', null, null);
INSERT INTO `areas` VALUES ('877', '370300000000', '临淄区', '370305000000', '2', null, null);
INSERT INTO `areas` VALUES ('878', '370300000000', '周村区', '370306000000', '2', null, null);
INSERT INTO `areas` VALUES ('879', '370300000000', '桓台县', '370321000000', '2', null, null);
INSERT INTO `areas` VALUES ('880', '370300000000', '高青县', '370322000000', '2', null, null);
INSERT INTO `areas` VALUES ('881', '370300000000', '沂源县', '370323000000', '2', null, null);
INSERT INTO `areas` VALUES ('882', '370700000000', '市辖区', '370701000000', '2', null, null);
INSERT INTO `areas` VALUES ('883', '370700000000', '潍城区', '370702000000', '2', null, null);
INSERT INTO `areas` VALUES ('884', '370700000000', '寒亭区', '370703000000', '2', null, null);
INSERT INTO `areas` VALUES ('885', '370700000000', '坊子区', '370704000000', '2', null, null);
INSERT INTO `areas` VALUES ('886', '370700000000', '奎文区', '370705000000', '2', null, null);
INSERT INTO `areas` VALUES ('887', '370700000000', '临朐县', '370724000000', '2', null, null);
INSERT INTO `areas` VALUES ('888', '370700000000', '昌乐县', '370725000000', '2', null, null);
INSERT INTO `areas` VALUES ('889', '370700000000', '潍坊滨海经济技术开发区', '370772000000', '2', null, null);
INSERT INTO `areas` VALUES ('890', '370700000000', '青州市', '370781000000', '2', null, null);
INSERT INTO `areas` VALUES ('891', '370700000000', '诸城市', '370782000000', '2', null, null);
INSERT INTO `areas` VALUES ('892', '370700000000', '寿光市', '370783000000', '2', null, null);
INSERT INTO `areas` VALUES ('893', '370700000000', '安丘市', '370784000000', '2', null, null);
INSERT INTO `areas` VALUES ('894', '370700000000', '高密市', '370785000000', '2', null, null);
INSERT INTO `areas` VALUES ('895', '370700000000', '昌邑市', '370786000000', '2', null, null);
INSERT INTO `areas` VALUES ('896', '370900000000', '市辖区', '370901000000', '2', null, null);
INSERT INTO `areas` VALUES ('897', '370900000000', '泰山区', '370902000000', '2', null, null);
INSERT INTO `areas` VALUES ('898', '370900000000', '岱岳区', '370911000000', '2', null, null);
INSERT INTO `areas` VALUES ('899', '370900000000', '宁阳县', '370921000000', '2', null, null);
INSERT INTO `areas` VALUES ('900', '370900000000', '东平县', '370923000000', '2', null, null);
INSERT INTO `areas` VALUES ('901', '370900000000', '新泰市', '370982000000', '2', null, null);
INSERT INTO `areas` VALUES ('902', '370900000000', '肥城市', '370983000000', '2', null, null);
INSERT INTO `areas` VALUES ('903', '371100000000', '市辖区', '371101000000', '2', null, null);
INSERT INTO `areas` VALUES ('904', '371100000000', '东港区', '371102000000', '2', null, null);
INSERT INTO `areas` VALUES ('905', '371100000000', '岚山区', '371103000000', '2', null, null);
INSERT INTO `areas` VALUES ('906', '371100000000', '五莲县', '371121000000', '2', null, null);
INSERT INTO `areas` VALUES ('907', '371100000000', '莒县', '371122000000', '2', null, null);
INSERT INTO `areas` VALUES ('908', '371100000000', '日照经济技术开发区', '371171000000', '2', null, null);
INSERT INTO `areas` VALUES ('909', '371700000000', '市辖区', '371701000000', '2', null, null);
INSERT INTO `areas` VALUES ('910', '371700000000', '牡丹区', '371702000000', '2', null, null);
INSERT INTO `areas` VALUES ('911', '371700000000', '定陶区', '371703000000', '2', null, null);
INSERT INTO `areas` VALUES ('912', '371700000000', '曹县', '371721000000', '2', null, null);
INSERT INTO `areas` VALUES ('913', '371700000000', '单县', '371722000000', '2', null, null);
INSERT INTO `areas` VALUES ('914', '371700000000', '成武县', '371723000000', '2', null, null);
INSERT INTO `areas` VALUES ('915', '371700000000', '巨野县', '371724000000', '2', null, null);
INSERT INTO `areas` VALUES ('916', '371700000000', '郓城县', '371725000000', '2', null, null);
INSERT INTO `areas` VALUES ('917', '371700000000', '鄄城县', '371726000000', '2', null, null);
INSERT INTO `areas` VALUES ('918', '371700000000', '东明县', '371728000000', '2', null, null);
INSERT INTO `areas` VALUES ('919', '371700000000', '菏泽经济技术开发区', '371771000000', '2', null, null);
INSERT INTO `areas` VALUES ('920', '371700000000', '菏泽高新技术开发区', '371772000000', '2', null, null);
INSERT INTO `areas` VALUES ('921', '210200000000', '市辖区', '210201000000', '2', null, null);
INSERT INTO `areas` VALUES ('922', '210200000000', '中山区', '210202000000', '2', null, null);
INSERT INTO `areas` VALUES ('923', '210200000000', '西岗区', '210203000000', '2', null, null);
INSERT INTO `areas` VALUES ('924', '210200000000', '沙河口区', '210204000000', '2', null, null);
INSERT INTO `areas` VALUES ('925', '210200000000', '甘井子区', '210211000000', '2', null, null);
INSERT INTO `areas` VALUES ('926', '210200000000', '旅顺口区', '210212000000', '2', null, null);
INSERT INTO `areas` VALUES ('927', '210200000000', '金州区', '210213000000', '2', null, null);
INSERT INTO `areas` VALUES ('928', '210200000000', '普兰店区', '210214000000', '2', null, null);
INSERT INTO `areas` VALUES ('929', '210200000000', '长海县', '210224000000', '2', null, null);
INSERT INTO `areas` VALUES ('930', '210200000000', '瓦房店市', '210281000000', '2', null, null);
INSERT INTO `areas` VALUES ('931', '210200000000', '庄河市', '210283000000', '2', null, null);
INSERT INTO `areas` VALUES ('932', '210600000000', '市辖区', '210601000000', '2', null, null);
INSERT INTO `areas` VALUES ('933', '210600000000', '元宝区', '210602000000', '2', null, null);
INSERT INTO `areas` VALUES ('934', '210600000000', '振兴区', '210603000000', '2', null, null);
INSERT INTO `areas` VALUES ('935', '210600000000', '振安区', '210604000000', '2', null, null);
INSERT INTO `areas` VALUES ('936', '210600000000', '宽甸满族自治县', '210624000000', '2', null, null);
INSERT INTO `areas` VALUES ('937', '210600000000', '东港市', '210681000000', '2', null, null);
INSERT INTO `areas` VALUES ('938', '210600000000', '凤城市', '210682000000', '2', null, null);
INSERT INTO `areas` VALUES ('939', '150600000000', '市辖区', '150601000000', '2', null, null);
INSERT INTO `areas` VALUES ('940', '150600000000', '东胜区', '150602000000', '2', null, null);
INSERT INTO `areas` VALUES ('941', '150600000000', '康巴什区', '150603000000', '2', null, null);
INSERT INTO `areas` VALUES ('942', '150600000000', '达拉特旗', '150621000000', '2', null, null);
INSERT INTO `areas` VALUES ('943', '150600000000', '准格尔旗', '150622000000', '2', null, null);
INSERT INTO `areas` VALUES ('944', '150600000000', '鄂托克前旗', '150623000000', '2', null, null);
INSERT INTO `areas` VALUES ('945', '150600000000', '鄂托克旗', '150624000000', '2', null, null);
INSERT INTO `areas` VALUES ('946', '150600000000', '杭锦旗', '150625000000', '2', null, null);
INSERT INTO `areas` VALUES ('947', '150600000000', '乌审旗', '150626000000', '2', null, null);
INSERT INTO `areas` VALUES ('948', '150600000000', '伊金霍洛旗', '150627000000', '2', null, null);
INSERT INTO `areas` VALUES ('949', '540100000000', '市辖区', '540101000000', '2', null, null);
INSERT INTO `areas` VALUES ('950', '540100000000', '城关区', '540102000000', '2', null, null);
INSERT INTO `areas` VALUES ('951', '540100000000', '堆龙德庆区', '540103000000', '2', null, null);
INSERT INTO `areas` VALUES ('952', '540100000000', '达孜区', '540104000000', '2', null, null);
INSERT INTO `areas` VALUES ('953', '540100000000', '林周县', '540121000000', '2', null, null);
INSERT INTO `areas` VALUES ('954', '540100000000', '当雄县', '540122000000', '2', null, null);
INSERT INTO `areas` VALUES ('955', '540100000000', '尼木县', '540123000000', '2', null, null);
INSERT INTO `areas` VALUES ('956', '540100000000', '曲水县', '540124000000', '2', null, null);
INSERT INTO `areas` VALUES ('957', '540100000000', '墨竹工卡县', '540127000000', '2', null, null);
INSERT INTO `areas` VALUES ('958', '540100000000', '格尔木藏青工业园区', '540171000000', '2', null, null);
INSERT INTO `areas` VALUES ('959', '540100000000', '拉萨经济技术开发区', '540172000000', '2', null, null);
INSERT INTO `areas` VALUES ('960', '540100000000', '西藏文化旅游创意园区', '540173000000', '2', null, null);
INSERT INTO `areas` VALUES ('961', '540100000000', '达孜工业园区', '540174000000', '2', null, null);
INSERT INTO `areas` VALUES ('962', '540600000000', '色尼区', '540602000000', '2', null, null);
INSERT INTO `areas` VALUES ('963', '540600000000', '嘉黎县', '540621000000', '2', null, null);
INSERT INTO `areas` VALUES ('964', '540600000000', '比如县', '540622000000', '2', null, null);
INSERT INTO `areas` VALUES ('965', '540600000000', '聂荣县', '540623000000', '2', null, null);
INSERT INTO `areas` VALUES ('966', '540600000000', '安多县', '540624000000', '2', null, null);
INSERT INTO `areas` VALUES ('967', '540600000000', '申扎县', '540625000000', '2', null, null);
INSERT INTO `areas` VALUES ('968', '540600000000', '索县', '540626000000', '2', null, null);
INSERT INTO `areas` VALUES ('969', '540600000000', '班戈县', '540627000000', '2', null, null);
INSERT INTO `areas` VALUES ('970', '540600000000', '巴青县', '540628000000', '2', null, null);
INSERT INTO `areas` VALUES ('971', '540600000000', '尼玛县', '540629000000', '2', null, null);
INSERT INTO `areas` VALUES ('972', '540600000000', '双湖县', '540630000000', '2', null, null);
INSERT INTO `areas` VALUES ('973', '130100000000', '市辖区', '130101000000', '2', null, null);
INSERT INTO `areas` VALUES ('974', '130100000000', '长安区', '130102000000', '2', null, null);
INSERT INTO `areas` VALUES ('975', '130100000000', '桥西区', '130104000000', '2', null, null);
INSERT INTO `areas` VALUES ('976', '130100000000', '新华区', '130105000000', '2', null, null);
INSERT INTO `areas` VALUES ('977', '130100000000', '井陉矿区', '130107000000', '2', null, null);
INSERT INTO `areas` VALUES ('978', '130100000000', '裕华区', '130108000000', '2', null, null);
INSERT INTO `areas` VALUES ('979', '130100000000', '藁城区', '130109000000', '2', null, null);
INSERT INTO `areas` VALUES ('980', '130100000000', '鹿泉区', '130110000000', '2', null, null);
INSERT INTO `areas` VALUES ('981', '130100000000', '栾城区', '130111000000', '2', null, null);
INSERT INTO `areas` VALUES ('982', '130100000000', '井陉县', '130121000000', '2', null, null);
INSERT INTO `areas` VALUES ('983', '130100000000', '正定县', '130123000000', '2', null, null);
INSERT INTO `areas` VALUES ('984', '130100000000', '行唐县', '130125000000', '2', null, null);
INSERT INTO `areas` VALUES ('985', '130100000000', '灵寿县', '130126000000', '2', null, null);
INSERT INTO `areas` VALUES ('986', '130100000000', '高邑县', '130127000000', '2', null, null);
INSERT INTO `areas` VALUES ('987', '130100000000', '深泽县', '130128000000', '2', null, null);
INSERT INTO `areas` VALUES ('988', '130100000000', '赞皇县', '130129000000', '2', null, null);
INSERT INTO `areas` VALUES ('989', '130100000000', '无极县', '130130000000', '2', null, null);
INSERT INTO `areas` VALUES ('990', '130100000000', '平山县', '130131000000', '2', null, null);
INSERT INTO `areas` VALUES ('991', '130100000000', '元氏县', '130132000000', '2', null, null);
INSERT INTO `areas` VALUES ('992', '130100000000', '赵县', '130133000000', '2', null, null);
INSERT INTO `areas` VALUES ('993', '130100000000', '石家庄高新技术产业开发区', '130171000000', '2', null, null);
INSERT INTO `areas` VALUES ('994', '130100000000', '石家庄循环化工园区', '130172000000', '2', null, null);
INSERT INTO `areas` VALUES ('995', '130100000000', '辛集市', '130181000000', '2', null, null);
INSERT INTO `areas` VALUES ('996', '130100000000', '晋州市', '130183000000', '2', null, null);
INSERT INTO `areas` VALUES ('997', '130100000000', '新乐市', '130184000000', '2', null, null);
INSERT INTO `areas` VALUES ('998', '130300000000', '市辖区', '130301000000', '2', null, null);
INSERT INTO `areas` VALUES ('999', '130300000000', '海港区', '130302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1000', '130300000000', '山海关区', '130303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1001', '130300000000', '北戴河区', '130304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1002', '130300000000', '抚宁区', '130306000000', '2', null, null);
INSERT INTO `areas` VALUES ('1003', '130300000000', '青龙满族自治县', '130321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1004', '130300000000', '昌黎县', '130322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1005', '130300000000', '卢龙县', '130324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1006', '130300000000', '秦皇岛市经济技术开发区', '130371000000', '2', null, null);
INSERT INTO `areas` VALUES ('1007', '130300000000', '北戴河新区', '130372000000', '2', null, null);
INSERT INTO `areas` VALUES ('1008', '130500000000', '市辖区', '130501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1009', '130500000000', '桥东区', '130502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1010', '130500000000', '桥西区', '130503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1011', '130500000000', '邢台县', '130521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1012', '130500000000', '临城县', '130522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1013', '130500000000', '内丘县', '130523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1014', '130500000000', '柏乡县', '130524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1015', '130500000000', '隆尧县', '130525000000', '2', null, null);
INSERT INTO `areas` VALUES ('1016', '130500000000', '任县', '130526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1017', '130500000000', '南和县', '130527000000', '2', null, null);
INSERT INTO `areas` VALUES ('1018', '130500000000', '宁晋县', '130528000000', '2', null, null);
INSERT INTO `areas` VALUES ('1019', '130500000000', '巨鹿县', '130529000000', '2', null, null);
INSERT INTO `areas` VALUES ('1020', '130500000000', '新河县', '130530000000', '2', null, null);
INSERT INTO `areas` VALUES ('1021', '130500000000', '广宗县', '130531000000', '2', null, null);
INSERT INTO `areas` VALUES ('1022', '130500000000', '平乡县', '130532000000', '2', null, null);
INSERT INTO `areas` VALUES ('1023', '130500000000', '威县', '130533000000', '2', null, null);
INSERT INTO `areas` VALUES ('1024', '130500000000', '清河县', '130534000000', '2', null, null);
INSERT INTO `areas` VALUES ('1025', '130500000000', '临西县', '130535000000', '2', null, null);
INSERT INTO `areas` VALUES ('1026', '130500000000', '河北邢台经济开发区', '130571000000', '2', null, null);
INSERT INTO `areas` VALUES ('1027', '130500000000', '南宫市', '130581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1028', '130500000000', '沙河市', '130582000000', '2', null, null);
INSERT INTO `areas` VALUES ('1029', '131100000000', '市辖区', '131101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1030', '131100000000', '桃城区', '131102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1031', '131100000000', '冀州区', '131103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1032', '131100000000', '枣强县', '131121000000', '2', null, null);
INSERT INTO `areas` VALUES ('1033', '131100000000', '武邑县', '131122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1034', '131100000000', '武强县', '131123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1035', '131100000000', '饶阳县', '131124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1036', '131100000000', '安平县', '131125000000', '2', null, null);
INSERT INTO `areas` VALUES ('1037', '131100000000', '故城县', '131126000000', '2', null, null);
INSERT INTO `areas` VALUES ('1038', '131100000000', '景县', '131127000000', '2', null, null);
INSERT INTO `areas` VALUES ('1039', '131100000000', '阜城县', '131128000000', '2', null, null);
INSERT INTO `areas` VALUES ('1040', '131100000000', '河北衡水高新技术产业开发区', '131171000000', '2', null, null);
INSERT INTO `areas` VALUES ('1041', '131100000000', '衡水滨湖新区', '131172000000', '2', null, null);
INSERT INTO `areas` VALUES ('1042', '131100000000', '深州市', '131182000000', '2', null, null);
INSERT INTO `areas` VALUES ('1043', '370100000000', '市辖区', '370101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1044', '370100000000', '历下区', '370102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1045', '370100000000', '市中区', '370103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1046', '370100000000', '槐荫区', '370104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1047', '370100000000', '天桥区', '370105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1048', '370100000000', '历城区', '370112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1049', '370100000000', '长清区', '370113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1050', '370100000000', '章丘区', '370114000000', '2', null, null);
INSERT INTO `areas` VALUES ('1051', '370100000000', '济阳区', '370115000000', '2', null, null);
INSERT INTO `areas` VALUES ('1052', '370100000000', '莱芜区', '370116000000', '2', null, null);
INSERT INTO `areas` VALUES ('1053', '370100000000', '钢城区', '370117000000', '2', null, null);
INSERT INTO `areas` VALUES ('1054', '370100000000', '平阴县', '370124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1055', '370100000000', '商河县', '370126000000', '2', null, null);
INSERT INTO `areas` VALUES ('1056', '370100000000', '济南高新技术产业开发区', '370171000000', '2', null, null);
INSERT INTO `areas` VALUES ('1057', '370400000000', '市辖区', '370401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1058', '370400000000', '市中区', '370402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1059', '370400000000', '薛城区', '370403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1060', '370400000000', '峄城区', '370404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1061', '370400000000', '台儿庄区', '370405000000', '2', null, null);
INSERT INTO `areas` VALUES ('1062', '370400000000', '山亭区', '370406000000', '2', null, null);
INSERT INTO `areas` VALUES ('1063', '370400000000', '滕州市', '370481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1064', '370800000000', '市辖区', '370801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1065', '370800000000', '任城区', '370811000000', '2', null, null);
INSERT INTO `areas` VALUES ('1066', '370800000000', '兖州区', '370812000000', '2', null, null);
INSERT INTO `areas` VALUES ('1067', '370800000000', '微山县', '370826000000', '2', null, null);
INSERT INTO `areas` VALUES ('1068', '370800000000', '鱼台县', '370827000000', '2', null, null);
INSERT INTO `areas` VALUES ('1069', '370800000000', '金乡县', '370828000000', '2', null, null);
INSERT INTO `areas` VALUES ('1070', '370800000000', '嘉祥县', '370829000000', '2', null, null);
INSERT INTO `areas` VALUES ('1071', '370800000000', '汶上县', '370830000000', '2', null, null);
INSERT INTO `areas` VALUES ('1072', '370800000000', '泗水县', '370831000000', '2', null, null);
INSERT INTO `areas` VALUES ('1073', '370800000000', '梁山县', '370832000000', '2', null, null);
INSERT INTO `areas` VALUES ('1074', '370800000000', '济宁高新技术产业开发区', '370871000000', '2', null, null);
INSERT INTO `areas` VALUES ('1075', '370800000000', '曲阜市', '370881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1076', '370800000000', '邹城市', '370883000000', '2', null, null);
INSERT INTO `areas` VALUES ('1077', '371000000000', '市辖区', '371001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1078', '371000000000', '环翠区', '371002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1079', '371000000000', '文登区', '371003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1080', '371000000000', '威海火炬高技术产业开发区', '371071000000', '2', null, null);
INSERT INTO `areas` VALUES ('1081', '371000000000', '威海经济技术开发区', '371072000000', '2', null, null);
INSERT INTO `areas` VALUES ('1082', '371000000000', '威海临港经济技术开发区', '371073000000', '2', null, null);
INSERT INTO `areas` VALUES ('1083', '371000000000', '荣成市', '371082000000', '2', null, null);
INSERT INTO `areas` VALUES ('1084', '371000000000', '乳山市', '371083000000', '2', null, null);
INSERT INTO `areas` VALUES ('1085', '371300000000', '市辖区', '371301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1086', '371300000000', '兰山区', '371302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1087', '371300000000', '罗庄区', '371311000000', '2', null, null);
INSERT INTO `areas` VALUES ('1088', '371300000000', '河东区', '371312000000', '2', null, null);
INSERT INTO `areas` VALUES ('1089', '371300000000', '沂南县', '371321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1090', '371300000000', '郯城县', '371322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1091', '371300000000', '沂水县', '371323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1092', '371300000000', '兰陵县', '371324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1093', '371300000000', '费县', '371325000000', '2', null, null);
INSERT INTO `areas` VALUES ('1094', '371300000000', '平邑县', '371326000000', '2', null, null);
INSERT INTO `areas` VALUES ('1095', '371300000000', '莒南县', '371327000000', '2', null, null);
INSERT INTO `areas` VALUES ('1096', '371300000000', '蒙阴县', '371328000000', '2', null, null);
INSERT INTO `areas` VALUES ('1097', '371300000000', '临沭县', '371329000000', '2', null, null);
INSERT INTO `areas` VALUES ('1098', '371300000000', '临沂高新技术产业开发区', '371371000000', '2', null, null);
INSERT INTO `areas` VALUES ('1099', '371300000000', '临沂经济技术开发区', '371372000000', '2', null, null);
INSERT INTO `areas` VALUES ('1100', '371300000000', '临沂临港经济开发区', '371373000000', '2', null, null);
INSERT INTO `areas` VALUES ('1101', '371500000000', '市辖区', '371501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1102', '371500000000', '东昌府区', '371502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1103', '371500000000', '茌平区', '371503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1104', '371500000000', '阳谷县', '371521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1105', '371500000000', '莘县', '371522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1106', '371500000000', '东阿县', '371524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1107', '371500000000', '冠县', '371525000000', '2', null, null);
INSERT INTO `areas` VALUES ('1108', '371500000000', '高唐县', '371526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1109', '371500000000', '临清市', '371581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1110', '371600000000', '市辖区', '371601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1111', '371600000000', '滨城区', '371602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1112', '371600000000', '沾化区', '371603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1113', '371600000000', '惠民县', '371621000000', '2', null, null);
INSERT INTO `areas` VALUES ('1114', '371600000000', '阳信县', '371622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1115', '371600000000', '无棣县', '371623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1116', '371600000000', '博兴县', '371625000000', '2', null, null);
INSERT INTO `areas` VALUES ('1117', '371600000000', '邹平市', '371681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1118', '210300000000', '市辖区', '210301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1119', '210300000000', '铁东区', '210302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1120', '210300000000', '铁西区', '210303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1121', '210300000000', '立山区', '210304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1122', '210300000000', '千山区', '210311000000', '2', null, null);
INSERT INTO `areas` VALUES ('1123', '210300000000', '台安县', '210321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1124', '210300000000', '岫岩满族自治县', '210323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1125', '210300000000', '海城市', '210381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1126', '130200000000', '市辖区', '130201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1127', '130200000000', '路南区', '130202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1128', '130200000000', '路北区', '130203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1129', '130200000000', '古冶区', '130204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1130', '130200000000', '开平区', '130205000000', '2', null, null);
INSERT INTO `areas` VALUES ('1131', '130200000000', '丰南区', '130207000000', '2', null, null);
INSERT INTO `areas` VALUES ('1132', '130200000000', '丰润区', '130208000000', '2', null, null);
INSERT INTO `areas` VALUES ('1133', '130200000000', '曹妃甸区', '130209000000', '2', null, null);
INSERT INTO `areas` VALUES ('1134', '130200000000', '滦南县', '130224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1135', '130200000000', '乐亭县', '130225000000', '2', null, null);
INSERT INTO `areas` VALUES ('1136', '130200000000', '迁西县', '130227000000', '2', null, null);
INSERT INTO `areas` VALUES ('1137', '130200000000', '玉田县', '130229000000', '2', null, null);
INSERT INTO `areas` VALUES ('1138', '130200000000', '河北唐山芦台经济开发区', '130271000000', '2', null, null);
INSERT INTO `areas` VALUES ('1139', '130200000000', '唐山市汉沽管理区', '130272000000', '2', null, null);
INSERT INTO `areas` VALUES ('1140', '130200000000', '唐山高新技术产业开发区', '130273000000', '2', null, null);
INSERT INTO `areas` VALUES ('1141', '130200000000', '河北唐山海港经济开发区', '130274000000', '2', null, null);
INSERT INTO `areas` VALUES ('1142', '130200000000', '遵化市', '130281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1143', '130200000000', '迁安市', '130283000000', '2', null, null);
INSERT INTO `areas` VALUES ('1144', '130200000000', '滦州市', '130284000000', '2', null, null);
INSERT INTO `areas` VALUES ('1145', '370500000000', '市辖区', '370501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1146', '370500000000', '东营区', '370502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1147', '370500000000', '河口区', '370503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1148', '370500000000', '垦利区', '370505000000', '2', null, null);
INSERT INTO `areas` VALUES ('1149', '370500000000', '利津县', '370522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1150', '370500000000', '广饶县', '370523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1151', '370500000000', '东营经济技术开发区', '370571000000', '2', null, null);
INSERT INTO `areas` VALUES ('1152', '370500000000', '东营港经济开发区', '370572000000', '2', null, null);
INSERT INTO `areas` VALUES ('1153', '370600000000', '市辖区', '370601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1154', '370600000000', '芝罘区', '370602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1155', '370600000000', '福山区', '370611000000', '2', null, null);
INSERT INTO `areas` VALUES ('1156', '370600000000', '牟平区', '370612000000', '2', null, null);
INSERT INTO `areas` VALUES ('1157', '370600000000', '莱山区', '370613000000', '2', null, null);
INSERT INTO `areas` VALUES ('1158', '370600000000', '长岛县', '370634000000', '2', null, null);
INSERT INTO `areas` VALUES ('1159', '370600000000', '烟台高新技术产业开发区', '370671000000', '2', null, null);
INSERT INTO `areas` VALUES ('1160', '370600000000', '烟台经济技术开发区', '370672000000', '2', null, null);
INSERT INTO `areas` VALUES ('1161', '370600000000', '龙口市', '370681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1162', '370600000000', '莱阳市', '370682000000', '2', null, null);
INSERT INTO `areas` VALUES ('1163', '370600000000', '莱州市', '370683000000', '2', null, null);
INSERT INTO `areas` VALUES ('1164', '370600000000', '蓬莱市', '370684000000', '2', null, null);
INSERT INTO `areas` VALUES ('1165', '370600000000', '招远市', '370685000000', '2', null, null);
INSERT INTO `areas` VALUES ('1166', '370600000000', '栖霞市', '370686000000', '2', null, null);
INSERT INTO `areas` VALUES ('1167', '370600000000', '海阳市', '370687000000', '2', null, null);
INSERT INTO `areas` VALUES ('1168', '371400000000', '市辖区', '371401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1169', '371400000000', '德城区', '371402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1170', '371400000000', '陵城区', '371403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1171', '371400000000', '宁津县', '371422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1172', '371400000000', '庆云县', '371423000000', '2', null, null);
INSERT INTO `areas` VALUES ('1173', '371400000000', '临邑县', '371424000000', '2', null, null);
INSERT INTO `areas` VALUES ('1174', '371400000000', '齐河县', '371425000000', '2', null, null);
INSERT INTO `areas` VALUES ('1175', '371400000000', '平原县', '371426000000', '2', null, null);
INSERT INTO `areas` VALUES ('1176', '371400000000', '夏津县', '371427000000', '2', null, null);
INSERT INTO `areas` VALUES ('1177', '371400000000', '武城县', '371428000000', '2', null, null);
INSERT INTO `areas` VALUES ('1178', '371400000000', '德州经济技术开发区', '371471000000', '2', null, null);
INSERT INTO `areas` VALUES ('1179', '371400000000', '德州运河经济开发区', '371472000000', '2', null, null);
INSERT INTO `areas` VALUES ('1180', '371400000000', '乐陵市', '371481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1181', '371400000000', '禹城市', '371482000000', '2', null, null);
INSERT INTO `areas` VALUES ('1182', '120100000000', '和平区', '120101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1183', '120100000000', '河东区', '120102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1184', '120100000000', '河西区', '120103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1185', '120100000000', '南开区', '120104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1186', '120100000000', '河北区', '120105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1187', '120100000000', '红桥区', '120106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1188', '120100000000', '东丽区', '120110000000', '2', null, null);
INSERT INTO `areas` VALUES ('1189', '120100000000', '西青区', '120111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1190', '120100000000', '津南区', '120112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1191', '120100000000', '北辰区', '120113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1192', '120100000000', '武清区', '120114000000', '2', null, null);
INSERT INTO `areas` VALUES ('1193', '120100000000', '宝坻区', '120115000000', '2', null, null);
INSERT INTO `areas` VALUES ('1194', '120100000000', '滨海新区', '120116000000', '2', null, null);
INSERT INTO `areas` VALUES ('1195', '120100000000', '宁河区', '120117000000', '2', null, null);
INSERT INTO `areas` VALUES ('1196', '120100000000', '静海区', '120118000000', '2', null, null);
INSERT INTO `areas` VALUES ('1197', '120100000000', '蓟州区', '120119000000', '2', null, null);
INSERT INTO `areas` VALUES ('1198', '210100000000', '市辖区', '210101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1199', '210100000000', '和平区', '210102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1200', '210100000000', '沈河区', '210103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1201', '210100000000', '大东区', '210104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1202', '210100000000', '皇姑区', '210105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1203', '210100000000', '铁西区', '210106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1204', '210100000000', '苏家屯区', '210111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1205', '210100000000', '浑南区', '210112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1206', '210100000000', '沈北新区', '210113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1207', '210100000000', '于洪区', '210114000000', '2', null, null);
INSERT INTO `areas` VALUES ('1208', '210100000000', '辽中区', '210115000000', '2', null, null);
INSERT INTO `areas` VALUES ('1209', '210100000000', '康平县', '210123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1210', '210100000000', '法库县', '210124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1211', '210100000000', '新民市', '210181000000', '2', null, null);
INSERT INTO `areas` VALUES ('1212', '210400000000', '市辖区', '210401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1213', '210400000000', '新抚区', '210402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1214', '210400000000', '东洲区', '210403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1215', '210400000000', '望花区', '210404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1216', '210400000000', '顺城区', '210411000000', '2', null, null);
INSERT INTO `areas` VALUES ('1217', '210400000000', '抚顺县', '210421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1218', '210400000000', '新宾满族自治县', '210422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1219', '210400000000', '清原满族自治县', '210423000000', '2', null, null);
INSERT INTO `areas` VALUES ('1220', '130600000000', '市辖区', '130601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1221', '130600000000', '竞秀区', '130602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1222', '130600000000', '莲池区', '130606000000', '2', null, null);
INSERT INTO `areas` VALUES ('1223', '130600000000', '满城区', '130607000000', '2', null, null);
INSERT INTO `areas` VALUES ('1224', '130600000000', '清苑区', '130608000000', '2', null, null);
INSERT INTO `areas` VALUES ('1225', '130600000000', '徐水区', '130609000000', '2', null, null);
INSERT INTO `areas` VALUES ('1226', '130600000000', '涞水县', '130623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1227', '130600000000', '阜平县', '130624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1228', '130600000000', '定兴县', '130626000000', '2', null, null);
INSERT INTO `areas` VALUES ('1229', '130600000000', '唐县', '130627000000', '2', null, null);
INSERT INTO `areas` VALUES ('1230', '130600000000', '高阳县', '130628000000', '2', null, null);
INSERT INTO `areas` VALUES ('1231', '130600000000', '容城县', '130629000000', '2', null, null);
INSERT INTO `areas` VALUES ('1232', '130600000000', '涞源县', '130630000000', '2', null, null);
INSERT INTO `areas` VALUES ('1233', '130600000000', '望都县', '130631000000', '2', null, null);
INSERT INTO `areas` VALUES ('1234', '130600000000', '安新县', '130632000000', '2', null, null);
INSERT INTO `areas` VALUES ('1235', '130600000000', '易县', '130633000000', '2', null, null);
INSERT INTO `areas` VALUES ('1236', '130600000000', '曲阳县', '130634000000', '2', null, null);
INSERT INTO `areas` VALUES ('1237', '130600000000', '蠡县', '130635000000', '2', null, null);
INSERT INTO `areas` VALUES ('1238', '130600000000', '顺平县', '130636000000', '2', null, null);
INSERT INTO `areas` VALUES ('1239', '130600000000', '博野县', '130637000000', '2', null, null);
INSERT INTO `areas` VALUES ('1240', '130600000000', '雄县', '130638000000', '2', null, null);
INSERT INTO `areas` VALUES ('1241', '130600000000', '保定高新技术产业开发区', '130671000000', '2', null, null);
INSERT INTO `areas` VALUES ('1242', '130600000000', '保定白沟新城', '130672000000', '2', null, null);
INSERT INTO `areas` VALUES ('1243', '130600000000', '涿州市', '130681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1244', '130600000000', '定州市', '130682000000', '2', null, null);
INSERT INTO `areas` VALUES ('1245', '130600000000', '安国市', '130683000000', '2', null, null);
INSERT INTO `areas` VALUES ('1246', '130600000000', '高碑店市', '130684000000', '2', null, null);
INSERT INTO `areas` VALUES ('1247', '210700000000', '市辖区', '210701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1248', '210700000000', '古塔区', '210702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1249', '210700000000', '凌河区', '210703000000', '2', null, null);
INSERT INTO `areas` VALUES ('1250', '210700000000', '太和区', '210711000000', '2', null, null);
INSERT INTO `areas` VALUES ('1251', '210700000000', '黑山县', '210726000000', '2', null, null);
INSERT INTO `areas` VALUES ('1252', '210700000000', '义县', '210727000000', '2', null, null);
INSERT INTO `areas` VALUES ('1253', '210700000000', '凌海市', '210781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1254', '210700000000', '北镇市', '210782000000', '2', null, null);
INSERT INTO `areas` VALUES ('1255', '210800000000', '市辖区', '210801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1256', '210800000000', '站前区', '210802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1257', '210800000000', '西市区', '210803000000', '2', null, null);
INSERT INTO `areas` VALUES ('1258', '210800000000', '鲅鱼圈区', '210804000000', '2', null, null);
INSERT INTO `areas` VALUES ('1259', '210800000000', '老边区', '210811000000', '2', null, null);
INSERT INTO `areas` VALUES ('1260', '210800000000', '盖州市', '210881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1261', '210800000000', '大石桥市', '210882000000', '2', null, null);
INSERT INTO `areas` VALUES ('1262', '210900000000', '市辖区', '210901000000', '2', null, null);
INSERT INTO `areas` VALUES ('1263', '210900000000', '海州区', '210902000000', '2', null, null);
INSERT INTO `areas` VALUES ('1264', '210900000000', '新邱区', '210903000000', '2', null, null);
INSERT INTO `areas` VALUES ('1265', '210900000000', '太平区', '210904000000', '2', null, null);
INSERT INTO `areas` VALUES ('1266', '210900000000', '清河门区', '210905000000', '2', null, null);
INSERT INTO `areas` VALUES ('1267', '210900000000', '细河区', '210911000000', '2', null, null);
INSERT INTO `areas` VALUES ('1268', '210900000000', '阜新蒙古族自治县', '210921000000', '2', null, null);
INSERT INTO `areas` VALUES ('1269', '210900000000', '彰武县', '210922000000', '2', null, null);
INSERT INTO `areas` VALUES ('1270', '211100000000', '市辖区', '211101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1271', '211100000000', '双台子区', '211102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1272', '211100000000', '兴隆台区', '211103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1273', '211100000000', '大洼区', '211104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1274', '211100000000', '盘山县', '211122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1275', '211000000000', '市辖区', '211001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1276', '211000000000', '白塔区', '211002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1277', '211000000000', '文圣区', '211003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1278', '211000000000', '宏伟区', '211004000000', '2', null, null);
INSERT INTO `areas` VALUES ('1279', '211000000000', '弓长岭区', '211005000000', '2', null, null);
INSERT INTO `areas` VALUES ('1280', '211000000000', '太子河区', '211011000000', '2', null, null);
INSERT INTO `areas` VALUES ('1281', '211000000000', '辽阳县', '211021000000', '2', null, null);
INSERT INTO `areas` VALUES ('1282', '211000000000', '灯塔市', '211081000000', '2', null, null);
INSERT INTO `areas` VALUES ('1283', '211200000000', '市辖区', '211201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1284', '211200000000', '银州区', '211202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1285', '211200000000', '清河区', '211204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1286', '211200000000', '铁岭县', '211221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1287', '211200000000', '西丰县', '211223000000', '2', null, null);
INSERT INTO `areas` VALUES ('1288', '211200000000', '昌图县', '211224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1289', '211200000000', '调兵山市', '211281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1290', '211200000000', '开原市', '211282000000', '2', null, null);
INSERT INTO `areas` VALUES ('1291', '220100000000', '市辖区', '220101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1292', '220100000000', '南关区', '220102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1293', '220100000000', '宽城区', '220103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1294', '220100000000', '朝阳区', '220104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1295', '220100000000', '二道区', '220105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1296', '220100000000', '绿园区', '220106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1297', '220100000000', '双阳区', '220112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1298', '220100000000', '九台区', '220113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1299', '220100000000', '农安县', '220122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1300', '220100000000', '长春经济技术开发区', '220171000000', '2', null, null);
INSERT INTO `areas` VALUES ('1301', '220100000000', '长春净月高新技术产业开发区', '220172000000', '2', null, null);
INSERT INTO `areas` VALUES ('1302', '220100000000', '长春高新技术产业开发区', '220173000000', '2', null, null);
INSERT INTO `areas` VALUES ('1303', '220100000000', '长春汽车经济技术开发区', '220174000000', '2', null, null);
INSERT INTO `areas` VALUES ('1304', '220100000000', '榆树市', '220182000000', '2', null, null);
INSERT INTO `areas` VALUES ('1305', '220100000000', '德惠市', '220183000000', '2', null, null);
INSERT INTO `areas` VALUES ('1306', '211300000000', '市辖区', '211301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1307', '211300000000', '双塔区', '211302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1308', '211300000000', '龙城区', '211303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1309', '211300000000', '朝阳县', '211321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1310', '211300000000', '建平县', '211322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1311', '211300000000', '喀喇沁左翼蒙古族自治县', '211324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1312', '211300000000', '北票市', '211381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1313', '211300000000', '凌源市', '211382000000', '2', null, null);
INSERT INTO `areas` VALUES ('1314', '211400000000', '市辖区', '211401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1315', '211400000000', '连山区', '211402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1316', '211400000000', '龙港区', '211403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1317', '211400000000', '南票区', '211404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1318', '211400000000', '绥中县', '211421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1319', '211400000000', '建昌县', '211422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1320', '211400000000', '兴城市', '211481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1321', '220200000000', '市辖区', '220201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1322', '220200000000', '昌邑区', '220202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1323', '220200000000', '龙潭区', '220203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1324', '220200000000', '船营区', '220204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1325', '220200000000', '丰满区', '220211000000', '2', null, null);
INSERT INTO `areas` VALUES ('1326', '220200000000', '永吉县', '220221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1327', '220200000000', '吉林经济开发区', '220271000000', '2', null, null);
INSERT INTO `areas` VALUES ('1328', '220200000000', '吉林高新技术产业开发区', '220272000000', '2', null, null);
INSERT INTO `areas` VALUES ('1329', '220200000000', '吉林中国新加坡食品区', '220273000000', '2', null, null);
INSERT INTO `areas` VALUES ('1330', '220200000000', '蛟河市', '220281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1331', '220200000000', '桦甸市', '220282000000', '2', null, null);
INSERT INTO `areas` VALUES ('1332', '220200000000', '舒兰市', '220283000000', '2', null, null);
INSERT INTO `areas` VALUES ('1333', '220200000000', '磐石市', '220284000000', '2', null, null);
INSERT INTO `areas` VALUES ('1334', '220300000000', '市辖区', '220301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1335', '220300000000', '铁西区', '220302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1336', '220300000000', '铁东区', '220303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1337', '220300000000', '梨树县', '220322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1338', '220300000000', '伊通满族自治县', '220323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1339', '220300000000', '公主岭市', '220381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1340', '220300000000', '双辽市', '220382000000', '2', null, null);
INSERT INTO `areas` VALUES ('1341', '220500000000', '市辖区', '220501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1342', '220500000000', '东昌区', '220502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1343', '220500000000', '二道江区', '220503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1344', '220500000000', '通化县', '220521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1345', '220500000000', '辉南县', '220523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1346', '220500000000', '柳河县', '220524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1347', '220500000000', '梅河口市', '220581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1348', '220500000000', '集安市', '220582000000', '2', null, null);
INSERT INTO `areas` VALUES ('1349', '220700000000', '市辖区', '220701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1350', '220700000000', '宁江区', '220702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1351', '220700000000', '前郭尔罗斯蒙古族自治县', '220721000000', '2', null, null);
INSERT INTO `areas` VALUES ('1352', '220700000000', '长岭县', '220722000000', '2', null, null);
INSERT INTO `areas` VALUES ('1353', '220700000000', '乾安县', '220723000000', '2', null, null);
INSERT INTO `areas` VALUES ('1354', '220700000000', '吉林松原经济开发区', '220771000000', '2', null, null);
INSERT INTO `areas` VALUES ('1355', '220700000000', '扶余市', '220781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1356', '220400000000', '市辖区', '220401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1357', '220400000000', '龙山区', '220402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1358', '220400000000', '西安区', '220403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1359', '220400000000', '东丰县', '220421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1360', '220400000000', '东辽县', '220422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1361', '220600000000', '市辖区', '220601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1362', '220600000000', '浑江区', '220602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1363', '220600000000', '江源区', '220605000000', '2', null, null);
INSERT INTO `areas` VALUES ('1364', '220600000000', '抚松县', '220621000000', '2', null, null);
INSERT INTO `areas` VALUES ('1365', '220600000000', '靖宇县', '220622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1366', '220600000000', '长白朝鲜族自治县', '220623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1367', '220600000000', '临江市', '220681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1368', '220800000000', '市辖区', '220801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1369', '220800000000', '洮北区', '220802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1370', '220800000000', '镇赉县', '220821000000', '2', null, null);
INSERT INTO `areas` VALUES ('1371', '220800000000', '通榆县', '220822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1372', '220800000000', '吉林白城经济开发区', '220871000000', '2', null, null);
INSERT INTO `areas` VALUES ('1373', '220800000000', '洮南市', '220881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1374', '220800000000', '大安市', '220882000000', '2', null, null);
INSERT INTO `areas` VALUES ('1375', '230100000000', '市辖区', '230101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1376', '230100000000', '道里区', '230102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1377', '230100000000', '南岗区', '230103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1378', '230100000000', '道外区', '230104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1379', '230100000000', '平房区', '230108000000', '2', null, null);
INSERT INTO `areas` VALUES ('1380', '230100000000', '松北区', '230109000000', '2', null, null);
INSERT INTO `areas` VALUES ('1381', '230100000000', '香坊区', '230110000000', '2', null, null);
INSERT INTO `areas` VALUES ('1382', '230100000000', '呼兰区', '230111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1383', '230100000000', '阿城区', '230112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1384', '230100000000', '双城区', '230113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1385', '230100000000', '依兰县', '230123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1386', '230100000000', '方正县', '230124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1387', '230100000000', '宾县', '230125000000', '2', null, null);
INSERT INTO `areas` VALUES ('1388', '230100000000', '巴彦县', '230126000000', '2', null, null);
INSERT INTO `areas` VALUES ('1389', '230100000000', '木兰县', '230127000000', '2', null, null);
INSERT INTO `areas` VALUES ('1390', '230100000000', '通河县', '230128000000', '2', null, null);
INSERT INTO `areas` VALUES ('1391', '230100000000', '延寿县', '230129000000', '2', null, null);
INSERT INTO `areas` VALUES ('1392', '230100000000', '尚志市', '230183000000', '2', null, null);
INSERT INTO `areas` VALUES ('1393', '230100000000', '五常市', '230184000000', '2', null, null);
INSERT INTO `areas` VALUES ('1394', '222400000000', '延吉市', '222401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1395', '222400000000', '图们市', '222402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1396', '222400000000', '敦化市', '222403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1397', '222400000000', '珲春市', '222404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1398', '222400000000', '龙井市', '222405000000', '2', null, null);
INSERT INTO `areas` VALUES ('1399', '222400000000', '和龙市', '222406000000', '2', null, null);
INSERT INTO `areas` VALUES ('1400', '222400000000', '汪清县', '222424000000', '2', null, null);
INSERT INTO `areas` VALUES ('1401', '222400000000', '安图县', '222426000000', '2', null, null);
INSERT INTO `areas` VALUES ('1402', '230200000000', '市辖区', '230201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1403', '230200000000', '龙沙区', '230202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1404', '230200000000', '建华区', '230203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1405', '230200000000', '铁锋区', '230204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1406', '230200000000', '昂昂溪区', '230205000000', '2', null, null);
INSERT INTO `areas` VALUES ('1407', '230200000000', '富拉尔基区', '230206000000', '2', null, null);
INSERT INTO `areas` VALUES ('1408', '230200000000', '碾子山区', '230207000000', '2', null, null);
INSERT INTO `areas` VALUES ('1409', '230200000000', '梅里斯达斡尔族区', '230208000000', '2', null, null);
INSERT INTO `areas` VALUES ('1410', '230200000000', '龙江县', '230221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1411', '230200000000', '依安县', '230223000000', '2', null, null);
INSERT INTO `areas` VALUES ('1412', '230200000000', '泰来县', '230224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1413', '230200000000', '甘南县', '230225000000', '2', null, null);
INSERT INTO `areas` VALUES ('1414', '230200000000', '富裕县', '230227000000', '2', null, null);
INSERT INTO `areas` VALUES ('1415', '230200000000', '克山县', '230229000000', '2', null, null);
INSERT INTO `areas` VALUES ('1416', '230200000000', '克东县', '230230000000', '2', null, null);
INSERT INTO `areas` VALUES ('1417', '230200000000', '拜泉县', '230231000000', '2', null, null);
INSERT INTO `areas` VALUES ('1418', '230200000000', '讷河市', '230281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1419', '230300000000', '市辖区', '230301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1420', '230300000000', '鸡冠区', '230302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1421', '230300000000', '恒山区', '230303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1422', '230300000000', '滴道区', '230304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1423', '230300000000', '梨树区', '230305000000', '2', null, null);
INSERT INTO `areas` VALUES ('1424', '230300000000', '城子河区', '230306000000', '2', null, null);
INSERT INTO `areas` VALUES ('1425', '230300000000', '麻山区', '230307000000', '2', null, null);
INSERT INTO `areas` VALUES ('1426', '230300000000', '鸡东县', '230321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1427', '230300000000', '虎林市', '230381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1428', '230300000000', '密山市', '230382000000', '2', null, null);
INSERT INTO `areas` VALUES ('1429', '230400000000', '市辖区', '230401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1430', '230400000000', '向阳区', '230402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1431', '230400000000', '工农区', '230403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1432', '230400000000', '南山区', '230404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1433', '230400000000', '兴安区', '230405000000', '2', null, null);
INSERT INTO `areas` VALUES ('1434', '230400000000', '东山区', '230406000000', '2', null, null);
INSERT INTO `areas` VALUES ('1435', '230400000000', '兴山区', '230407000000', '2', null, null);
INSERT INTO `areas` VALUES ('1436', '230400000000', '萝北县', '230421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1437', '230400000000', '绥滨县', '230422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1438', '231100000000', '市辖区', '231101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1439', '231100000000', '爱辉区', '231102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1440', '231100000000', '逊克县', '231123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1441', '231100000000', '孙吴县', '231124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1442', '231100000000', '北安市', '231181000000', '2', null, null);
INSERT INTO `areas` VALUES ('1443', '231100000000', '五大连池市', '231182000000', '2', null, null);
INSERT INTO `areas` VALUES ('1444', '231100000000', '嫩江市', '231183000000', '2', null, null);
INSERT INTO `areas` VALUES ('1445', '231200000000', '市辖区', '231201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1446', '231200000000', '北林区', '231202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1447', '231200000000', '望奎县', '231221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1448', '231200000000', '兰西县', '231222000000', '2', null, null);
INSERT INTO `areas` VALUES ('1449', '231200000000', '青冈县', '231223000000', '2', null, null);
INSERT INTO `areas` VALUES ('1450', '231200000000', '庆安县', '231224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1451', '231200000000', '明水县', '231225000000', '2', null, null);
INSERT INTO `areas` VALUES ('1452', '231200000000', '绥棱县', '231226000000', '2', null, null);
INSERT INTO `areas` VALUES ('1453', '231200000000', '安达市', '231281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1454', '231200000000', '肇东市', '231282000000', '2', null, null);
INSERT INTO `areas` VALUES ('1455', '231200000000', '海伦市', '231283000000', '2', null, null);
INSERT INTO `areas` VALUES ('1456', '230500000000', '市辖区', '230501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1457', '230500000000', '尖山区', '230502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1458', '230500000000', '岭东区', '230503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1459', '230500000000', '四方台区', '230505000000', '2', null, null);
INSERT INTO `areas` VALUES ('1460', '230500000000', '宝山区', '230506000000', '2', null, null);
INSERT INTO `areas` VALUES ('1461', '230500000000', '集贤县', '230521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1462', '230500000000', '友谊县', '230522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1463', '230500000000', '宝清县', '230523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1464', '230500000000', '饶河县', '230524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1465', '230600000000', '市辖区', '230601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1466', '230600000000', '萨尔图区', '230602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1467', '230600000000', '龙凤区', '230603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1468', '230600000000', '让胡路区', '230604000000', '2', null, null);
INSERT INTO `areas` VALUES ('1469', '230600000000', '红岗区', '230605000000', '2', null, null);
INSERT INTO `areas` VALUES ('1470', '230600000000', '大同区', '230606000000', '2', null, null);
INSERT INTO `areas` VALUES ('1471', '230600000000', '肇州县', '230621000000', '2', null, null);
INSERT INTO `areas` VALUES ('1472', '230600000000', '肇源县', '230622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1473', '230600000000', '林甸县', '230623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1474', '230600000000', '杜尔伯特蒙古族自治县', '230624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1475', '230600000000', '大庆高新技术产业开发区', '230671000000', '2', null, null);
INSERT INTO `areas` VALUES ('1476', '230700000000', '市辖区', '230701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1477', '230700000000', '伊美区', '230717000000', '2', null, null);
INSERT INTO `areas` VALUES ('1478', '230700000000', '乌翠区', '230718000000', '2', null, null);
INSERT INTO `areas` VALUES ('1479', '230700000000', '友好区', '230719000000', '2', null, null);
INSERT INTO `areas` VALUES ('1480', '230700000000', '嘉荫县', '230722000000', '2', null, null);
INSERT INTO `areas` VALUES ('1481', '230700000000', '汤旺县', '230723000000', '2', null, null);
INSERT INTO `areas` VALUES ('1482', '230700000000', '丰林县', '230724000000', '2', null, null);
INSERT INTO `areas` VALUES ('1483', '230700000000', '大箐山县', '230725000000', '2', null, null);
INSERT INTO `areas` VALUES ('1484', '230700000000', '南岔县', '230726000000', '2', null, null);
INSERT INTO `areas` VALUES ('1485', '230700000000', '金林区', '230751000000', '2', null, null);
INSERT INTO `areas` VALUES ('1486', '230700000000', '铁力市', '230781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1487', '230800000000', '市辖区', '230801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1488', '230800000000', '向阳区', '230803000000', '2', null, null);
INSERT INTO `areas` VALUES ('1489', '230800000000', '前进区', '230804000000', '2', null, null);
INSERT INTO `areas` VALUES ('1490', '230800000000', '东风区', '230805000000', '2', null, null);
INSERT INTO `areas` VALUES ('1491', '230800000000', '郊区', '230811000000', '2', null, null);
INSERT INTO `areas` VALUES ('1492', '230800000000', '桦南县', '230822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1493', '230800000000', '桦川县', '230826000000', '2', null, null);
INSERT INTO `areas` VALUES ('1494', '230800000000', '汤原县', '230828000000', '2', null, null);
INSERT INTO `areas` VALUES ('1495', '230800000000', '同江市', '230881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1496', '230800000000', '富锦市', '230882000000', '2', null, null);
INSERT INTO `areas` VALUES ('1497', '230800000000', '抚远市', '230883000000', '2', null, null);
INSERT INTO `areas` VALUES ('1498', '231000000000', '市辖区', '231001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1499', '231000000000', '东安区', '231002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1500', '231000000000', '阳明区', '231003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1501', '231000000000', '爱民区', '231004000000', '2', null, null);
INSERT INTO `areas` VALUES ('1502', '231000000000', '西安区', '231005000000', '2', null, null);
INSERT INTO `areas` VALUES ('1503', '231000000000', '林口县', '231025000000', '2', null, null);
INSERT INTO `areas` VALUES ('1504', '231000000000', '牡丹江经济技术开发区', '231071000000', '2', null, null);
INSERT INTO `areas` VALUES ('1505', '231000000000', '绥芬河市', '231081000000', '2', null, null);
INSERT INTO `areas` VALUES ('1506', '231000000000', '海林市', '231083000000', '2', null, null);
INSERT INTO `areas` VALUES ('1507', '231000000000', '宁安市', '231084000000', '2', null, null);
INSERT INTO `areas` VALUES ('1508', '231000000000', '穆棱市', '231085000000', '2', null, null);
INSERT INTO `areas` VALUES ('1509', '231000000000', '东宁市', '231086000000', '2', null, null);
INSERT INTO `areas` VALUES ('1510', '230900000000', '市辖区', '230901000000', '2', null, null);
INSERT INTO `areas` VALUES ('1511', '230900000000', '新兴区', '230902000000', '2', null, null);
INSERT INTO `areas` VALUES ('1512', '230900000000', '桃山区', '230903000000', '2', null, null);
INSERT INTO `areas` VALUES ('1513', '230900000000', '茄子河区', '230904000000', '2', null, null);
INSERT INTO `areas` VALUES ('1514', '230900000000', '勃利县', '230921000000', '2', null, null);
INSERT INTO `areas` VALUES ('1515', '330300000000', '市辖区', '330301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1516', '330300000000', '鹿城区', '330302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1517', '330300000000', '龙湾区', '330303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1518', '330300000000', '瓯海区', '330304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1519', '330300000000', '洞头区', '330305000000', '2', null, null);
INSERT INTO `areas` VALUES ('1520', '330300000000', '永嘉县', '330324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1521', '330300000000', '平阳县', '330326000000', '2', null, null);
INSERT INTO `areas` VALUES ('1522', '330300000000', '苍南县', '330327000000', '2', null, null);
INSERT INTO `areas` VALUES ('1523', '330300000000', '文成县', '330328000000', '2', null, null);
INSERT INTO `areas` VALUES ('1524', '330300000000', '泰顺县', '330329000000', '2', null, null);
INSERT INTO `areas` VALUES ('1525', '330300000000', '温州经济技术开发区', '330371000000', '2', null, null);
INSERT INTO `areas` VALUES ('1526', '330300000000', '瑞安市', '330381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1527', '330300000000', '乐清市', '330382000000', '2', null, null);
INSERT INTO `areas` VALUES ('1528', '330300000000', '龙港市', '330383000000', '2', null, null);
INSERT INTO `areas` VALUES ('1529', '232700000000', '漠河市', '232701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1530', '232700000000', '呼玛县', '232721000000', '2', null, null);
INSERT INTO `areas` VALUES ('1531', '232700000000', '塔河县', '232722000000', '2', null, null);
INSERT INTO `areas` VALUES ('1532', '232700000000', '加格达奇区', '232761000000', '2', null, null);
INSERT INTO `areas` VALUES ('1533', '232700000000', '松岭区', '232762000000', '2', null, null);
INSERT INTO `areas` VALUES ('1534', '232700000000', '新林区', '232763000000', '2', null, null);
INSERT INTO `areas` VALUES ('1535', '232700000000', '呼中区', '232764000000', '2', null, null);
INSERT INTO `areas` VALUES ('1536', '330400000000', '市辖区', '330401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1537', '330400000000', '南湖区', '330402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1538', '330400000000', '秀洲区', '330411000000', '2', null, null);
INSERT INTO `areas` VALUES ('1539', '330400000000', '嘉善县', '330421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1540', '330400000000', '海盐县', '330424000000', '2', null, null);
INSERT INTO `areas` VALUES ('1541', '330400000000', '海宁市', '330481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1542', '330400000000', '平湖市', '330482000000', '2', null, null);
INSERT INTO `areas` VALUES ('1543', '330400000000', '桐乡市', '330483000000', '2', null, null);
INSERT INTO `areas` VALUES ('1544', '330100000000', '市辖区', '330101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1545', '330100000000', '上城区', '330102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1546', '330100000000', '下城区', '330103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1547', '330100000000', '江干区', '330104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1548', '330100000000', '拱墅区', '330105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1549', '330100000000', '西湖区', '330106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1550', '330100000000', '滨江区', '330108000000', '2', null, null);
INSERT INTO `areas` VALUES ('1551', '330100000000', '萧山区', '330109000000', '2', null, null);
INSERT INTO `areas` VALUES ('1552', '330100000000', '余杭区', '330110000000', '2', null, null);
INSERT INTO `areas` VALUES ('1553', '330100000000', '富阳区', '330111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1554', '330100000000', '临安区', '330112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1555', '330100000000', '桐庐县', '330122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1556', '330100000000', '淳安县', '330127000000', '2', null, null);
INSERT INTO `areas` VALUES ('1557', '330100000000', '建德市', '330182000000', '2', null, null);
INSERT INTO `areas` VALUES ('1558', '330500000000', '市辖区', '330501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1559', '330500000000', '吴兴区', '330502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1560', '330500000000', '南浔区', '330503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1561', '330500000000', '德清县', '330521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1562', '330500000000', '长兴县', '330522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1563', '330500000000', '安吉县', '330523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1564', '330600000000', '市辖区', '330601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1565', '330600000000', '越城区', '330602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1566', '330600000000', '柯桥区', '330603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1567', '330600000000', '上虞区', '330604000000', '2', null, null);
INSERT INTO `areas` VALUES ('1568', '330600000000', '新昌县', '330624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1569', '330600000000', '诸暨市', '330681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1570', '330600000000', '嵊州市', '330683000000', '2', null, null);
INSERT INTO `areas` VALUES ('1571', '330700000000', '市辖区', '330701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1572', '330700000000', '婺城区', '330702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1573', '330700000000', '金东区', '330703000000', '2', null, null);
INSERT INTO `areas` VALUES ('1574', '330700000000', '武义县', '330723000000', '2', null, null);
INSERT INTO `areas` VALUES ('1575', '330700000000', '浦江县', '330726000000', '2', null, null);
INSERT INTO `areas` VALUES ('1576', '330700000000', '磐安县', '330727000000', '2', null, null);
INSERT INTO `areas` VALUES ('1577', '330700000000', '兰溪市', '330781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1578', '330700000000', '义乌市', '330782000000', '2', null, null);
INSERT INTO `areas` VALUES ('1579', '330700000000', '东阳市', '330783000000', '2', null, null);
INSERT INTO `areas` VALUES ('1580', '330700000000', '永康市', '330784000000', '2', null, null);
INSERT INTO `areas` VALUES ('1581', '330800000000', '市辖区', '330801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1582', '330800000000', '柯城区', '330802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1583', '330800000000', '衢江区', '330803000000', '2', null, null);
INSERT INTO `areas` VALUES ('1584', '330800000000', '常山县', '330822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1585', '330800000000', '开化县', '330824000000', '2', null, null);
INSERT INTO `areas` VALUES ('1586', '330800000000', '龙游县', '330825000000', '2', null, null);
INSERT INTO `areas` VALUES ('1587', '330800000000', '江山市', '330881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1588', '330900000000', '市辖区', '330901000000', '2', null, null);
INSERT INTO `areas` VALUES ('1589', '330900000000', '定海区', '330902000000', '2', null, null);
INSERT INTO `areas` VALUES ('1590', '330900000000', '普陀区', '330903000000', '2', null, null);
INSERT INTO `areas` VALUES ('1591', '330900000000', '岱山县', '330921000000', '2', null, null);
INSERT INTO `areas` VALUES ('1592', '330900000000', '嵊泗县', '330922000000', '2', null, null);
INSERT INTO `areas` VALUES ('1593', '331100000000', '市辖区', '331101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1594', '331100000000', '莲都区', '331102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1595', '331100000000', '青田县', '331121000000', '2', null, null);
INSERT INTO `areas` VALUES ('1596', '331100000000', '缙云县', '331122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1597', '331100000000', '遂昌县', '331123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1598', '331100000000', '松阳县', '331124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1599', '331100000000', '云和县', '331125000000', '2', null, null);
INSERT INTO `areas` VALUES ('1600', '331100000000', '庆元县', '331126000000', '2', null, null);
INSERT INTO `areas` VALUES ('1601', '331100000000', '景宁畲族自治县', '331127000000', '2', null, null);
INSERT INTO `areas` VALUES ('1602', '331100000000', '龙泉市', '331181000000', '2', null, null);
INSERT INTO `areas` VALUES ('1603', '350300000000', '市辖区', '350301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1604', '350300000000', '城厢区', '350302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1605', '350300000000', '涵江区', '350303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1606', '350300000000', '荔城区', '350304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1607', '350300000000', '秀屿区', '350305000000', '2', null, null);
INSERT INTO `areas` VALUES ('1608', '350300000000', '仙游县', '350322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1609', '350100000000', '市辖区', '350101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1610', '350100000000', '鼓楼区', '350102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1611', '350100000000', '台江区', '350103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1612', '350100000000', '仓山区', '350104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1613', '350100000000', '马尾区', '350105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1614', '350100000000', '晋安区', '350111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1615', '350100000000', '长乐区', '350112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1616', '350100000000', '闽侯县', '350121000000', '2', null, null);
INSERT INTO `areas` VALUES ('1617', '350100000000', '连江县', '350122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1618', '350100000000', '罗源县', '350123000000', '2', null, null);
INSERT INTO `areas` VALUES ('1619', '350100000000', '闽清县', '350124000000', '2', null, null);
INSERT INTO `areas` VALUES ('1620', '350100000000', '永泰县', '350125000000', '2', null, null);
INSERT INTO `areas` VALUES ('1621', '350100000000', '平潭县', '350128000000', '2', null, null);
INSERT INTO `areas` VALUES ('1622', '350100000000', '福清市', '350181000000', '2', null, null);
INSERT INTO `areas` VALUES ('1623', '350200000000', '市辖区', '350201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1624', '350200000000', '思明区', '350203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1625', '350200000000', '海沧区', '350205000000', '2', null, null);
INSERT INTO `areas` VALUES ('1626', '350200000000', '湖里区', '350206000000', '2', null, null);
INSERT INTO `areas` VALUES ('1627', '350200000000', '集美区', '350211000000', '2', null, null);
INSERT INTO `areas` VALUES ('1628', '350200000000', '同安区', '350212000000', '2', null, null);
INSERT INTO `areas` VALUES ('1629', '350200000000', '翔安区', '350213000000', '2', null, null);
INSERT INTO `areas` VALUES ('1630', '350500000000', '市辖区', '350501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1631', '350500000000', '鲤城区', '350502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1632', '350500000000', '丰泽区', '350503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1633', '350500000000', '洛江区', '350504000000', '2', null, null);
INSERT INTO `areas` VALUES ('1634', '350500000000', '泉港区', '350505000000', '2', null, null);
INSERT INTO `areas` VALUES ('1635', '350500000000', '惠安县', '350521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1636', '350500000000', '安溪县', '350524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1637', '350500000000', '永春县', '350525000000', '2', null, null);
INSERT INTO `areas` VALUES ('1638', '350500000000', '德化县', '350526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1639', '350500000000', '金门县', '350527000000', '2', null, null);
INSERT INTO `areas` VALUES ('1640', '350500000000', '石狮市', '350581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1641', '350500000000', '晋江市', '350582000000', '2', null, null);
INSERT INTO `areas` VALUES ('1642', '350500000000', '南安市', '350583000000', '2', null, null);
INSERT INTO `areas` VALUES ('1643', '331000000000', '市辖区', '331001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1644', '331000000000', '椒江区', '331002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1645', '331000000000', '黄岩区', '331003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1646', '331000000000', '路桥区', '331004000000', '2', null, null);
INSERT INTO `areas` VALUES ('1647', '331000000000', '三门县', '331022000000', '2', null, null);
INSERT INTO `areas` VALUES ('1648', '331000000000', '天台县', '331023000000', '2', null, null);
INSERT INTO `areas` VALUES ('1649', '331000000000', '仙居县', '331024000000', '2', null, null);
INSERT INTO `areas` VALUES ('1650', '331000000000', '温岭市', '331081000000', '2', null, null);
INSERT INTO `areas` VALUES ('1651', '331000000000', '临海市', '331082000000', '2', null, null);
INSERT INTO `areas` VALUES ('1652', '331000000000', '玉环市', '331083000000', '2', null, null);
INSERT INTO `areas` VALUES ('1653', '350400000000', '市辖区', '350401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1654', '350400000000', '梅列区', '350402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1655', '350400000000', '三元区', '350403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1656', '350400000000', '明溪县', '350421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1657', '350400000000', '清流县', '350423000000', '2', null, null);
INSERT INTO `areas` VALUES ('1658', '350400000000', '宁化县', '350424000000', '2', null, null);
INSERT INTO `areas` VALUES ('1659', '350400000000', '大田县', '350425000000', '2', null, null);
INSERT INTO `areas` VALUES ('1660', '350400000000', '尤溪县', '350426000000', '2', null, null);
INSERT INTO `areas` VALUES ('1661', '350400000000', '沙县', '350427000000', '2', null, null);
INSERT INTO `areas` VALUES ('1662', '350400000000', '将乐县', '350428000000', '2', null, null);
INSERT INTO `areas` VALUES ('1663', '350400000000', '泰宁县', '350429000000', '2', null, null);
INSERT INTO `areas` VALUES ('1664', '350400000000', '建宁县', '350430000000', '2', null, null);
INSERT INTO `areas` VALUES ('1665', '350400000000', '永安市', '350481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1666', '350600000000', '市辖区', '350601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1667', '350600000000', '芗城区', '350602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1668', '350600000000', '龙文区', '350603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1669', '350600000000', '云霄县', '350622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1670', '350600000000', '漳浦县', '350623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1671', '350600000000', '诏安县', '350624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1672', '350600000000', '长泰县', '350625000000', '2', null, null);
INSERT INTO `areas` VALUES ('1673', '350600000000', '东山县', '350626000000', '2', null, null);
INSERT INTO `areas` VALUES ('1674', '350600000000', '南靖县', '350627000000', '2', null, null);
INSERT INTO `areas` VALUES ('1675', '350600000000', '平和县', '350628000000', '2', null, null);
INSERT INTO `areas` VALUES ('1676', '350600000000', '华安县', '350629000000', '2', null, null);
INSERT INTO `areas` VALUES ('1677', '350600000000', '龙海市', '350681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1678', '350700000000', '市辖区', '350701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1679', '350700000000', '延平区', '350702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1680', '350700000000', '建阳区', '350703000000', '2', null, null);
INSERT INTO `areas` VALUES ('1681', '350700000000', '顺昌县', '350721000000', '2', null, null);
INSERT INTO `areas` VALUES ('1682', '350700000000', '浦城县', '350722000000', '2', null, null);
INSERT INTO `areas` VALUES ('1683', '350700000000', '光泽县', '350723000000', '2', null, null);
INSERT INTO `areas` VALUES ('1684', '350700000000', '松溪县', '350724000000', '2', null, null);
INSERT INTO `areas` VALUES ('1685', '350700000000', '政和县', '350725000000', '2', null, null);
INSERT INTO `areas` VALUES ('1686', '350700000000', '邵武市', '350781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1687', '350700000000', '武夷山市', '350782000000', '2', null, null);
INSERT INTO `areas` VALUES ('1688', '350700000000', '建瓯市', '350783000000', '2', null, null);
INSERT INTO `areas` VALUES ('1689', '350800000000', '市辖区', '350801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1690', '350800000000', '新罗区', '350802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1691', '350800000000', '永定区', '350803000000', '2', null, null);
INSERT INTO `areas` VALUES ('1692', '350800000000', '长汀县', '350821000000', '2', null, null);
INSERT INTO `areas` VALUES ('1693', '350800000000', '上杭县', '350823000000', '2', null, null);
INSERT INTO `areas` VALUES ('1694', '350800000000', '武平县', '350824000000', '2', null, null);
INSERT INTO `areas` VALUES ('1695', '350800000000', '连城县', '350825000000', '2', null, null);
INSERT INTO `areas` VALUES ('1696', '350800000000', '漳平市', '350881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1697', '410200000000', '市辖区', '410201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1698', '410200000000', '龙亭区', '410202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1699', '410200000000', '顺河回族区', '410203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1700', '410200000000', '鼓楼区', '410204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1701', '410200000000', '禹王台区', '410205000000', '2', null, null);
INSERT INTO `areas` VALUES ('1702', '410200000000', '祥符区', '410212000000', '2', null, null);
INSERT INTO `areas` VALUES ('1703', '410200000000', '杞县', '410221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1704', '410200000000', '通许县', '410222000000', '2', null, null);
INSERT INTO `areas` VALUES ('1705', '410200000000', '尉氏县', '410223000000', '2', null, null);
INSERT INTO `areas` VALUES ('1706', '410200000000', '兰考县', '410225000000', '2', null, null);
INSERT INTO `areas` VALUES ('1707', '410300000000', '市辖区', '410301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1708', '410300000000', '老城区', '410302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1709', '410300000000', '西工区', '410303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1710', '410300000000', '瀍河回族区', '410304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1711', '410300000000', '涧西区', '410305000000', '2', null, null);
INSERT INTO `areas` VALUES ('1712', '410300000000', '吉利区', '410306000000', '2', null, null);
INSERT INTO `areas` VALUES ('1713', '410300000000', '洛龙区', '410311000000', '2', null, null);
INSERT INTO `areas` VALUES ('1714', '410300000000', '孟津县', '410322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1715', '410300000000', '新安县', '410323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1716', '410300000000', '栾川县', '410324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1717', '410300000000', '嵩县', '410325000000', '2', null, null);
INSERT INTO `areas` VALUES ('1718', '410300000000', '汝阳县', '410326000000', '2', null, null);
INSERT INTO `areas` VALUES ('1719', '410300000000', '宜阳县', '410327000000', '2', null, null);
INSERT INTO `areas` VALUES ('1720', '410300000000', '洛宁县', '410328000000', '2', null, null);
INSERT INTO `areas` VALUES ('1721', '410300000000', '伊川县', '410329000000', '2', null, null);
INSERT INTO `areas` VALUES ('1722', '410300000000', '洛阳高新技术产业开发区', '410371000000', '2', null, null);
INSERT INTO `areas` VALUES ('1723', '410300000000', '偃师市', '410381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1724', '410500000000', '市辖区', '410501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1725', '410500000000', '文峰区', '410502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1726', '410500000000', '北关区', '410503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1727', '410500000000', '殷都区', '410505000000', '2', null, null);
INSERT INTO `areas` VALUES ('1728', '410500000000', '龙安区', '410506000000', '2', null, null);
INSERT INTO `areas` VALUES ('1729', '410500000000', '安阳县', '410522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1730', '410500000000', '汤阴县', '410523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1731', '410500000000', '滑县', '410526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1732', '410500000000', '内黄县', '410527000000', '2', null, null);
INSERT INTO `areas` VALUES ('1733', '410500000000', '安阳高新技术产业开发区', '410571000000', '2', null, null);
INSERT INTO `areas` VALUES ('1734', '410500000000', '林州市', '410581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1735', '410600000000', '市辖区', '410601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1736', '410600000000', '鹤山区', '410602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1737', '410600000000', '山城区', '410603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1738', '410600000000', '淇滨区', '410611000000', '2', null, null);
INSERT INTO `areas` VALUES ('1739', '410600000000', '浚县', '410621000000', '2', null, null);
INSERT INTO `areas` VALUES ('1740', '410600000000', '淇县', '410622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1741', '410600000000', '鹤壁经济技术开发区', '410671000000', '2', null, null);
INSERT INTO `areas` VALUES ('1742', '410100000000', '市辖区', '410101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1743', '410100000000', '中原区', '410102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1744', '410100000000', '二七区', '410103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1745', '410100000000', '管城回族区', '410104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1746', '410100000000', '金水区', '410105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1747', '410100000000', '上街区', '410106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1748', '410100000000', '惠济区', '410108000000', '2', null, null);
INSERT INTO `areas` VALUES ('1749', '410100000000', '中牟县', '410122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1750', '410100000000', '郑州经济技术开发区', '410171000000', '2', null, null);
INSERT INTO `areas` VALUES ('1751', '410100000000', '郑州高新技术产业开发区', '410172000000', '2', null, null);
INSERT INTO `areas` VALUES ('1752', '410100000000', '郑州航空港经济综合实验区', '410173000000', '2', null, null);
INSERT INTO `areas` VALUES ('1753', '410100000000', '巩义市', '410181000000', '2', null, null);
INSERT INTO `areas` VALUES ('1754', '410100000000', '荥阳市', '410182000000', '2', null, null);
INSERT INTO `areas` VALUES ('1755', '410100000000', '新密市', '410183000000', '2', null, null);
INSERT INTO `areas` VALUES ('1756', '410100000000', '新郑市', '410184000000', '2', null, null);
INSERT INTO `areas` VALUES ('1757', '410100000000', '登封市', '410185000000', '2', null, null);
INSERT INTO `areas` VALUES ('1758', '410400000000', '市辖区', '410401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1759', '410400000000', '新华区', '410402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1760', '410400000000', '卫东区', '410403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1761', '410400000000', '石龙区', '410404000000', '2', null, null);
INSERT INTO `areas` VALUES ('1762', '410400000000', '湛河区', '410411000000', '2', null, null);
INSERT INTO `areas` VALUES ('1763', '410400000000', '宝丰县', '410421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1764', '410400000000', '叶县', '410422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1765', '410400000000', '鲁山县', '410423000000', '2', null, null);
INSERT INTO `areas` VALUES ('1766', '410400000000', '郏县', '410425000000', '2', null, null);
INSERT INTO `areas` VALUES ('1767', '410400000000', '平顶山高新技术产业开发区', '410471000000', '2', null, null);
INSERT INTO `areas` VALUES ('1768', '410400000000', '平顶山市城乡一体化示范区', '410472000000', '2', null, null);
INSERT INTO `areas` VALUES ('1769', '410400000000', '舞钢市', '410481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1770', '410400000000', '汝州市', '410482000000', '2', null, null);
INSERT INTO `areas` VALUES ('1771', '410800000000', '市辖区', '410801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1772', '410800000000', '解放区', '410802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1773', '410800000000', '中站区', '410803000000', '2', null, null);
INSERT INTO `areas` VALUES ('1774', '410800000000', '马村区', '410804000000', '2', null, null);
INSERT INTO `areas` VALUES ('1775', '410800000000', '山阳区', '410811000000', '2', null, null);
INSERT INTO `areas` VALUES ('1776', '410800000000', '修武县', '410821000000', '2', null, null);
INSERT INTO `areas` VALUES ('1777', '410800000000', '博爱县', '410822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1778', '410800000000', '武陟县', '410823000000', '2', null, null);
INSERT INTO `areas` VALUES ('1779', '410800000000', '温县', '410825000000', '2', null, null);
INSERT INTO `areas` VALUES ('1780', '410800000000', '焦作城乡一体化示范区', '410871000000', '2', null, null);
INSERT INTO `areas` VALUES ('1781', '410800000000', '沁阳市', '410882000000', '2', null, null);
INSERT INTO `areas` VALUES ('1782', '410800000000', '孟州市', '410883000000', '2', null, null);
INSERT INTO `areas` VALUES ('1783', '410700000000', '市辖区', '410701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1784', '410700000000', '红旗区', '410702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1785', '410700000000', '卫滨区', '410703000000', '2', null, null);
INSERT INTO `areas` VALUES ('1786', '410700000000', '凤泉区', '410704000000', '2', null, null);
INSERT INTO `areas` VALUES ('1787', '410700000000', '牧野区', '410711000000', '2', null, null);
INSERT INTO `areas` VALUES ('1788', '410700000000', '新乡县', '410721000000', '2', null, null);
INSERT INTO `areas` VALUES ('1789', '410700000000', '获嘉县', '410724000000', '2', null, null);
INSERT INTO `areas` VALUES ('1790', '410700000000', '原阳县', '410725000000', '2', null, null);
INSERT INTO `areas` VALUES ('1791', '410700000000', '延津县', '410726000000', '2', null, null);
INSERT INTO `areas` VALUES ('1792', '410700000000', '封丘县', '410727000000', '2', null, null);
INSERT INTO `areas` VALUES ('1793', '410700000000', '新乡高新技术产业开发区', '410771000000', '2', null, null);
INSERT INTO `areas` VALUES ('1794', '410700000000', '新乡经济技术开发区', '410772000000', '2', null, null);
INSERT INTO `areas` VALUES ('1795', '410700000000', '新乡市平原城乡一体化示范区', '410773000000', '2', null, null);
INSERT INTO `areas` VALUES ('1796', '410700000000', '卫辉市', '410781000000', '2', null, null);
INSERT INTO `areas` VALUES ('1797', '410700000000', '辉县市', '410782000000', '2', null, null);
INSERT INTO `areas` VALUES ('1798', '410700000000', '长垣市', '410783000000', '2', null, null);
INSERT INTO `areas` VALUES ('1799', '411000000000', '市辖区', '411001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1800', '411000000000', '魏都区', '411002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1801', '411000000000', '建安区', '411003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1802', '411000000000', '鄢陵县', '411024000000', '2', null, null);
INSERT INTO `areas` VALUES ('1803', '411000000000', '襄城县', '411025000000', '2', null, null);
INSERT INTO `areas` VALUES ('1804', '411000000000', '许昌经济技术开发区', '411071000000', '2', null, null);
INSERT INTO `areas` VALUES ('1805', '411000000000', '禹州市', '411081000000', '2', null, null);
INSERT INTO `areas` VALUES ('1806', '411000000000', '长葛市', '411082000000', '2', null, null);
INSERT INTO `areas` VALUES ('1807', '410900000000', '市辖区', '410901000000', '2', null, null);
INSERT INTO `areas` VALUES ('1808', '410900000000', '华龙区', '410902000000', '2', null, null);
INSERT INTO `areas` VALUES ('1809', '410900000000', '清丰县', '410922000000', '2', null, null);
INSERT INTO `areas` VALUES ('1810', '410900000000', '南乐县', '410923000000', '2', null, null);
INSERT INTO `areas` VALUES ('1811', '410900000000', '范县', '410926000000', '2', null, null);
INSERT INTO `areas` VALUES ('1812', '410900000000', '台前县', '410927000000', '2', null, null);
INSERT INTO `areas` VALUES ('1813', '410900000000', '濮阳县', '410928000000', '2', null, null);
INSERT INTO `areas` VALUES ('1814', '410900000000', '河南濮阳工业园区', '410971000000', '2', null, null);
INSERT INTO `areas` VALUES ('1815', '410900000000', '濮阳经济技术开发区', '410972000000', '2', null, null);
INSERT INTO `areas` VALUES ('1816', '411100000000', '市辖区', '411101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1817', '411100000000', '源汇区', '411102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1818', '411100000000', '郾城区', '411103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1819', '411100000000', '召陵区', '411104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1820', '411100000000', '舞阳县', '411121000000', '2', null, null);
INSERT INTO `areas` VALUES ('1821', '411100000000', '临颍县', '411122000000', '2', null, null);
INSERT INTO `areas` VALUES ('1822', '411100000000', '漯河经济技术开发区', '411171000000', '2', null, null);
INSERT INTO `areas` VALUES ('1823', '411400000000', '市辖区', '411401000000', '2', null, null);
INSERT INTO `areas` VALUES ('1824', '411400000000', '梁园区', '411402000000', '2', null, null);
INSERT INTO `areas` VALUES ('1825', '411400000000', '睢阳区', '411403000000', '2', null, null);
INSERT INTO `areas` VALUES ('1826', '411400000000', '民权县', '411421000000', '2', null, null);
INSERT INTO `areas` VALUES ('1827', '411400000000', '睢县', '411422000000', '2', null, null);
INSERT INTO `areas` VALUES ('1828', '411400000000', '宁陵县', '411423000000', '2', null, null);
INSERT INTO `areas` VALUES ('1829', '411400000000', '柘城县', '411424000000', '2', null, null);
INSERT INTO `areas` VALUES ('1830', '411400000000', '虞城县', '411425000000', '2', null, null);
INSERT INTO `areas` VALUES ('1831', '411400000000', '夏邑县', '411426000000', '2', null, null);
INSERT INTO `areas` VALUES ('1832', '411400000000', '豫东综合物流产业聚集区', '411471000000', '2', null, null);
INSERT INTO `areas` VALUES ('1833', '411400000000', '河南商丘经济开发区', '411472000000', '2', null, null);
INSERT INTO `areas` VALUES ('1834', '411400000000', '永城市', '411481000000', '2', null, null);
INSERT INTO `areas` VALUES ('1835', '411200000000', '市辖区', '411201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1836', '411200000000', '湖滨区', '411202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1837', '411200000000', '陕州区', '411203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1838', '411200000000', '渑池县', '411221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1839', '411200000000', '卢氏县', '411224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1840', '411200000000', '河南三门峡经济开发区', '411271000000', '2', null, null);
INSERT INTO `areas` VALUES ('1841', '411200000000', '义马市', '411281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1842', '411200000000', '灵宝市', '411282000000', '2', null, null);
INSERT INTO `areas` VALUES ('1843', '411300000000', '市辖区', '411301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1844', '411300000000', '宛城区', '411302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1845', '411300000000', '卧龙区', '411303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1846', '411300000000', '南召县', '411321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1847', '411300000000', '方城县', '411322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1848', '411300000000', '西峡县', '411323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1849', '411300000000', '镇平县', '411324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1850', '411300000000', '内乡县', '411325000000', '2', null, null);
INSERT INTO `areas` VALUES ('1851', '411300000000', '淅川县', '411326000000', '2', null, null);
INSERT INTO `areas` VALUES ('1852', '411300000000', '社旗县', '411327000000', '2', null, null);
INSERT INTO `areas` VALUES ('1853', '411300000000', '唐河县', '411328000000', '2', null, null);
INSERT INTO `areas` VALUES ('1854', '411300000000', '新野县', '411329000000', '2', null, null);
INSERT INTO `areas` VALUES ('1855', '411300000000', '桐柏县', '411330000000', '2', null, null);
INSERT INTO `areas` VALUES ('1856', '411300000000', '南阳高新技术产业开发区', '411371000000', '2', null, null);
INSERT INTO `areas` VALUES ('1857', '411300000000', '南阳市城乡一体化示范区', '411372000000', '2', null, null);
INSERT INTO `areas` VALUES ('1858', '411300000000', '邓州市', '411381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1859', '419000000000', '济源市', '419001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1860', '420100000000', '市辖区', '420101000000', '2', null, null);
INSERT INTO `areas` VALUES ('1861', '420100000000', '江岸区', '420102000000', '2', null, null);
INSERT INTO `areas` VALUES ('1862', '420100000000', '江汉区', '420103000000', '2', null, null);
INSERT INTO `areas` VALUES ('1863', '420100000000', '硚口区', '420104000000', '2', null, null);
INSERT INTO `areas` VALUES ('1864', '420100000000', '汉阳区', '420105000000', '2', null, null);
INSERT INTO `areas` VALUES ('1865', '420100000000', '武昌区', '420106000000', '2', null, null);
INSERT INTO `areas` VALUES ('1866', '420100000000', '青山区', '420107000000', '2', null, null);
INSERT INTO `areas` VALUES ('1867', '420100000000', '洪山区', '420111000000', '2', null, null);
INSERT INTO `areas` VALUES ('1868', '420100000000', '东西湖区', '420112000000', '2', null, null);
INSERT INTO `areas` VALUES ('1869', '420100000000', '汉南区', '420113000000', '2', null, null);
INSERT INTO `areas` VALUES ('1870', '420100000000', '蔡甸区', '420114000000', '2', null, null);
INSERT INTO `areas` VALUES ('1871', '420100000000', '江夏区', '420115000000', '2', null, null);
INSERT INTO `areas` VALUES ('1872', '420100000000', '黄陂区', '420116000000', '2', null, null);
INSERT INTO `areas` VALUES ('1873', '420100000000', '新洲区', '420117000000', '2', null, null);
INSERT INTO `areas` VALUES ('1874', '420300000000', '市辖区', '420301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1875', '420300000000', '茅箭区', '420302000000', '2', null, null);
INSERT INTO `areas` VALUES ('1876', '420300000000', '张湾区', '420303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1877', '420300000000', '郧阳区', '420304000000', '2', null, null);
INSERT INTO `areas` VALUES ('1878', '420300000000', '郧西县', '420322000000', '2', null, null);
INSERT INTO `areas` VALUES ('1879', '420300000000', '竹山县', '420323000000', '2', null, null);
INSERT INTO `areas` VALUES ('1880', '420300000000', '竹溪县', '420324000000', '2', null, null);
INSERT INTO `areas` VALUES ('1881', '420300000000', '房县', '420325000000', '2', null, null);
INSERT INTO `areas` VALUES ('1882', '420300000000', '丹江口市', '420381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1883', '420200000000', '市辖区', '420201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1884', '420200000000', '黄石港区', '420202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1885', '420200000000', '西塞山区', '420203000000', '2', null, null);
INSERT INTO `areas` VALUES ('1886', '420200000000', '下陆区', '420204000000', '2', null, null);
INSERT INTO `areas` VALUES ('1887', '420200000000', '铁山区', '420205000000', '2', null, null);
INSERT INTO `areas` VALUES ('1888', '420200000000', '阳新县', '420222000000', '2', null, null);
INSERT INTO `areas` VALUES ('1889', '420200000000', '大冶市', '420281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1890', '411600000000', '市辖区', '411601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1891', '411600000000', '川汇区', '411602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1892', '411600000000', '淮阳区', '411603000000', '2', null, null);
INSERT INTO `areas` VALUES ('1893', '411600000000', '扶沟县', '411621000000', '2', null, null);
INSERT INTO `areas` VALUES ('1894', '411600000000', '西华县', '411622000000', '2', null, null);
INSERT INTO `areas` VALUES ('1895', '411600000000', '商水县', '411623000000', '2', null, null);
INSERT INTO `areas` VALUES ('1896', '411600000000', '沈丘县', '411624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1897', '411600000000', '郸城县', '411625000000', '2', null, null);
INSERT INTO `areas` VALUES ('1898', '411600000000', '太康县', '411627000000', '2', null, null);
INSERT INTO `areas` VALUES ('1899', '411600000000', '鹿邑县', '411628000000', '2', null, null);
INSERT INTO `areas` VALUES ('1900', '411600000000', '河南周口经济开发区', '411671000000', '2', null, null);
INSERT INTO `areas` VALUES ('1901', '411600000000', '项城市', '411681000000', '2', null, null);
INSERT INTO `areas` VALUES ('1902', '411700000000', '市辖区', '411701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1903', '411700000000', '驿城区', '411702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1904', '411700000000', '西平县', '411721000000', '2', null, null);
INSERT INTO `areas` VALUES ('1905', '411700000000', '上蔡县', '411722000000', '2', null, null);
INSERT INTO `areas` VALUES ('1906', '411700000000', '平舆县', '411723000000', '2', null, null);
INSERT INTO `areas` VALUES ('1907', '411700000000', '正阳县', '411724000000', '2', null, null);
INSERT INTO `areas` VALUES ('1908', '411700000000', '确山县', '411725000000', '2', null, null);
INSERT INTO `areas` VALUES ('1909', '411700000000', '泌阳县', '411726000000', '2', null, null);
INSERT INTO `areas` VALUES ('1910', '411700000000', '汝南县', '411727000000', '2', null, null);
INSERT INTO `areas` VALUES ('1911', '411700000000', '遂平县', '411728000000', '2', null, null);
INSERT INTO `areas` VALUES ('1912', '411700000000', '新蔡县', '411729000000', '2', null, null);
INSERT INTO `areas` VALUES ('1913', '411700000000', '河南驻马店经济开发区', '411771000000', '2', null, null);
INSERT INTO `areas` VALUES ('1914', '420500000000', '市辖区', '420501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1915', '420500000000', '西陵区', '420502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1916', '420500000000', '伍家岗区', '420503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1917', '420500000000', '点军区', '420504000000', '2', null, null);
INSERT INTO `areas` VALUES ('1918', '420500000000', '猇亭区', '420505000000', '2', null, null);
INSERT INTO `areas` VALUES ('1919', '420500000000', '夷陵区', '420506000000', '2', null, null);
INSERT INTO `areas` VALUES ('1920', '420500000000', '远安县', '420525000000', '2', null, null);
INSERT INTO `areas` VALUES ('1921', '420500000000', '兴山县', '420526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1922', '420500000000', '秭归县', '420527000000', '2', null, null);
INSERT INTO `areas` VALUES ('1923', '420500000000', '长阳土家族自治县', '420528000000', '2', null, null);
INSERT INTO `areas` VALUES ('1924', '420500000000', '五峰土家族自治县', '420529000000', '2', null, null);
INSERT INTO `areas` VALUES ('1925', '420500000000', '宜都市', '420581000000', '2', null, null);
INSERT INTO `areas` VALUES ('1926', '420500000000', '当阳市', '420582000000', '2', null, null);
INSERT INTO `areas` VALUES ('1927', '420500000000', '枝江市', '420583000000', '2', null, null);
INSERT INTO `areas` VALUES ('1928', '411500000000', '市辖区', '411501000000', '2', null, null);
INSERT INTO `areas` VALUES ('1929', '411500000000', '浉河区', '411502000000', '2', null, null);
INSERT INTO `areas` VALUES ('1930', '411500000000', '平桥区', '411503000000', '2', null, null);
INSERT INTO `areas` VALUES ('1931', '411500000000', '罗山县', '411521000000', '2', null, null);
INSERT INTO `areas` VALUES ('1932', '411500000000', '光山县', '411522000000', '2', null, null);
INSERT INTO `areas` VALUES ('1933', '411500000000', '新县', '411523000000', '2', null, null);
INSERT INTO `areas` VALUES ('1934', '411500000000', '商城县', '411524000000', '2', null, null);
INSERT INTO `areas` VALUES ('1935', '411500000000', '固始县', '411525000000', '2', null, null);
INSERT INTO `areas` VALUES ('1936', '411500000000', '潢川县', '411526000000', '2', null, null);
INSERT INTO `areas` VALUES ('1937', '411500000000', '淮滨县', '411527000000', '2', null, null);
INSERT INTO `areas` VALUES ('1938', '411500000000', '息县', '411528000000', '2', null, null);
INSERT INTO `areas` VALUES ('1939', '411500000000', '信阳高新技术产业开发区', '411571000000', '2', null, null);
INSERT INTO `areas` VALUES ('1940', '420600000000', '市辖区', '420601000000', '2', null, null);
INSERT INTO `areas` VALUES ('1941', '420600000000', '襄城区', '420602000000', '2', null, null);
INSERT INTO `areas` VALUES ('1942', '420600000000', '樊城区', '420606000000', '2', null, null);
INSERT INTO `areas` VALUES ('1943', '420600000000', '襄州区', '420607000000', '2', null, null);
INSERT INTO `areas` VALUES ('1944', '420600000000', '南漳县', '420624000000', '2', null, null);
INSERT INTO `areas` VALUES ('1945', '420600000000', '谷城县', '420625000000', '2', null, null);
INSERT INTO `areas` VALUES ('1946', '420600000000', '保康县', '420626000000', '2', null, null);
INSERT INTO `areas` VALUES ('1947', '420600000000', '老河口市', '420682000000', '2', null, null);
INSERT INTO `areas` VALUES ('1948', '420600000000', '枣阳市', '420683000000', '2', null, null);
INSERT INTO `areas` VALUES ('1949', '420600000000', '宜城市', '420684000000', '2', null, null);
INSERT INTO `areas` VALUES ('1950', '420700000000', '市辖区', '420701000000', '2', null, null);
INSERT INTO `areas` VALUES ('1951', '420700000000', '梁子湖区', '420702000000', '2', null, null);
INSERT INTO `areas` VALUES ('1952', '420700000000', '华容区', '420703000000', '2', null, null);
INSERT INTO `areas` VALUES ('1953', '420700000000', '鄂城区', '420704000000', '2', null, null);
INSERT INTO `areas` VALUES ('1954', '420900000000', '市辖区', '420901000000', '2', null, null);
INSERT INTO `areas` VALUES ('1955', '420900000000', '孝南区', '420902000000', '2', null, null);
INSERT INTO `areas` VALUES ('1956', '420900000000', '孝昌县', '420921000000', '2', null, null);
INSERT INTO `areas` VALUES ('1957', '420900000000', '大悟县', '420922000000', '2', null, null);
INSERT INTO `areas` VALUES ('1958', '420900000000', '云梦县', '420923000000', '2', null, null);
INSERT INTO `areas` VALUES ('1959', '420900000000', '应城市', '420981000000', '2', null, null);
INSERT INTO `areas` VALUES ('1960', '420900000000', '安陆市', '420982000000', '2', null, null);
INSERT INTO `areas` VALUES ('1961', '420900000000', '汉川市', '420984000000', '2', null, null);
INSERT INTO `areas` VALUES ('1962', '420800000000', '市辖区', '420801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1963', '420800000000', '东宝区', '420802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1964', '420800000000', '掇刀区', '420804000000', '2', null, null);
INSERT INTO `areas` VALUES ('1965', '420800000000', '沙洋县', '420822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1966', '420800000000', '钟祥市', '420881000000', '2', null, null);
INSERT INTO `areas` VALUES ('1967', '420800000000', '京山市', '420882000000', '2', null, null);
INSERT INTO `areas` VALUES ('1968', '421300000000', '市辖区', '421301000000', '2', null, null);
INSERT INTO `areas` VALUES ('1969', '421300000000', '曾都区', '421303000000', '2', null, null);
INSERT INTO `areas` VALUES ('1970', '421300000000', '随县', '421321000000', '2', null, null);
INSERT INTO `areas` VALUES ('1971', '421300000000', '广水市', '421381000000', '2', null, null);
INSERT INTO `areas` VALUES ('1972', '421000000000', '市辖区', '421001000000', '2', null, null);
INSERT INTO `areas` VALUES ('1973', '421000000000', '沙市区', '421002000000', '2', null, null);
INSERT INTO `areas` VALUES ('1974', '421000000000', '荆州区', '421003000000', '2', null, null);
INSERT INTO `areas` VALUES ('1975', '421000000000', '公安县', '421022000000', '2', null, null);
INSERT INTO `areas` VALUES ('1976', '421000000000', '监利县', '421023000000', '2', null, null);
INSERT INTO `areas` VALUES ('1977', '421000000000', '江陵县', '421024000000', '2', null, null);
INSERT INTO `areas` VALUES ('1978', '421000000000', '荆州经济技术开发区', '421071000000', '2', null, null);
INSERT INTO `areas` VALUES ('1979', '421000000000', '石首市', '421081000000', '2', null, null);
INSERT INTO `areas` VALUES ('1980', '421000000000', '洪湖市', '421083000000', '2', null, null);
INSERT INTO `areas` VALUES ('1981', '421000000000', '松滋市', '421087000000', '2', null, null);
INSERT INTO `areas` VALUES ('1982', '421200000000', '市辖区', '421201000000', '2', null, null);
INSERT INTO `areas` VALUES ('1983', '421200000000', '咸安区', '421202000000', '2', null, null);
INSERT INTO `areas` VALUES ('1984', '421200000000', '嘉鱼县', '421221000000', '2', null, null);
INSERT INTO `areas` VALUES ('1985', '421200000000', '通城县', '421222000000', '2', null, null);
INSERT INTO `areas` VALUES ('1986', '421200000000', '崇阳县', '421223000000', '2', null, null);
INSERT INTO `areas` VALUES ('1987', '421200000000', '通山县', '421224000000', '2', null, null);
INSERT INTO `areas` VALUES ('1988', '421200000000', '赤壁市', '421281000000', '2', null, null);
INSERT INTO `areas` VALUES ('1989', '422800000000', '恩施市', '422801000000', '2', null, null);
INSERT INTO `areas` VALUES ('1990', '422800000000', '利川市', '422802000000', '2', null, null);
INSERT INTO `areas` VALUES ('1991', '422800000000', '建始县', '422822000000', '2', null, null);
INSERT INTO `areas` VALUES ('1992', '422800000000', '巴东县', '422823000000', '2', null, null);
INSERT INTO `areas` VALUES ('1993', '422800000000', '宣恩县', '422825000000', '2', null, null);
INSERT INTO `areas` VALUES ('1994', '422800000000', '咸丰县', '422826000000', '2', null, null);
INSERT INTO `areas` VALUES ('1995', '422800000000', '来凤县', '422827000000', '2', null, null);
INSERT INTO `areas` VALUES ('1996', '422800000000', '鹤峰县', '422828000000', '2', null, null);
INSERT INTO `areas` VALUES ('1997', '429000000000', '仙桃市', '429004000000', '2', null, null);
INSERT INTO `areas` VALUES ('1998', '429000000000', '潜江市', '429005000000', '2', null, null);
INSERT INTO `areas` VALUES ('1999', '429000000000', '天门市', '429006000000', '2', null, null);
INSERT INTO `areas` VALUES ('2000', '429000000000', '神农架林区', '429021000000', '2', null, null);
INSERT INTO `areas` VALUES ('2001', '421100000000', '市辖区', '421101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2002', '421100000000', '黄州区', '421102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2003', '421100000000', '团风县', '421121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2004', '421100000000', '红安县', '421122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2005', '421100000000', '罗田县', '421123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2006', '421100000000', '英山县', '421124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2007', '421100000000', '浠水县', '421125000000', '2', null, null);
INSERT INTO `areas` VALUES ('2008', '421100000000', '蕲春县', '421126000000', '2', null, null);
INSERT INTO `areas` VALUES ('2009', '421100000000', '黄梅县', '421127000000', '2', null, null);
INSERT INTO `areas` VALUES ('2010', '421100000000', '龙感湖管理区', '421171000000', '2', null, null);
INSERT INTO `areas` VALUES ('2011', '421100000000', '麻城市', '421181000000', '2', null, null);
INSERT INTO `areas` VALUES ('2012', '421100000000', '武穴市', '421182000000', '2', null, null);
INSERT INTO `areas` VALUES ('2013', '430200000000', '市辖区', '430201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2014', '430200000000', '荷塘区', '430202000000', '2', null, null);
INSERT INTO `areas` VALUES ('2015', '430200000000', '芦淞区', '430203000000', '2', null, null);
INSERT INTO `areas` VALUES ('2016', '430200000000', '石峰区', '430204000000', '2', null, null);
INSERT INTO `areas` VALUES ('2017', '430200000000', '天元区', '430211000000', '2', null, null);
INSERT INTO `areas` VALUES ('2018', '430200000000', '渌口区', '430212000000', '2', null, null);
INSERT INTO `areas` VALUES ('2019', '430200000000', '攸县', '430223000000', '2', null, null);
INSERT INTO `areas` VALUES ('2020', '430200000000', '茶陵县', '430224000000', '2', null, null);
INSERT INTO `areas` VALUES ('2021', '430200000000', '炎陵县', '430225000000', '2', null, null);
INSERT INTO `areas` VALUES ('2022', '430200000000', '云龙示范区', '430271000000', '2', null, null);
INSERT INTO `areas` VALUES ('2023', '430200000000', '醴陵市', '430281000000', '2', null, null);
INSERT INTO `areas` VALUES ('2024', '430400000000', '市辖区', '430401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2025', '430400000000', '珠晖区', '430405000000', '2', null, null);
INSERT INTO `areas` VALUES ('2026', '430400000000', '雁峰区', '430406000000', '2', null, null);
INSERT INTO `areas` VALUES ('2027', '430400000000', '石鼓区', '430407000000', '2', null, null);
INSERT INTO `areas` VALUES ('2028', '430400000000', '蒸湘区', '430408000000', '2', null, null);
INSERT INTO `areas` VALUES ('2029', '430400000000', '南岳区', '430412000000', '2', null, null);
INSERT INTO `areas` VALUES ('2030', '430400000000', '衡阳县', '430421000000', '2', null, null);
INSERT INTO `areas` VALUES ('2031', '430400000000', '衡南县', '430422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2032', '430400000000', '衡山县', '430423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2033', '430400000000', '衡东县', '430424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2034', '430400000000', '祁东县', '430426000000', '2', null, null);
INSERT INTO `areas` VALUES ('2035', '430400000000', '衡阳综合保税区', '430471000000', '2', null, null);
INSERT INTO `areas` VALUES ('2036', '430400000000', '湖南衡阳高新技术产业园区', '430472000000', '2', null, null);
INSERT INTO `areas` VALUES ('2037', '430400000000', '湖南衡阳松木经济开发区', '430473000000', '2', null, null);
INSERT INTO `areas` VALUES ('2038', '430400000000', '耒阳市', '430481000000', '2', null, null);
INSERT INTO `areas` VALUES ('2039', '430400000000', '常宁市', '430482000000', '2', null, null);
INSERT INTO `areas` VALUES ('2040', '430500000000', '市辖区', '430501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2041', '430500000000', '双清区', '430502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2042', '430500000000', '大祥区', '430503000000', '2', null, null);
INSERT INTO `areas` VALUES ('2043', '430500000000', '北塔区', '430511000000', '2', null, null);
INSERT INTO `areas` VALUES ('2044', '430500000000', '新邵县', '430522000000', '2', null, null);
INSERT INTO `areas` VALUES ('2045', '430500000000', '邵阳县', '430523000000', '2', null, null);
INSERT INTO `areas` VALUES ('2046', '430500000000', '隆回县', '430524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2047', '430500000000', '洞口县', '430525000000', '2', null, null);
INSERT INTO `areas` VALUES ('2048', '430500000000', '绥宁县', '430527000000', '2', null, null);
INSERT INTO `areas` VALUES ('2049', '430500000000', '新宁县', '430528000000', '2', null, null);
INSERT INTO `areas` VALUES ('2050', '430500000000', '城步苗族自治县', '430529000000', '2', null, null);
INSERT INTO `areas` VALUES ('2051', '430500000000', '武冈市', '430581000000', '2', null, null);
INSERT INTO `areas` VALUES ('2052', '430500000000', '邵东市', '430582000000', '2', null, null);
INSERT INTO `areas` VALUES ('2053', '430100000000', '市辖区', '430101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2054', '430100000000', '芙蓉区', '430102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2055', '430100000000', '天心区', '430103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2056', '430100000000', '岳麓区', '430104000000', '2', null, null);
INSERT INTO `areas` VALUES ('2057', '430100000000', '开福区', '430105000000', '2', null, null);
INSERT INTO `areas` VALUES ('2058', '430100000000', '雨花区', '430111000000', '2', null, null);
INSERT INTO `areas` VALUES ('2059', '430100000000', '望城区', '430112000000', '2', null, null);
INSERT INTO `areas` VALUES ('2060', '430100000000', '长沙县', '430121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2061', '430100000000', '浏阳市', '430181000000', '2', null, null);
INSERT INTO `areas` VALUES ('2062', '430100000000', '宁乡市', '430182000000', '2', null, null);
INSERT INTO `areas` VALUES ('2063', '430700000000', '市辖区', '430701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2064', '430700000000', '武陵区', '430702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2065', '430700000000', '鼎城区', '430703000000', '2', null, null);
INSERT INTO `areas` VALUES ('2066', '430700000000', '安乡县', '430721000000', '2', null, null);
INSERT INTO `areas` VALUES ('2067', '430700000000', '汉寿县', '430722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2068', '430700000000', '澧县', '430723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2069', '430700000000', '临澧县', '430724000000', '2', null, null);
INSERT INTO `areas` VALUES ('2070', '430700000000', '桃源县', '430725000000', '2', null, null);
INSERT INTO `areas` VALUES ('2071', '430700000000', '石门县', '430726000000', '2', null, null);
INSERT INTO `areas` VALUES ('2072', '430700000000', '常德市西洞庭管理区', '430771000000', '2', null, null);
INSERT INTO `areas` VALUES ('2073', '430700000000', '津市市', '430781000000', '2', null, null);
INSERT INTO `areas` VALUES ('2074', '430800000000', '市辖区', '430801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2075', '430800000000', '永定区', '430802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2076', '430800000000', '武陵源区', '430811000000', '2', null, null);
INSERT INTO `areas` VALUES ('2077', '430800000000', '慈利县', '430821000000', '2', null, null);
INSERT INTO `areas` VALUES ('2078', '430800000000', '桑植县', '430822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2079', '430900000000', '市辖区', '430901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2080', '430900000000', '资阳区', '430902000000', '2', null, null);
INSERT INTO `areas` VALUES ('2081', '430900000000', '赫山区', '430903000000', '2', null, null);
INSERT INTO `areas` VALUES ('2082', '430900000000', '南县', '430921000000', '2', null, null);
INSERT INTO `areas` VALUES ('2083', '430900000000', '桃江县', '430922000000', '2', null, null);
INSERT INTO `areas` VALUES ('2084', '430900000000', '安化县', '430923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2085', '430900000000', '益阳市大通湖管理区', '430971000000', '2', null, null);
INSERT INTO `areas` VALUES ('2086', '430900000000', '湖南益阳高新技术产业园区', '430972000000', '2', null, null);
INSERT INTO `areas` VALUES ('2087', '430900000000', '沅江市', '430981000000', '2', null, null);
INSERT INTO `areas` VALUES ('2088', '430600000000', '市辖区', '430601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2089', '430600000000', '岳阳楼区', '430602000000', '2', null, null);
INSERT INTO `areas` VALUES ('2090', '430600000000', '云溪区', '430603000000', '2', null, null);
INSERT INTO `areas` VALUES ('2091', '430600000000', '君山区', '430611000000', '2', null, null);
INSERT INTO `areas` VALUES ('2092', '430600000000', '岳阳县', '430621000000', '2', null, null);
INSERT INTO `areas` VALUES ('2093', '430600000000', '华容县', '430623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2094', '430600000000', '湘阴县', '430624000000', '2', null, null);
INSERT INTO `areas` VALUES ('2095', '430600000000', '平江县', '430626000000', '2', null, null);
INSERT INTO `areas` VALUES ('2096', '430600000000', '岳阳市屈原管理区', '430671000000', '2', null, null);
INSERT INTO `areas` VALUES ('2097', '430600000000', '汨罗市', '430681000000', '2', null, null);
INSERT INTO `areas` VALUES ('2098', '430600000000', '临湘市', '430682000000', '2', null, null);
INSERT INTO `areas` VALUES ('2099', '431300000000', '市辖区', '431301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2100', '431300000000', '娄星区', '431302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2101', '431300000000', '双峰县', '431321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2102', '431300000000', '新化县', '431322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2103', '431300000000', '冷水江市', '431381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2104', '431300000000', '涟源市', '431382000000', '2', null, null);
INSERT INTO `areas` VALUES ('2105', '431200000000', '市辖区', '431201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2106', '431200000000', '鹤城区', '431202000000', '2', null, null);
INSERT INTO `areas` VALUES ('2107', '431200000000', '中方县', '431221000000', '2', null, null);
INSERT INTO `areas` VALUES ('2108', '431200000000', '沅陵县', '431222000000', '2', null, null);
INSERT INTO `areas` VALUES ('2109', '431200000000', '辰溪县', '431223000000', '2', null, null);
INSERT INTO `areas` VALUES ('2110', '431200000000', '溆浦县', '431224000000', '2', null, null);
INSERT INTO `areas` VALUES ('2111', '431200000000', '会同县', '431225000000', '2', null, null);
INSERT INTO `areas` VALUES ('2112', '431200000000', '麻阳苗族自治县', '431226000000', '2', null, null);
INSERT INTO `areas` VALUES ('2113', '431200000000', '新晃侗族自治县', '431227000000', '2', null, null);
INSERT INTO `areas` VALUES ('2114', '431200000000', '芷江侗族自治县', '431228000000', '2', null, null);
INSERT INTO `areas` VALUES ('2115', '431200000000', '靖州苗族侗族自治县', '431229000000', '2', null, null);
INSERT INTO `areas` VALUES ('2116', '431200000000', '通道侗族自治县', '431230000000', '2', null, null);
INSERT INTO `areas` VALUES ('2117', '431200000000', '怀化市洪江管理区', '431271000000', '2', null, null);
INSERT INTO `areas` VALUES ('2118', '431200000000', '洪江市', '431281000000', '2', null, null);
INSERT INTO `areas` VALUES ('2119', '430300000000', '市辖区', '430301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2120', '430300000000', '雨湖区', '430302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2121', '430300000000', '岳塘区', '430304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2122', '430300000000', '湘潭县', '430321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2123', '430300000000', '湖南湘潭高新技术产业园区', '430371000000', '2', null, null);
INSERT INTO `areas` VALUES ('2124', '430300000000', '湘潭昭山示范区', '430372000000', '2', null, null);
INSERT INTO `areas` VALUES ('2125', '430300000000', '湘潭九华示范区', '430373000000', '2', null, null);
INSERT INTO `areas` VALUES ('2126', '430300000000', '湘乡市', '430381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2127', '430300000000', '韶山市', '430382000000', '2', null, null);
INSERT INTO `areas` VALUES ('2128', '431100000000', '市辖区', '431101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2129', '431100000000', '零陵区', '431102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2130', '431100000000', '冷水滩区', '431103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2131', '431100000000', '祁阳县', '431121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2132', '431100000000', '东安县', '431122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2133', '431100000000', '双牌县', '431123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2134', '431100000000', '道县', '431124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2135', '431100000000', '江永县', '431125000000', '2', null, null);
INSERT INTO `areas` VALUES ('2136', '431100000000', '宁远县', '431126000000', '2', null, null);
INSERT INTO `areas` VALUES ('2137', '431100000000', '蓝山县', '431127000000', '2', null, null);
INSERT INTO `areas` VALUES ('2138', '431100000000', '新田县', '431128000000', '2', null, null);
INSERT INTO `areas` VALUES ('2139', '431100000000', '江华瑶族自治县', '431129000000', '2', null, null);
INSERT INTO `areas` VALUES ('2140', '431100000000', '永州经济技术开发区', '431171000000', '2', null, null);
INSERT INTO `areas` VALUES ('2141', '431100000000', '永州市金洞管理区', '431172000000', '2', null, null);
INSERT INTO `areas` VALUES ('2142', '431100000000', '永州市回龙圩管理区', '431173000000', '2', null, null);
INSERT INTO `areas` VALUES ('2143', '433100000000', '吉首市', '433101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2144', '433100000000', '泸溪县', '433122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2145', '433100000000', '凤凰县', '433123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2146', '433100000000', '花垣县', '433124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2147', '433100000000', '保靖县', '433125000000', '2', null, null);
INSERT INTO `areas` VALUES ('2148', '433100000000', '古丈县', '433126000000', '2', null, null);
INSERT INTO `areas` VALUES ('2149', '433100000000', '永顺县', '433127000000', '2', null, null);
INSERT INTO `areas` VALUES ('2150', '433100000000', '龙山县', '433130000000', '2', null, null);
INSERT INTO `areas` VALUES ('2151', '433100000000', '湖南永顺经济开发区', '433173000000', '2', null, null);
INSERT INTO `areas` VALUES ('2152', '450200000000', '市辖区', '450201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2153', '450200000000', '城中区', '450202000000', '2', null, null);
INSERT INTO `areas` VALUES ('2154', '450200000000', '鱼峰区', '450203000000', '2', null, null);
INSERT INTO `areas` VALUES ('2155', '450200000000', '柳南区', '450204000000', '2', null, null);
INSERT INTO `areas` VALUES ('2156', '450200000000', '柳北区', '450205000000', '2', null, null);
INSERT INTO `areas` VALUES ('2157', '450200000000', '柳江区', '450206000000', '2', null, null);
INSERT INTO `areas` VALUES ('2158', '450200000000', '柳城县', '450222000000', '2', null, null);
INSERT INTO `areas` VALUES ('2159', '450200000000', '鹿寨县', '450223000000', '2', null, null);
INSERT INTO `areas` VALUES ('2160', '450200000000', '融安县', '450224000000', '2', null, null);
INSERT INTO `areas` VALUES ('2161', '450200000000', '融水苗族自治县', '450225000000', '2', null, null);
INSERT INTO `areas` VALUES ('2162', '450200000000', '三江侗族自治县', '450226000000', '2', null, null);
INSERT INTO `areas` VALUES ('2163', '450100000000', '市辖区', '450101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2164', '450100000000', '兴宁区', '450102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2165', '450100000000', '青秀区', '450103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2166', '450100000000', '江南区', '450105000000', '2', null, null);
INSERT INTO `areas` VALUES ('2167', '450100000000', '西乡塘区', '450107000000', '2', null, null);
INSERT INTO `areas` VALUES ('2168', '450100000000', '良庆区', '450108000000', '2', null, null);
INSERT INTO `areas` VALUES ('2169', '450100000000', '邕宁区', '450109000000', '2', null, null);
INSERT INTO `areas` VALUES ('2170', '450100000000', '武鸣区', '450110000000', '2', null, null);
INSERT INTO `areas` VALUES ('2171', '450100000000', '隆安县', '450123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2172', '450100000000', '马山县', '450124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2173', '450100000000', '上林县', '450125000000', '2', null, null);
INSERT INTO `areas` VALUES ('2174', '450100000000', '宾阳县', '450126000000', '2', null, null);
INSERT INTO `areas` VALUES ('2175', '450100000000', '横县', '450127000000', '2', null, null);
INSERT INTO `areas` VALUES ('2176', '431000000000', '市辖区', '431001000000', '2', null, null);
INSERT INTO `areas` VALUES ('2177', '431000000000', '北湖区', '431002000000', '2', null, null);
INSERT INTO `areas` VALUES ('2178', '431000000000', '苏仙区', '431003000000', '2', null, null);
INSERT INTO `areas` VALUES ('2179', '431000000000', '桂阳县', '431021000000', '2', null, null);
INSERT INTO `areas` VALUES ('2180', '431000000000', '宜章县', '431022000000', '2', null, null);
INSERT INTO `areas` VALUES ('2181', '431000000000', '永兴县', '431023000000', '2', null, null);
INSERT INTO `areas` VALUES ('2182', '431000000000', '嘉禾县', '431024000000', '2', null, null);
INSERT INTO `areas` VALUES ('2183', '431000000000', '临武县', '431025000000', '2', null, null);
INSERT INTO `areas` VALUES ('2184', '431000000000', '汝城县', '431026000000', '2', null, null);
INSERT INTO `areas` VALUES ('2185', '431000000000', '桂东县', '431027000000', '2', null, null);
INSERT INTO `areas` VALUES ('2186', '431000000000', '安仁县', '431028000000', '2', null, null);
INSERT INTO `areas` VALUES ('2187', '431000000000', '资兴市', '431081000000', '2', null, null);
INSERT INTO `areas` VALUES ('2188', '450400000000', '市辖区', '450401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2189', '450400000000', '万秀区', '450403000000', '2', null, null);
INSERT INTO `areas` VALUES ('2190', '450400000000', '长洲区', '450405000000', '2', null, null);
INSERT INTO `areas` VALUES ('2191', '450400000000', '龙圩区', '450406000000', '2', null, null);
INSERT INTO `areas` VALUES ('2192', '450400000000', '苍梧县', '450421000000', '2', null, null);
INSERT INTO `areas` VALUES ('2193', '450400000000', '藤县', '450422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2194', '450400000000', '蒙山县', '450423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2195', '450400000000', '岑溪市', '450481000000', '2', null, null);
INSERT INTO `areas` VALUES ('2196', '450500000000', '市辖区', '450501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2197', '450500000000', '海城区', '450502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2198', '450500000000', '银海区', '450503000000', '2', null, null);
INSERT INTO `areas` VALUES ('2199', '450500000000', '铁山港区', '450512000000', '2', null, null);
INSERT INTO `areas` VALUES ('2200', '450500000000', '合浦县', '450521000000', '2', null, null);
INSERT INTO `areas` VALUES ('2201', '450300000000', '市辖区', '450301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2202', '450300000000', '秀峰区', '450302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2203', '450300000000', '叠彩区', '450303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2204', '450300000000', '象山区', '450304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2205', '450300000000', '七星区', '450305000000', '2', null, null);
INSERT INTO `areas` VALUES ('2206', '450300000000', '雁山区', '450311000000', '2', null, null);
INSERT INTO `areas` VALUES ('2207', '450300000000', '临桂区', '450312000000', '2', null, null);
INSERT INTO `areas` VALUES ('2208', '450300000000', '阳朔县', '450321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2209', '450300000000', '灵川县', '450323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2210', '450300000000', '全州县', '450324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2211', '450300000000', '兴安县', '450325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2212', '450300000000', '永福县', '450326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2213', '450300000000', '灌阳县', '450327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2214', '450300000000', '龙胜各族自治县', '450328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2215', '450300000000', '资源县', '450329000000', '2', null, null);
INSERT INTO `areas` VALUES ('2216', '450300000000', '平乐县', '450330000000', '2', null, null);
INSERT INTO `areas` VALUES ('2217', '450300000000', '恭城瑶族自治县', '450332000000', '2', null, null);
INSERT INTO `areas` VALUES ('2218', '450300000000', '荔浦市', '450381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2219', '450600000000', '市辖区', '450601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2220', '450600000000', '港口区', '450602000000', '2', null, null);
INSERT INTO `areas` VALUES ('2221', '450600000000', '防城区', '450603000000', '2', null, null);
INSERT INTO `areas` VALUES ('2222', '450600000000', '上思县', '450621000000', '2', null, null);
INSERT INTO `areas` VALUES ('2223', '450600000000', '东兴市', '450681000000', '2', null, null);
INSERT INTO `areas` VALUES ('2224', '450700000000', '市辖区', '450701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2225', '450700000000', '钦南区', '450702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2226', '450700000000', '钦北区', '450703000000', '2', null, null);
INSERT INTO `areas` VALUES ('2227', '450700000000', '灵山县', '450721000000', '2', null, null);
INSERT INTO `areas` VALUES ('2228', '450700000000', '浦北县', '450722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2229', '450800000000', '市辖区', '450801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2230', '450800000000', '港北区', '450802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2231', '450800000000', '港南区', '450803000000', '2', null, null);
INSERT INTO `areas` VALUES ('2232', '450800000000', '覃塘区', '450804000000', '2', null, null);
INSERT INTO `areas` VALUES ('2233', '450800000000', '平南县', '450821000000', '2', null, null);
INSERT INTO `areas` VALUES ('2234', '450800000000', '桂平市', '450881000000', '2', null, null);
INSERT INTO `areas` VALUES ('2235', '450900000000', '市辖区', '450901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2236', '450900000000', '玉州区', '450902000000', '2', null, null);
INSERT INTO `areas` VALUES ('2237', '450900000000', '福绵区', '450903000000', '2', null, null);
INSERT INTO `areas` VALUES ('2238', '450900000000', '容县', '450921000000', '2', null, null);
INSERT INTO `areas` VALUES ('2239', '450900000000', '陆川县', '450922000000', '2', null, null);
INSERT INTO `areas` VALUES ('2240', '450900000000', '博白县', '450923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2241', '450900000000', '兴业县', '450924000000', '2', null, null);
INSERT INTO `areas` VALUES ('2242', '450900000000', '北流市', '450981000000', '2', null, null);
INSERT INTO `areas` VALUES ('2243', '451000000000', '市辖区', '451001000000', '2', null, null);
INSERT INTO `areas` VALUES ('2244', '451000000000', '右江区', '451002000000', '2', null, null);
INSERT INTO `areas` VALUES ('2245', '451000000000', '田阳区', '451003000000', '2', null, null);
INSERT INTO `areas` VALUES ('2246', '451000000000', '田东县', '451022000000', '2', null, null);
INSERT INTO `areas` VALUES ('2247', '451000000000', '平果县', '451023000000', '2', null, null);
INSERT INTO `areas` VALUES ('2248', '451000000000', '德保县', '451024000000', '2', null, null);
INSERT INTO `areas` VALUES ('2249', '451000000000', '那坡县', '451026000000', '2', null, null);
INSERT INTO `areas` VALUES ('2250', '451000000000', '凌云县', '451027000000', '2', null, null);
INSERT INTO `areas` VALUES ('2251', '451000000000', '乐业县', '451028000000', '2', null, null);
INSERT INTO `areas` VALUES ('2252', '451000000000', '田林县', '451029000000', '2', null, null);
INSERT INTO `areas` VALUES ('2253', '451000000000', '西林县', '451030000000', '2', null, null);
INSERT INTO `areas` VALUES ('2254', '451000000000', '隆林各族自治县', '451031000000', '2', null, null);
INSERT INTO `areas` VALUES ('2255', '451000000000', '靖西市', '451081000000', '2', null, null);
INSERT INTO `areas` VALUES ('2256', '451100000000', '市辖区', '451101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2257', '451100000000', '八步区', '451102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2258', '451100000000', '平桂区', '451103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2259', '451100000000', '昭平县', '451121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2260', '451100000000', '钟山县', '451122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2261', '451100000000', '富川瑶族自治县', '451123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2262', '451300000000', '市辖区', '451301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2263', '451300000000', '兴宾区', '451302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2264', '451300000000', '忻城县', '451321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2265', '451300000000', '象州县', '451322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2266', '451300000000', '武宣县', '451323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2267', '451300000000', '金秀瑶族自治县', '451324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2268', '451300000000', '合山市', '451381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2269', '451400000000', '市辖区', '451401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2270', '451400000000', '江州区', '451402000000', '2', null, null);
INSERT INTO `areas` VALUES ('2271', '451400000000', '扶绥县', '451421000000', '2', null, null);
INSERT INTO `areas` VALUES ('2272', '451400000000', '宁明县', '451422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2273', '451400000000', '龙州县', '451423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2274', '451400000000', '大新县', '451424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2275', '451400000000', '天等县', '451425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2276', '451400000000', '凭祥市', '451481000000', '2', null, null);
INSERT INTO `areas` VALUES ('2277', '451200000000', '市辖区', '451201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2278', '451200000000', '金城江区', '451202000000', '2', null, null);
INSERT INTO `areas` VALUES ('2279', '451200000000', '宜州区', '451203000000', '2', null, null);
INSERT INTO `areas` VALUES ('2280', '451200000000', '南丹县', '451221000000', '2', null, null);
INSERT INTO `areas` VALUES ('2281', '451200000000', '天峨县', '451222000000', '2', null, null);
INSERT INTO `areas` VALUES ('2282', '451200000000', '凤山县', '451223000000', '2', null, null);
INSERT INTO `areas` VALUES ('2283', '451200000000', '东兰县', '451224000000', '2', null, null);
INSERT INTO `areas` VALUES ('2284', '451200000000', '罗城仫佬族自治县', '451225000000', '2', null, null);
INSERT INTO `areas` VALUES ('2285', '451200000000', '环江毛南族自治县', '451226000000', '2', null, null);
INSERT INTO `areas` VALUES ('2286', '451200000000', '巴马瑶族自治县', '451227000000', '2', null, null);
INSERT INTO `areas` VALUES ('2287', '451200000000', '都安瑶族自治县', '451228000000', '2', null, null);
INSERT INTO `areas` VALUES ('2288', '451200000000', '大化瑶族自治县', '451229000000', '2', null, null);
INSERT INTO `areas` VALUES ('2289', '510300000000', '市辖区', '510301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2290', '510300000000', '自流井区', '510302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2291', '510300000000', '贡井区', '510303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2292', '510300000000', '大安区', '510304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2293', '510300000000', '沿滩区', '510311000000', '2', null, null);
INSERT INTO `areas` VALUES ('2294', '510300000000', '荣县', '510321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2295', '510300000000', '富顺县', '510322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2296', '510500000000', '市辖区', '510501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2297', '510500000000', '江阳区', '510502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2298', '510500000000', '纳溪区', '510503000000', '2', null, null);
INSERT INTO `areas` VALUES ('2299', '510500000000', '龙马潭区', '510504000000', '2', null, null);
INSERT INTO `areas` VALUES ('2300', '510500000000', '泸县', '510521000000', '2', null, null);
INSERT INTO `areas` VALUES ('2301', '510500000000', '合江县', '510522000000', '2', null, null);
INSERT INTO `areas` VALUES ('2302', '510500000000', '叙永县', '510524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2303', '510500000000', '古蔺县', '510525000000', '2', null, null);
INSERT INTO `areas` VALUES ('2304', '510600000000', '市辖区', '510601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2305', '510600000000', '旌阳区', '510603000000', '2', null, null);
INSERT INTO `areas` VALUES ('2306', '510600000000', '罗江区', '510604000000', '2', null, null);
INSERT INTO `areas` VALUES ('2307', '510600000000', '中江县', '510623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2308', '510600000000', '广汉市', '510681000000', '2', null, null);
INSERT INTO `areas` VALUES ('2309', '510600000000', '什邡市', '510682000000', '2', null, null);
INSERT INTO `areas` VALUES ('2310', '510600000000', '绵竹市', '510683000000', '2', null, null);
INSERT INTO `areas` VALUES ('2311', '510100000000', '市辖区', '510101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2312', '510100000000', '锦江区', '510104000000', '2', null, null);
INSERT INTO `areas` VALUES ('2313', '510100000000', '青羊区', '510105000000', '2', null, null);
INSERT INTO `areas` VALUES ('2314', '510100000000', '金牛区', '510106000000', '2', null, null);
INSERT INTO `areas` VALUES ('2315', '510100000000', '武侯区', '510107000000', '2', null, null);
INSERT INTO `areas` VALUES ('2316', '510100000000', '成华区', '510108000000', '2', null, null);
INSERT INTO `areas` VALUES ('2317', '510100000000', '龙泉驿区', '510112000000', '2', null, null);
INSERT INTO `areas` VALUES ('2318', '510100000000', '青白江区', '510113000000', '2', null, null);
INSERT INTO `areas` VALUES ('2319', '510100000000', '新都区', '510114000000', '2', null, null);
INSERT INTO `areas` VALUES ('2320', '510100000000', '温江区', '510115000000', '2', null, null);
INSERT INTO `areas` VALUES ('2321', '510100000000', '双流区', '510116000000', '2', null, null);
INSERT INTO `areas` VALUES ('2322', '510100000000', '郫都区', '510117000000', '2', null, null);
INSERT INTO `areas` VALUES ('2323', '510100000000', '金堂县', '510121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2324', '510100000000', '大邑县', '510129000000', '2', null, null);
INSERT INTO `areas` VALUES ('2325', '510100000000', '蒲江县', '510131000000', '2', null, null);
INSERT INTO `areas` VALUES ('2326', '510100000000', '新津县', '510132000000', '2', null, null);
INSERT INTO `areas` VALUES ('2327', '510100000000', '都江堰市', '510181000000', '2', null, null);
INSERT INTO `areas` VALUES ('2328', '510100000000', '彭州市', '510182000000', '2', null, null);
INSERT INTO `areas` VALUES ('2329', '510100000000', '邛崃市', '510183000000', '2', null, null);
INSERT INTO `areas` VALUES ('2330', '510100000000', '崇州市', '510184000000', '2', null, null);
INSERT INTO `areas` VALUES ('2331', '510100000000', '简阳市', '510185000000', '2', null, null);
INSERT INTO `areas` VALUES ('2332', '510800000000', '市辖区', '510801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2333', '510800000000', '利州区', '510802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2334', '510800000000', '昭化区', '510811000000', '2', null, null);
INSERT INTO `areas` VALUES ('2335', '510800000000', '朝天区', '510812000000', '2', null, null);
INSERT INTO `areas` VALUES ('2336', '510800000000', '旺苍县', '510821000000', '2', null, null);
INSERT INTO `areas` VALUES ('2337', '510800000000', '青川县', '510822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2338', '510800000000', '剑阁县', '510823000000', '2', null, null);
INSERT INTO `areas` VALUES ('2339', '510800000000', '苍溪县', '510824000000', '2', null, null);
INSERT INTO `areas` VALUES ('2340', '510900000000', '市辖区', '510901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2341', '510900000000', '船山区', '510903000000', '2', null, null);
INSERT INTO `areas` VALUES ('2342', '510900000000', '安居区', '510904000000', '2', null, null);
INSERT INTO `areas` VALUES ('2343', '510900000000', '蓬溪县', '510921000000', '2', null, null);
INSERT INTO `areas` VALUES ('2344', '510900000000', '大英县', '510923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2345', '510900000000', '射洪市', '510981000000', '2', null, null);
INSERT INTO `areas` VALUES ('2346', '510700000000', '市辖区', '510701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2347', '510700000000', '涪城区', '510703000000', '2', null, null);
INSERT INTO `areas` VALUES ('2348', '510700000000', '游仙区', '510704000000', '2', null, null);
INSERT INTO `areas` VALUES ('2349', '510700000000', '安州区', '510705000000', '2', null, null);
INSERT INTO `areas` VALUES ('2350', '510700000000', '三台县', '510722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2351', '510700000000', '盐亭县', '510723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2352', '510700000000', '梓潼县', '510725000000', '2', null, null);
INSERT INTO `areas` VALUES ('2353', '510700000000', '北川羌族自治县', '510726000000', '2', null, null);
INSERT INTO `areas` VALUES ('2354', '510700000000', '平武县', '510727000000', '2', null, null);
INSERT INTO `areas` VALUES ('2355', '510700000000', '江油市', '510781000000', '2', null, null);
INSERT INTO `areas` VALUES ('2356', '511300000000', '市辖区', '511301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2357', '511300000000', '顺庆区', '511302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2358', '511300000000', '高坪区', '511303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2359', '511300000000', '嘉陵区', '511304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2360', '511300000000', '南部县', '511321000000', '2', null, null);
INSERT INTO `areas` VALUES ('2361', '511300000000', '营山县', '511322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2362', '511300000000', '蓬安县', '511323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2363', '511300000000', '仪陇县', '511324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2364', '511300000000', '西充县', '511325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2365', '511300000000', '阆中市', '511381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2366', '511400000000', '市辖区', '511401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2367', '511400000000', '东坡区', '511402000000', '2', null, null);
INSERT INTO `areas` VALUES ('2368', '511400000000', '彭山区', '511403000000', '2', null, null);
INSERT INTO `areas` VALUES ('2369', '511400000000', '仁寿县', '511421000000', '2', null, null);
INSERT INTO `areas` VALUES ('2370', '511400000000', '洪雅县', '511423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2371', '511400000000', '丹棱县', '511424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2372', '511400000000', '青神县', '511425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2373', '511700000000', '市辖区', '511701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2374', '511700000000', '通川区', '511702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2375', '511700000000', '达川区', '511703000000', '2', null, null);
INSERT INTO `areas` VALUES ('2376', '511700000000', '宣汉县', '511722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2377', '511700000000', '开江县', '511723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2378', '511700000000', '大竹县', '511724000000', '2', null, null);
INSERT INTO `areas` VALUES ('2379', '511700000000', '渠县', '511725000000', '2', null, null);
INSERT INTO `areas` VALUES ('2380', '511700000000', '达州经济开发区', '511771000000', '2', null, null);
INSERT INTO `areas` VALUES ('2381', '511700000000', '万源市', '511781000000', '2', null, null);
INSERT INTO `areas` VALUES ('2382', '511600000000', '市辖区', '511601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2383', '511600000000', '广安区', '511602000000', '2', null, null);
INSERT INTO `areas` VALUES ('2384', '511600000000', '前锋区', '511603000000', '2', null, null);
INSERT INTO `areas` VALUES ('2385', '511600000000', '岳池县', '511621000000', '2', null, null);
INSERT INTO `areas` VALUES ('2386', '511600000000', '武胜县', '511622000000', '2', null, null);
INSERT INTO `areas` VALUES ('2387', '511600000000', '邻水县', '511623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2388', '511600000000', '华蓥市', '511681000000', '2', null, null);
INSERT INTO `areas` VALUES ('2389', '511800000000', '市辖区', '511801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2390', '511800000000', '雨城区', '511802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2391', '511800000000', '名山区', '511803000000', '2', null, null);
INSERT INTO `areas` VALUES ('2392', '511800000000', '荥经县', '511822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2393', '511800000000', '汉源县', '511823000000', '2', null, null);
INSERT INTO `areas` VALUES ('2394', '511800000000', '石棉县', '511824000000', '2', null, null);
INSERT INTO `areas` VALUES ('2395', '511800000000', '天全县', '511825000000', '2', null, null);
INSERT INTO `areas` VALUES ('2396', '511800000000', '芦山县', '511826000000', '2', null, null);
INSERT INTO `areas` VALUES ('2397', '511800000000', '宝兴县', '511827000000', '2', null, null);
INSERT INTO `areas` VALUES ('2398', '511900000000', '市辖区', '511901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2399', '511900000000', '巴州区', '511902000000', '2', null, null);
INSERT INTO `areas` VALUES ('2400', '511900000000', '恩阳区', '511903000000', '2', null, null);
INSERT INTO `areas` VALUES ('2401', '511900000000', '通江县', '511921000000', '2', null, null);
INSERT INTO `areas` VALUES ('2402', '511900000000', '南江县', '511922000000', '2', null, null);
INSERT INTO `areas` VALUES ('2403', '511900000000', '平昌县', '511923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2404', '511900000000', '巴中经济开发区', '511971000000', '2', null, null);
INSERT INTO `areas` VALUES ('2405', '511500000000', '市辖区', '511501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2406', '511500000000', '翠屏区', '511502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2407', '511500000000', '南溪区', '511503000000', '2', null, null);
INSERT INTO `areas` VALUES ('2408', '511500000000', '叙州区', '511504000000', '2', null, null);
INSERT INTO `areas` VALUES ('2409', '511500000000', '江安县', '511523000000', '2', null, null);
INSERT INTO `areas` VALUES ('2410', '511500000000', '长宁县', '511524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2411', '511500000000', '高县', '511525000000', '2', null, null);
INSERT INTO `areas` VALUES ('2412', '511500000000', '珙县', '511526000000', '2', null, null);
INSERT INTO `areas` VALUES ('2413', '511500000000', '筠连县', '511527000000', '2', null, null);
INSERT INTO `areas` VALUES ('2414', '511500000000', '兴文县', '511528000000', '2', null, null);
INSERT INTO `areas` VALUES ('2415', '511500000000', '屏山县', '511529000000', '2', null, null);
INSERT INTO `areas` VALUES ('2416', '512000000000', '市辖区', '512001000000', '2', null, null);
INSERT INTO `areas` VALUES ('2417', '512000000000', '雁江区', '512002000000', '2', null, null);
INSERT INTO `areas` VALUES ('2418', '512000000000', '安岳县', '512021000000', '2', null, null);
INSERT INTO `areas` VALUES ('2419', '512000000000', '乐至县', '512022000000', '2', null, null);
INSERT INTO `areas` VALUES ('2420', '513400000000', '西昌市', '513401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2421', '513400000000', '木里藏族自治县', '513422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2422', '513400000000', '盐源县', '513423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2423', '513400000000', '德昌县', '513424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2424', '513400000000', '会理县', '513425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2425', '513400000000', '会东县', '513426000000', '2', null, null);
INSERT INTO `areas` VALUES ('2426', '513400000000', '宁南县', '513427000000', '2', null, null);
INSERT INTO `areas` VALUES ('2427', '513400000000', '普格县', '513428000000', '2', null, null);
INSERT INTO `areas` VALUES ('2428', '513400000000', '布拖县', '513429000000', '2', null, null);
INSERT INTO `areas` VALUES ('2429', '513400000000', '金阳县', '513430000000', '2', null, null);
INSERT INTO `areas` VALUES ('2430', '513400000000', '昭觉县', '513431000000', '2', null, null);
INSERT INTO `areas` VALUES ('2431', '513400000000', '喜德县', '513432000000', '2', null, null);
INSERT INTO `areas` VALUES ('2432', '513400000000', '冕宁县', '513433000000', '2', null, null);
INSERT INTO `areas` VALUES ('2433', '513400000000', '越西县', '513434000000', '2', null, null);
INSERT INTO `areas` VALUES ('2434', '513400000000', '甘洛县', '513435000000', '2', null, null);
INSERT INTO `areas` VALUES ('2435', '513400000000', '美姑县', '513436000000', '2', null, null);
INSERT INTO `areas` VALUES ('2436', '513400000000', '雷波县', '513437000000', '2', null, null);
INSERT INTO `areas` VALUES ('2437', '520100000000', '市辖区', '520101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2438', '520100000000', '南明区', '520102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2439', '520100000000', '云岩区', '520103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2440', '520100000000', '花溪区', '520111000000', '2', null, null);
INSERT INTO `areas` VALUES ('2441', '520100000000', '乌当区', '520112000000', '2', null, null);
INSERT INTO `areas` VALUES ('2442', '520100000000', '白云区', '520113000000', '2', null, null);
INSERT INTO `areas` VALUES ('2443', '520100000000', '观山湖区', '520115000000', '2', null, null);
INSERT INTO `areas` VALUES ('2444', '520100000000', '开阳县', '520121000000', '2', null, null);
INSERT INTO `areas` VALUES ('2445', '520100000000', '息烽县', '520122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2446', '520100000000', '修文县', '520123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2447', '520100000000', '清镇市', '520181000000', '2', null, null);
INSERT INTO `areas` VALUES ('2448', '520200000000', '钟山区', '520201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2449', '520200000000', '六枝特区', '520203000000', '2', null, null);
INSERT INTO `areas` VALUES ('2450', '520200000000', '水城县', '520221000000', '2', null, null);
INSERT INTO `areas` VALUES ('2451', '520200000000', '盘州市', '520281000000', '2', null, null);
INSERT INTO `areas` VALUES ('2452', '513200000000', '马尔康市', '513201000000', '2', null, null);
INSERT INTO `areas` VALUES ('2453', '513200000000', '汶川县', '513221000000', '2', null, null);
INSERT INTO `areas` VALUES ('2454', '513200000000', '理县', '513222000000', '2', null, null);
INSERT INTO `areas` VALUES ('2455', '513200000000', '茂县', '513223000000', '2', null, null);
INSERT INTO `areas` VALUES ('2456', '513200000000', '松潘县', '513224000000', '2', null, null);
INSERT INTO `areas` VALUES ('2457', '513200000000', '九寨沟县', '513225000000', '2', null, null);
INSERT INTO `areas` VALUES ('2458', '513200000000', '金川县', '513226000000', '2', null, null);
INSERT INTO `areas` VALUES ('2459', '513200000000', '小金县', '513227000000', '2', null, null);
INSERT INTO `areas` VALUES ('2460', '513200000000', '黑水县', '513228000000', '2', null, null);
INSERT INTO `areas` VALUES ('2461', '513200000000', '壤塘县', '513230000000', '2', null, null);
INSERT INTO `areas` VALUES ('2462', '513200000000', '阿坝县', '513231000000', '2', null, null);
INSERT INTO `areas` VALUES ('2463', '513200000000', '若尔盖县', '513232000000', '2', null, null);
INSERT INTO `areas` VALUES ('2464', '513200000000', '红原县', '513233000000', '2', null, null);
INSERT INTO `areas` VALUES ('2465', '513300000000', '康定市', '513301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2466', '513300000000', '泸定县', '513322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2467', '513300000000', '丹巴县', '513323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2468', '513300000000', '九龙县', '513324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2469', '513300000000', '雅江县', '513325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2470', '513300000000', '道孚县', '513326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2471', '513300000000', '炉霍县', '513327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2472', '513300000000', '甘孜县', '513328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2473', '513300000000', '新龙县', '513329000000', '2', null, null);
INSERT INTO `areas` VALUES ('2474', '513300000000', '德格县', '513330000000', '2', null, null);
INSERT INTO `areas` VALUES ('2475', '513300000000', '白玉县', '513331000000', '2', null, null);
INSERT INTO `areas` VALUES ('2476', '513300000000', '石渠县', '513332000000', '2', null, null);
INSERT INTO `areas` VALUES ('2477', '513300000000', '色达县', '513333000000', '2', null, null);
INSERT INTO `areas` VALUES ('2478', '513300000000', '理塘县', '513334000000', '2', null, null);
INSERT INTO `areas` VALUES ('2479', '513300000000', '巴塘县', '513335000000', '2', null, null);
INSERT INTO `areas` VALUES ('2480', '513300000000', '乡城县', '513336000000', '2', null, null);
INSERT INTO `areas` VALUES ('2481', '513300000000', '稻城县', '513337000000', '2', null, null);
INSERT INTO `areas` VALUES ('2482', '513300000000', '得荣县', '513338000000', '2', null, null);
INSERT INTO `areas` VALUES ('2483', '520400000000', '市辖区', '520401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2484', '520400000000', '西秀区', '520402000000', '2', null, null);
INSERT INTO `areas` VALUES ('2485', '520400000000', '平坝区', '520403000000', '2', null, null);
INSERT INTO `areas` VALUES ('2486', '520400000000', '普定县', '520422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2487', '520400000000', '镇宁布依族苗族自治县', '520423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2488', '520400000000', '关岭布依族苗族自治县', '520424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2489', '520400000000', '紫云苗族布依族自治县', '520425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2490', '520500000000', '市辖区', '520501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2491', '520500000000', '七星关区', '520502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2492', '520500000000', '大方县', '520521000000', '2', null, null);
INSERT INTO `areas` VALUES ('2493', '520500000000', '黔西县', '520522000000', '2', null, null);
INSERT INTO `areas` VALUES ('2494', '520500000000', '金沙县', '520523000000', '2', null, null);
INSERT INTO `areas` VALUES ('2495', '520500000000', '织金县', '520524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2496', '520500000000', '纳雍县', '520525000000', '2', null, null);
INSERT INTO `areas` VALUES ('2497', '520500000000', '威宁彝族回族苗族自治县', '520526000000', '2', null, null);
INSERT INTO `areas` VALUES ('2498', '520500000000', '赫章县', '520527000000', '2', null, null);
INSERT INTO `areas` VALUES ('2499', '520300000000', '市辖区', '520301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2500', '520300000000', '红花岗区', '520302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2501', '520300000000', '汇川区', '520303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2502', '520300000000', '播州区', '520304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2503', '520300000000', '桐梓县', '520322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2504', '520300000000', '绥阳县', '520323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2505', '520300000000', '正安县', '520324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2506', '520300000000', '道真仡佬族苗族自治县', '520325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2507', '520300000000', '务川仡佬族苗族自治县', '520326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2508', '520300000000', '凤冈县', '520327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2509', '520300000000', '湄潭县', '520328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2510', '520300000000', '余庆县', '520329000000', '2', null, null);
INSERT INTO `areas` VALUES ('2511', '520300000000', '习水县', '520330000000', '2', null, null);
INSERT INTO `areas` VALUES ('2512', '520300000000', '赤水市', '520381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2513', '520300000000', '仁怀市', '520382000000', '2', null, null);
INSERT INTO `areas` VALUES ('2514', '522300000000', '兴义市', '522301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2515', '522300000000', '兴仁市', '522302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2516', '522300000000', '普安县', '522323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2517', '522300000000', '晴隆县', '522324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2518', '522300000000', '贞丰县', '522325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2519', '522300000000', '望谟县', '522326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2520', '522300000000', '册亨县', '522327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2521', '522300000000', '安龙县', '522328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2522', '520600000000', '市辖区', '520601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2523', '520600000000', '碧江区', '520602000000', '2', null, null);
INSERT INTO `areas` VALUES ('2524', '520600000000', '万山区', '520603000000', '2', null, null);
INSERT INTO `areas` VALUES ('2525', '520600000000', '江口县', '520621000000', '2', null, null);
INSERT INTO `areas` VALUES ('2526', '520600000000', '玉屏侗族自治县', '520622000000', '2', null, null);
INSERT INTO `areas` VALUES ('2527', '520600000000', '石阡县', '520623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2528', '520600000000', '思南县', '520624000000', '2', null, null);
INSERT INTO `areas` VALUES ('2529', '520600000000', '印江土家族苗族自治县', '520625000000', '2', null, null);
INSERT INTO `areas` VALUES ('2530', '520600000000', '德江县', '520626000000', '2', null, null);
INSERT INTO `areas` VALUES ('2531', '520600000000', '沿河土家族自治县', '520627000000', '2', null, null);
INSERT INTO `areas` VALUES ('2532', '520600000000', '松桃苗族自治县', '520628000000', '2', null, null);
INSERT INTO `areas` VALUES ('2533', '530100000000', '市辖区', '530101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2534', '530100000000', '五华区', '530102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2535', '530100000000', '盘龙区', '530103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2536', '530100000000', '官渡区', '530111000000', '2', null, null);
INSERT INTO `areas` VALUES ('2537', '530100000000', '西山区', '530112000000', '2', null, null);
INSERT INTO `areas` VALUES ('2538', '530100000000', '东川区', '530113000000', '2', null, null);
INSERT INTO `areas` VALUES ('2539', '530100000000', '呈贡区', '530114000000', '2', null, null);
INSERT INTO `areas` VALUES ('2540', '530100000000', '晋宁区', '530115000000', '2', null, null);
INSERT INTO `areas` VALUES ('2541', '530100000000', '富民县', '530124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2542', '530100000000', '宜良县', '530125000000', '2', null, null);
INSERT INTO `areas` VALUES ('2543', '530100000000', '石林彝族自治县', '530126000000', '2', null, null);
INSERT INTO `areas` VALUES ('2544', '530100000000', '嵩明县', '530127000000', '2', null, null);
INSERT INTO `areas` VALUES ('2545', '530100000000', '禄劝彝族苗族自治县', '530128000000', '2', null, null);
INSERT INTO `areas` VALUES ('2546', '530100000000', '寻甸回族彝族自治县', '530129000000', '2', null, null);
INSERT INTO `areas` VALUES ('2547', '530100000000', '安宁市', '530181000000', '2', null, null);
INSERT INTO `areas` VALUES ('2548', '522600000000', '凯里市', '522601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2549', '522600000000', '黄平县', '522622000000', '2', null, null);
INSERT INTO `areas` VALUES ('2550', '522600000000', '施秉县', '522623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2551', '522600000000', '三穗县', '522624000000', '2', null, null);
INSERT INTO `areas` VALUES ('2552', '522600000000', '镇远县', '522625000000', '2', null, null);
INSERT INTO `areas` VALUES ('2553', '522600000000', '岑巩县', '522626000000', '2', null, null);
INSERT INTO `areas` VALUES ('2554', '522600000000', '天柱县', '522627000000', '2', null, null);
INSERT INTO `areas` VALUES ('2555', '522600000000', '锦屏县', '522628000000', '2', null, null);
INSERT INTO `areas` VALUES ('2556', '522600000000', '剑河县', '522629000000', '2', null, null);
INSERT INTO `areas` VALUES ('2557', '522600000000', '台江县', '522630000000', '2', null, null);
INSERT INTO `areas` VALUES ('2558', '522600000000', '黎平县', '522631000000', '2', null, null);
INSERT INTO `areas` VALUES ('2559', '522600000000', '榕江县', '522632000000', '2', null, null);
INSERT INTO `areas` VALUES ('2560', '522600000000', '从江县', '522633000000', '2', null, null);
INSERT INTO `areas` VALUES ('2561', '522600000000', '雷山县', '522634000000', '2', null, null);
INSERT INTO `areas` VALUES ('2562', '522600000000', '麻江县', '522635000000', '2', null, null);
INSERT INTO `areas` VALUES ('2563', '522600000000', '丹寨县', '522636000000', '2', null, null);
INSERT INTO `areas` VALUES ('2564', '530400000000', '市辖区', '530401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2565', '530400000000', '红塔区', '530402000000', '2', null, null);
INSERT INTO `areas` VALUES ('2566', '530400000000', '江川区', '530403000000', '2', null, null);
INSERT INTO `areas` VALUES ('2567', '530400000000', '澄江县', '530422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2568', '530400000000', '通海县', '530423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2569', '530400000000', '华宁县', '530424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2570', '530400000000', '易门县', '530425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2571', '530400000000', '峨山彝族自治县', '530426000000', '2', null, null);
INSERT INTO `areas` VALUES ('2572', '530400000000', '新平彝族傣族自治县', '530427000000', '2', null, null);
INSERT INTO `areas` VALUES ('2573', '530400000000', '元江哈尼族彝族傣族自治县', '530428000000', '2', null, null);
INSERT INTO `areas` VALUES ('2574', '530300000000', '市辖区', '530301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2575', '530300000000', '麒麟区', '530302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2576', '530300000000', '沾益区', '530303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2577', '530300000000', '马龙区', '530304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2578', '530300000000', '陆良县', '530322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2579', '530300000000', '师宗县', '530323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2580', '530300000000', '罗平县', '530324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2581', '530300000000', '富源县', '530325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2582', '530300000000', '会泽县', '530326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2583', '530300000000', '宣威市', '530381000000', '2', null, null);
INSERT INTO `areas` VALUES ('2584', '530500000000', '市辖区', '530501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2585', '530500000000', '隆阳区', '530502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2586', '530500000000', '施甸县', '530521000000', '2', null, null);
INSERT INTO `areas` VALUES ('2587', '530500000000', '龙陵县', '530523000000', '2', null, null);
INSERT INTO `areas` VALUES ('2588', '530500000000', '昌宁县', '530524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2589', '530500000000', '腾冲市', '530581000000', '2', null, null);
INSERT INTO `areas` VALUES ('2590', '530700000000', '市辖区', '530701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2591', '530700000000', '古城区', '530702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2592', '530700000000', '玉龙纳西族自治县', '530721000000', '2', null, null);
INSERT INTO `areas` VALUES ('2593', '530700000000', '永胜县', '530722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2594', '530700000000', '华坪县', '530723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2595', '530700000000', '宁蒗彝族自治县', '530724000000', '2', null, null);
INSERT INTO `areas` VALUES ('2596', '522700000000', '都匀市', '522701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2597', '522700000000', '福泉市', '522702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2598', '522700000000', '荔波县', '522722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2599', '522700000000', '贵定县', '522723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2600', '522700000000', '瓮安县', '522725000000', '2', null, null);
INSERT INTO `areas` VALUES ('2601', '522700000000', '独山县', '522726000000', '2', null, null);
INSERT INTO `areas` VALUES ('2602', '522700000000', '平塘县', '522727000000', '2', null, null);
INSERT INTO `areas` VALUES ('2603', '522700000000', '罗甸县', '522728000000', '2', null, null);
INSERT INTO `areas` VALUES ('2604', '522700000000', '长顺县', '522729000000', '2', null, null);
INSERT INTO `areas` VALUES ('2605', '522700000000', '龙里县', '522730000000', '2', null, null);
INSERT INTO `areas` VALUES ('2606', '522700000000', '惠水县', '522731000000', '2', null, null);
INSERT INTO `areas` VALUES ('2607', '522700000000', '三都水族自治县', '522732000000', '2', null, null);
INSERT INTO `areas` VALUES ('2608', '530900000000', '市辖区', '530901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2609', '530900000000', '临翔区', '530902000000', '2', null, null);
INSERT INTO `areas` VALUES ('2610', '530900000000', '凤庆县', '530921000000', '2', null, null);
INSERT INTO `areas` VALUES ('2611', '530900000000', '云县', '530922000000', '2', null, null);
INSERT INTO `areas` VALUES ('2612', '530900000000', '永德县', '530923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2613', '530900000000', '镇康县', '530924000000', '2', null, null);
INSERT INTO `areas` VALUES ('2614', '530900000000', '双江拉祜族佤族布朗族傣族自治县', '530925000000', '2', null, null);
INSERT INTO `areas` VALUES ('2615', '530900000000', '耿马傣族佤族自治县', '530926000000', '2', null, null);
INSERT INTO `areas` VALUES ('2616', '530900000000', '沧源佤族自治县', '530927000000', '2', null, null);
INSERT INTO `areas` VALUES ('2617', '530600000000', '市辖区', '530601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2618', '530600000000', '昭阳区', '530602000000', '2', null, null);
INSERT INTO `areas` VALUES ('2619', '530600000000', '鲁甸县', '530621000000', '2', null, null);
INSERT INTO `areas` VALUES ('2620', '530600000000', '巧家县', '530622000000', '2', null, null);
INSERT INTO `areas` VALUES ('2621', '530600000000', '盐津县', '530623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2622', '530600000000', '大关县', '530624000000', '2', null, null);
INSERT INTO `areas` VALUES ('2623', '530600000000', '永善县', '530625000000', '2', null, null);
INSERT INTO `areas` VALUES ('2624', '530600000000', '绥江县', '530626000000', '2', null, null);
INSERT INTO `areas` VALUES ('2625', '530600000000', '镇雄县', '530627000000', '2', null, null);
INSERT INTO `areas` VALUES ('2626', '530600000000', '彝良县', '530628000000', '2', null, null);
INSERT INTO `areas` VALUES ('2627', '530600000000', '威信县', '530629000000', '2', null, null);
INSERT INTO `areas` VALUES ('2628', '530600000000', '水富市', '530681000000', '2', null, null);
INSERT INTO `areas` VALUES ('2629', '532600000000', '文山市', '532601000000', '2', null, null);
INSERT INTO `areas` VALUES ('2630', '532600000000', '砚山县', '532622000000', '2', null, null);
INSERT INTO `areas` VALUES ('2631', '532600000000', '西畴县', '532623000000', '2', null, null);
INSERT INTO `areas` VALUES ('2632', '532600000000', '麻栗坡县', '532624000000', '2', null, null);
INSERT INTO `areas` VALUES ('2633', '532600000000', '马关县', '532625000000', '2', null, null);
INSERT INTO `areas` VALUES ('2634', '532600000000', '丘北县', '532626000000', '2', null, null);
INSERT INTO `areas` VALUES ('2635', '532600000000', '广南县', '532627000000', '2', null, null);
INSERT INTO `areas` VALUES ('2636', '532600000000', '富宁县', '532628000000', '2', null, null);
INSERT INTO `areas` VALUES ('2637', '530800000000', '市辖区', '530801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2638', '530800000000', '思茅区', '530802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2639', '530800000000', '宁洱哈尼族彝族自治县', '530821000000', '2', null, null);
INSERT INTO `areas` VALUES ('2640', '530800000000', '墨江哈尼族自治县', '530822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2641', '530800000000', '景东彝族自治县', '530823000000', '2', null, null);
INSERT INTO `areas` VALUES ('2642', '530800000000', '景谷傣族彝族自治县', '530824000000', '2', null, null);
INSERT INTO `areas` VALUES ('2643', '530800000000', '镇沅彝族哈尼族拉祜族自治县', '530825000000', '2', null, null);
INSERT INTO `areas` VALUES ('2644', '530800000000', '江城哈尼族彝族自治县', '530826000000', '2', null, null);
INSERT INTO `areas` VALUES ('2645', '530800000000', '孟连傣族拉祜族佤族自治县', '530827000000', '2', null, null);
INSERT INTO `areas` VALUES ('2646', '530800000000', '澜沧拉祜族自治县', '530828000000', '2', null, null);
INSERT INTO `areas` VALUES ('2647', '530800000000', '西盟佤族自治县', '530829000000', '2', null, null);
INSERT INTO `areas` VALUES ('2648', '532500000000', '个旧市', '532501000000', '2', null, null);
INSERT INTO `areas` VALUES ('2649', '532500000000', '开远市', '532502000000', '2', null, null);
INSERT INTO `areas` VALUES ('2650', '532500000000', '蒙自市', '532503000000', '2', null, null);
INSERT INTO `areas` VALUES ('2651', '532500000000', '弥勒市', '532504000000', '2', null, null);
INSERT INTO `areas` VALUES ('2652', '532500000000', '屏边苗族自治县', '532523000000', '2', null, null);
INSERT INTO `areas` VALUES ('2653', '532500000000', '建水县', '532524000000', '2', null, null);
INSERT INTO `areas` VALUES ('2654', '532500000000', '石屏县', '532525000000', '2', null, null);
INSERT INTO `areas` VALUES ('2655', '532500000000', '泸西县', '532527000000', '2', null, null);
INSERT INTO `areas` VALUES ('2656', '532500000000', '元阳县', '532528000000', '2', null, null);
INSERT INTO `areas` VALUES ('2657', '532500000000', '红河县', '532529000000', '2', null, null);
INSERT INTO `areas` VALUES ('2658', '532500000000', '金平苗族瑶族傣族自治县', '532530000000', '2', null, null);
INSERT INTO `areas` VALUES ('2659', '532500000000', '绿春县', '532531000000', '2', null, null);
INSERT INTO `areas` VALUES ('2660', '532500000000', '河口瑶族自治县', '532532000000', '2', null, null);
INSERT INTO `areas` VALUES ('2661', '532800000000', '景洪市', '532801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2662', '532800000000', '勐海县', '532822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2663', '532800000000', '勐腊县', '532823000000', '2', null, null);
INSERT INTO `areas` VALUES ('2664', '532300000000', '楚雄市', '532301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2665', '532300000000', '双柏县', '532322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2666', '532300000000', '牟定县', '532323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2667', '532300000000', '南华县', '532324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2668', '532300000000', '姚安县', '532325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2669', '532300000000', '大姚县', '532326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2670', '532300000000', '永仁县', '532327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2671', '532300000000', '元谋县', '532328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2672', '532300000000', '武定县', '532329000000', '2', null, null);
INSERT INTO `areas` VALUES ('2673', '532300000000', '禄丰县', '532331000000', '2', null, null);
INSERT INTO `areas` VALUES ('2674', '533100000000', '瑞丽市', '533102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2675', '533100000000', '芒市', '533103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2676', '533100000000', '梁河县', '533122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2677', '533100000000', '盈江县', '533123000000', '2', null, null);
INSERT INTO `areas` VALUES ('2678', '533100000000', '陇川县', '533124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2679', '533300000000', '泸水市', '533301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2680', '533300000000', '福贡县', '533323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2681', '533300000000', '贡山独龙族怒族自治县', '533324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2682', '533300000000', '兰坪白族普米族自治县', '533325000000', '2', null, null);
INSERT INTO `areas` VALUES ('2683', '533400000000', '香格里拉市', '533401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2684', '533400000000', '德钦县', '533422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2685', '533400000000', '维西傈僳族自治县', '533423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2686', '610100000000', '市辖区', '610101000000', '2', null, null);
INSERT INTO `areas` VALUES ('2687', '610100000000', '新城区', '610102000000', '2', null, null);
INSERT INTO `areas` VALUES ('2688', '610100000000', '碑林区', '610103000000', '2', null, null);
INSERT INTO `areas` VALUES ('2689', '610100000000', '莲湖区', '610104000000', '2', null, null);
INSERT INTO `areas` VALUES ('2690', '610100000000', '灞桥区', '610111000000', '2', null, null);
INSERT INTO `areas` VALUES ('2691', '610100000000', '未央区', '610112000000', '2', null, null);
INSERT INTO `areas` VALUES ('2692', '610100000000', '雁塔区', '610113000000', '2', null, null);
INSERT INTO `areas` VALUES ('2693', '610100000000', '阎良区', '610114000000', '2', null, null);
INSERT INTO `areas` VALUES ('2694', '610100000000', '临潼区', '610115000000', '2', null, null);
INSERT INTO `areas` VALUES ('2695', '610100000000', '长安区', '610116000000', '2', null, null);
INSERT INTO `areas` VALUES ('2696', '610100000000', '高陵区', '610117000000', '2', null, null);
INSERT INTO `areas` VALUES ('2697', '610100000000', '鄠邑区', '610118000000', '2', null, null);
INSERT INTO `areas` VALUES ('2698', '610100000000', '蓝田县', '610122000000', '2', null, null);
INSERT INTO `areas` VALUES ('2699', '610100000000', '周至县', '610124000000', '2', null, null);
INSERT INTO `areas` VALUES ('2700', '532900000000', '大理市', '532901000000', '2', null, null);
INSERT INTO `areas` VALUES ('2701', '532900000000', '漾濞彝族自治县', '532922000000', '2', null, null);
INSERT INTO `areas` VALUES ('2702', '532900000000', '祥云县', '532923000000', '2', null, null);
INSERT INTO `areas` VALUES ('2703', '532900000000', '宾川县', '532924000000', '2', null, null);
INSERT INTO `areas` VALUES ('2704', '532900000000', '弥渡县', '532925000000', '2', null, null);
INSERT INTO `areas` VALUES ('2705', '532900000000', '南涧彝族自治县', '532926000000', '2', null, null);
INSERT INTO `areas` VALUES ('2706', '532900000000', '巍山彝族回族自治县', '532927000000', '2', null, null);
INSERT INTO `areas` VALUES ('2707', '532900000000', '永平县', '532928000000', '2', null, null);
INSERT INTO `areas` VALUES ('2708', '532900000000', '云龙县', '532929000000', '2', null, null);
INSERT INTO `areas` VALUES ('2709', '532900000000', '洱源县', '532930000000', '2', null, null);
INSERT INTO `areas` VALUES ('2710', '532900000000', '剑川县', '532931000000', '2', null, null);
INSERT INTO `areas` VALUES ('2711', '532900000000', '鹤庆县', '532932000000', '2', null, null);
INSERT INTO `areas` VALUES ('2712', '610300000000', '市辖区', '610301000000', '2', null, null);
INSERT INTO `areas` VALUES ('2713', '610300000000', '渭滨区', '610302000000', '2', null, null);
INSERT INTO `areas` VALUES ('2714', '610300000000', '金台区', '610303000000', '2', null, null);
INSERT INTO `areas` VALUES ('2715', '610300000000', '陈仓区', '610304000000', '2', null, null);
INSERT INTO `areas` VALUES ('2716', '610300000000', '凤翔县', '610322000000', '2', null, null);
INSERT INTO `areas` VALUES ('2717', '610300000000', '岐山县', '610323000000', '2', null, null);
INSERT INTO `areas` VALUES ('2718', '610300000000', '扶风县', '610324000000', '2', null, null);
INSERT INTO `areas` VALUES ('2719', '610300000000', '眉县', '610326000000', '2', null, null);
INSERT INTO `areas` VALUES ('2720', '610300000000', '陇县', '610327000000', '2', null, null);
INSERT INTO `areas` VALUES ('2721', '610300000000', '千阳县', '610328000000', '2', null, null);
INSERT INTO `areas` VALUES ('2722', '610300000000', '麟游县', '610329000000', '2', null, null);
INSERT INTO `areas` VALUES ('2723', '610300000000', '凤县', '610330000000', '2', null, null);
INSERT INTO `areas` VALUES ('2724', '610300000000', '太白县', '610331000000', '2', null, null);
INSERT INTO `areas` VALUES ('2725', '610400000000', '市辖区', '610401000000', '2', null, null);
INSERT INTO `areas` VALUES ('2726', '610400000000', '秦都区', '610402000000', '2', null, null);
INSERT INTO `areas` VALUES ('2727', '610400000000', '杨陵区', '610403000000', '2', null, null);
INSERT INTO `areas` VALUES ('2728', '610400000000', '渭城区', '610404000000', '2', null, null);
INSERT INTO `areas` VALUES ('2729', '610400000000', '三原县', '610422000000', '2', null, null);
INSERT INTO `areas` VALUES ('2730', '610400000000', '泾阳县', '610423000000', '2', null, null);
INSERT INTO `areas` VALUES ('2731', '610400000000', '乾县', '610424000000', '2', null, null);
INSERT INTO `areas` VALUES ('2732', '610400000000', '礼泉县', '610425000000', '2', null, null);
INSERT INTO `areas` VALUES ('2733', '610400000000', '永寿县', '610426000000', '2', null, null);
INSERT INTO `areas` VALUES ('2734', '610400000000', '长武县', '610428000000', '2', null, null);
INSERT INTO `areas` VALUES ('2735', '610400000000', '旬邑县', '610429000000', '2', null, null);
INSERT INTO `areas` VALUES ('2736', '610400000000', '淳化县', '610430000000', '2', null, null);
INSERT INTO `areas` VALUES ('2737', '610400000000', '武功县', '610431000000', '2', null, null);
INSERT INTO `areas` VALUES ('2738', '610400000000', '兴平市', '610481000000', '2', null, null);
INSERT INTO `areas` VALUES ('2739', '610400000000', '彬州市', '610482000000', '2', null, null);
INSERT INTO `areas` VALUES ('2740', '610700000000', '市辖区', '610701000000', '2', null, null);
INSERT INTO `areas` VALUES ('2741', '610700000000', '汉台区', '610702000000', '2', null, null);
INSERT INTO `areas` VALUES ('2742', '610700000000', '南郑区', '610703000000', '2', null, null);
INSERT INTO `areas` VALUES ('2743', '610700000000', '城固县', '610722000000', '2', null, null);
INSERT INTO `areas` VALUES ('2744', '610700000000', '洋县', '610723000000', '2', null, null);
INSERT INTO `areas` VALUES ('2745', '610700000000', '西乡县', '610724000000', '2', null, null);
INSERT INTO `areas` VALUES ('2746', '610700000000', '勉县', '610725000000', '2', null, null);
INSERT INTO `areas` VALUES ('2747', '610700000000', '宁强县', '610726000000', '2', null, null);
INSERT INTO `areas` VALUES ('2748', '610700000000', '略阳县', '610727000000', '2', null, null);
INSERT INTO `areas` VALUES ('2749', '610700000000', '镇巴县', '610728000000', '2', null, null);
INSERT INTO `areas` VALUES ('2750', '610700000000', '留坝县', '610729000000', '2', null, null);
INSERT INTO `areas` VALUES ('2751', '610700000000', '佛坪县', '610730000000', '2', null, null);
INSERT INTO `areas` VALUES ('2752', '610800000000', '市辖区', '610801000000', '2', null, null);
INSERT INTO `areas` VALUES ('2753', '610800000000', '榆阳区', '610802000000', '2', null, null);
INSERT INTO `areas` VALUES ('2754', '610800000000', '横山区', '610803000000', '2', null, null);
INSERT INTO `areas` VALUES ('2755', '610800000000', '府谷县', '610822000000', '2', null, null);
INSERT INTO `areas` VALUES ('2756', '610800000000', '靖边县', '610824000000', '2', null, null);
INSERT INTO `areas` VALUES ('2757', '610800000000', '定边县', '610825000000', '2', null, null);
INSERT INTO `areas` VALUES ('2758', '610800000000', '绥德县', '610826000000', '2', null, null);

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `ename` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '英文标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `click_num` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '点击数量',
  `is_sort` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES ('1', '关于我们', 'about', '<p>123关于我们</p>', '17', '0', '2020-11-05 20:09:14', '2020-11-15 20:31:33');
INSERT INTO `articles` VALUES ('2', '其他合作', 'cooperation', '<p>是</p>', '3', '0', '2020-11-05 20:40:12', '2020-11-09 13:35:49');
INSERT INTO `articles` VALUES ('3', '帮助中心', 'help', '<p>1</p>', '15', '0', '2020-11-05 20:40:28', '2020-11-23 15:10:57');
INSERT INTO `articles` VALUES ('4', '网站公告', 'notice', '<p>网站公告</p>', '5', '0', '2020-11-09 13:38:58', '2020-11-15 20:15:24');

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `sku_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Sku_ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `buy_num` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '购买数量',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of carts
-- ----------------------------

-- ----------------------------
-- Table structure for cashes
-- ----------------------------
DROP TABLE IF EXISTS `cashes`;
CREATE TABLE `cashes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '金额',
  `cash_status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '状态0 提现申请 1提现成功 2拒绝提现',
  `refuse_info` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '拒绝原因',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提现人名',
  `card_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '银行名',
  `remark` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cashes
-- ----------------------------
INSERT INTO `cashes` VALUES ('1', '8', '0', '100.00', '1', '', '李四', '43092115621102011145', '中国工商银行', '', '2020-10-06 13:44:00', '2020-11-08 21:14:48');
INSERT INTO `cashes` VALUES ('2', '8', '0', '100.00', '0', '我据说拒绝', '李四', '43092115621102011145', '中国工商银行', '', '2020-10-06 13:51:57', '2020-11-08 21:05:25');
INSERT INTO `cashes` VALUES ('3', '0', '2', '100.00', '0', '', '4阿斯顿', '', '', '', '2020-11-08 20:26:26', '2020-11-08 21:08:32');

-- ----------------------------
-- Table structure for chat_friends
-- ----------------------------
DROP TABLE IF EXISTS `chat_friends`;
CREATE TABLE `chat_friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of chat_friends
-- ----------------------------
INSERT INTO `chat_friends` VALUES ('3', '8', '2', '2020-11-19 17:01:19', '2020-11-19 17:01:19');

-- ----------------------------
-- Table structure for chat_msgs
-- ----------------------------
DROP TABLE IF EXISTS `chat_msgs`;
CREATE TABLE `chat_msgs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT '类型',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `user_read` tinyint(3) unsigned NOT NULL COMMENT '用户是否查看',
  `store_read` tinyint(3) unsigned NOT NULL COMMENT '店铺是否查看',
  `send_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '发送人0用户 1商家',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of chat_msgs
-- ----------------------------
INSERT INTO `chat_msgs` VALUES ('17', '8', '2', 'text', '1', '1', '1', '0', '2020-11-19 17:56:43', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('18', '8', '2', 'text', '1', '1', '1', '0', '2020-11-19 17:57:59', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('19', '8', '2', 'text', '1', '1', '1', '0', '2020-11-19 18:01:03', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('20', '8', '2', 'text', '12', '1', '1', '0', '2020-11-19 18:01:52', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('21', '8', '2', 'text', '123', '1', '1', '0', '2020-11-19 18:04:30', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('22', '8', '2', 'text', '1', '1', '1', '0', '2020-11-19 18:20:02', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('23', '8', '2', 'text', '3', '1', '1', '0', '2020-11-19 18:20:29', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('24', '8', '2', 'text', '2', '1', '1', '0', '2020-11-19 18:35:41', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('25', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-19/PEboCjb30DhlnsTag3DYcczilHUBRYNDU8ORTZ2z.jpg', '1', '1', '0', '2020-11-19 18:46:28', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('26', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-19/DDp0sxbg63oLiJc9I4ixl2yzSd5RE5USY8RVL3jE.jpg', '1', '1', '0', '2020-11-19 18:48:45', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('27', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-19/DPs33C8biUGPQ3o5d5d2PrYGgA5ecmZ9oOYZH2Zh.jpg', '1', '1', '0', '2020-11-19 18:50:28', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('28', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-19/j22Ko38bBMeoXctsJIDCNmVQIcY8iB8YakPPhbC5.png', '1', '1', '0', '2020-11-19 18:51:17', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('29', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-19/s0CEnwtTTXBnG2SNJRZYvQFLRtNQq5VqbWC2MOcd.png', '1', '1', '0', '2020-11-19 18:56:10', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('30', '8', '2', 'text', '1', '1', '1', '1', '2020-11-19 19:28:47', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('31', '8', '2', 'text', '1', '1', '1', '0', '2020-11-19 19:46:10', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('32', '8', '2', 'text', '12', '1', '1', '1', '2020-11-23 11:58:24', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('33', '8', '2', 'text', '222', '1', '1', '1', '2020-11-23 12:00:54', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('34', '8', '2', 'text', '333', '1', '1', '1', '2020-11-23 12:02:02', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('35', '8', '2', 'text', '1234', '1', '1', '1', '2020-11-23 12:03:29', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('36', '8', '2', 'text', '1', '1', '1', '0', '2020-11-23 12:08:11', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('37', '8', '2', 'text', '2', '1', '1', '1', '2020-11-23 12:09:48', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('38', '8', '2', 'text', '2', '1', '1', '1', '2020-11-23 12:11:40', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('39', '8', '2', 'text', '1', '1', '1', '1', '2020-11-23 12:12:21', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('40', '8', '2', 'text', '3', '1', '1', '0', '2020-11-23 12:14:21', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('41', '8', '2', 'text', '1', '1', '1', '1', '2020-11-23 12:16:19', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('42', '8', '2', 'text', '12', '1', '1', '1', '2020-11-23 12:16:43', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('43', '8', '2', 'text', '1', '1', '1', '1', '2020-11-23 12:18:53', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('44', '8', '2', 'text', '2', '1', '1', '1', '2020-11-23 12:20:07', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('45', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 12:20:18', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('46', '8', '2', 'text', '2', '1', '1', '1', '2020-11-23 12:31:23', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('47', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 12:31:31', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('48', '8', '2', 'text', '3', '1', '1', '0', '2020-11-23 12:32:27', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('49', '8', '2', 'text', '4', '1', '1', '0', '2020-11-23 12:34:37', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('50', '8', '2', 'text', '5', '1', '1', '0', '2020-11-23 12:36:26', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('51', '8', '2', 'text', '6', '1', '1', '0', '2020-11-23 12:36:35', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('52', '8', '2', 'text', '1', '1', '1', '0', '2020-11-23 12:37:55', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('53', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 12:39:12', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('54', '8', '2', 'text', '3', '1', '1', '0', '2020-11-23 12:40:51', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('55', '8', '2', 'text', '4', '1', '1', '1', '2020-11-23 12:41:02', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('56', '8', '2', 'text', '4', '1', '1', '0', '2020-11-23 12:43:46', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('57', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 12:44:07', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('58', '8', '2', 'text', '1', '1', '1', '1', '2020-11-23 12:44:12', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('59', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 12:50:52', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('60', '8', '2', 'text', '23', '1', '1', '0', '2020-11-23 12:51:40', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('61', '8', '2', 'text', '123', '1', '1', '1', '2020-11-23 12:51:47', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('62', '8', '2', 'text', '12', '1', '1', '0', '2020-11-23 13:02:28', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('63', '8', '2', 'text', '1', '1', '1', '0', '2020-11-23 13:03:31', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('64', '8', '2', 'text', '1', '1', '1', '0', '2020-11-23 13:03:58', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('65', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 13:04:21', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('66', '8', '2', 'image', 'http://127.0.0.1:8000/storage/chat/2020-11-23/0ihvOAFRUz2zf37UtafEG2aexKGu2bQuPeQZl80s.png', '1', '1', '1', '2020-11-23 13:13:37', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('67', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 14:12:43', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('68', '8', '2', 'text', '2', '1', '1', '0', '2020-11-23 14:13:01', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('69', '8', '2', 'text', '2', '0', '1', '0', '2020-11-23 14:16:01', '2020-11-23 14:16:26');
INSERT INTO `chat_msgs` VALUES ('70', '8', '2', 'text', '3', '0', '1', '0', '2020-11-23 14:16:10', '2020-11-23 14:16:26');

-- ----------------------------
-- Table structure for collectives
-- ----------------------------
DROP TABLE IF EXISTS `collectives`;
CREATE TABLE `collectives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `discount` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '折扣',
  `need` tinyint(3) unsigned NOT NULL DEFAULT 5 COMMENT '需要人数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of collectives
-- ----------------------------
INSERT INTO `collectives` VALUES ('1', '2', '16', '20.00', '3', '2020-11-16 18:21:57', '2020-11-16 18:21:57');
INSERT INTO `collectives` VALUES ('2', '2', '17', '15.00', '2', '2020-11-16 21:37:39', '2020-11-16 21:37:39');

-- ----------------------------
-- Table structure for collective_logs
-- ----------------------------
DROP TABLE IF EXISTS `collective_logs`;
CREATE TABLE `collective_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `collective_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '团ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `discount` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '折扣',
  `need` tinyint(3) unsigned NOT NULL DEFAULT 5 COMMENT '需要人数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 2 COMMENT '状态 0取消 1完成  2 拼团中',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of collective_logs
-- ----------------------------
INSERT INTO `collective_logs` VALUES ('1', '2', '1', '2', '17', '15.00', '2', '2', '2020-11-16 23:44:46', '2020-11-16 23:44:46');
INSERT INTO `collective_logs` VALUES ('2', '1', '1', '2', '16', '20.00', '3', '2', '2020-11-18 13:20:46', '2020-11-18 13:20:46');

-- ----------------------------
-- Table structure for configs
-- ----------------------------
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `val` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '值',
  `content` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of configs
-- ----------------------------
INSERT INTO `configs` VALUES ('1', 'web_name', '青梧商城', '网站名');
INSERT INTO `configs` VALUES ('2', 'title', '青梧商城-B2B2C', '网站标题');
INSERT INTO `configs` VALUES ('3', 'keywords', '青梧商城,B2B2C,laravel,vue', '关键字');
INSERT INTO `configs` VALUES ('4', 'description', '青梧商城,电商开发平台', '网站描述');
INSERT INTO `configs` VALUES ('5', 'logo', '', '网站Logo');
INSERT INTO `configs` VALUES ('6', 'icon', '', '游览器Logo');
INSERT INTO `configs` VALUES ('7', 'mobile', '', '联系电话');
INSERT INTO `configs` VALUES ('8', 'email', 'bishashiwo@gmail.com', '邮箱');
INSERT INTO `configs` VALUES ('9', 'icp', '', '备案信息');
INSERT INTO `configs` VALUES ('10', 'web_status', '1', '网站状态 0关 1开');
INSERT INTO `configs` VALUES ('11', 'web_close_info', '网站维护中...', '网站关闭原因');
INSERT INTO `configs` VALUES ('12', 'ali_pay', '{\"ali_h5\":{\"app_id\":null,\"public_key\":null,\"private_key\":null,\"return_url\":null,\"notify_url\":null},\"ali_app\":{\"app_id\":null,\"public_key\":null,\"private_key\":null,\"return_url\":null,\"notify_url\":null},\"ali_mini\":{\"app_id\":null,\"public_key\":null,\"private_key\":null,\"return_url\":null,\"notify_url\":null},\"ali_scan\":{\"app_id\":\"2016091900544672\",\"public_key\":null,\"private_key\":\"MIIEowIBAAKCAQEAqMfEF9QVEOW4+7dNJCY674IHjllPVFLUtP\\/r72qL37XpDlX\\/hutQQKOqRtaqBtoEiWEwnkvzvntUhbvNy6PDQfw5CGSIKfKV2UsIEnTXeoY9byizhkvmPedGSBEjo6622fwzdfu5uHRPbrwedNFcGKfb2PPgNeb3KUCJFYh5aHk\\/8WPpJBo\\/zJW29iN0WznqXiOWsuADCFNKQuzEvSzyxpiyZErJ2k+xptmX1S5MsfsENHVfLc\\/IY8VXQhle\\/UG+ccoTASUGyI8Dq2QV76XMDotaheOdX7iUOmtW6px\\/Rjk9qbzIfobbvlRDboJYnTiBXA+7Qh7Jr8ZMY2+L1ccHHQIDAQABAoIBADisS2YM6r3vxTuQgzCePa\\/qo4ri5kZUQeQ9AwYzaqBFvun3aoPtQ9dQnX6H2jPOxTM0FVUx\\/7h4RRtawnGcnZXskWIGD8q2ECcUkLNR1IDpznsi2ZVAqyEiJXFRf\\/wYUIHFs6nsSqJrO7jdJgTnv9rvkly5FxJCab\\/KUb2PQ+UWM76tGfhEjxv9+ONBuWxDHr6mHLQn2MK\\/CM\\/WB0qNDEMSVz4AqQ\\/hx0ysZZWBAZaOPN5V9FRV+bWhpo\\/O9HB18TaKNtRdAfMvsy2o70NLi\\/DZDjPsQ1dtIVTjBE5j\\/ikn6Qpm6P\\/tBCw\\/8hWukbO7Aktg+1YLakHq\\/110xnM4VmkCgYEA+VizDa7D1+1jzveS7Ca2\\/44dw3eGnYEz+DUxigeQO6nY2RIkUEhOg5MvdezJsN1P9GIGS3JG9QJCCmKPpWdHRnSTaz8H04sKeWlObOs\\/pgWyXq1g2WZWQZ77CUB7LcgChQTltPBPoUKeuW3Cj8GOG3xvEhgXoW5sGMhgOyMcVkcCgYEArUi25iUb3\\/F4QOd6Kkdlur825sXBJ2xGe6TQP8BhbE86\\/oUU34RuNz0PGwWnvidb5JwS9S4wCmkU+AbpC1+9cd1Mdpb08heY\\/KqYi9CxE0EGOYN3fWksbc4tuXD8BBKJPq2K1uPtzHEZKW2ysVsEfC52ZpZNC8MAVPbj\\/otcVXsCgYEA95ipa\\/vPgvm6M0lJvm+PJeLEEVX9+SbUKTMA9zQdd\\/FnX92Q65txQ1wDM9EmUhbhDyXLZF2csixE0bUfOLp+XyrhPAyBxAD8LKVx99v9\\/ukHPtZhJl9lZZHtazl5V3OVdOrxAiPinpndrPmdykwDxa1hSZFc3bMdadqHHBH4UH8CgYBbSfmBGX+r5OxzVP9ZErAXR\\/FGdlwhxrsDHHrReypYAw2TVM0ATCY8V6CDneEXHmkc8NRT8ndApMd1Oz6+zTtipzFHMJPujlv8kGs5DUKcYB9FYWsr2KKdXodcMJe0FYfUS1zfhMicceDNoIsJQGgGe\\/vIY9pHHf4oxMCsMwL7zQKBgGxePvJuaQRklq7m\\/IB4+htsOBR457HTXJnskpVfUBR5ZJGq8fq4ilO\\/Ej3F+Yi\\/AA6HrarM7xB5LV7e97HpHATJs6HcY+pMOdilEDPZ2CDBCR1CiJ9+pOUCyviIVDs7ePZFKqqnwf8HiJbQwTp6ADMWJp6WzRfriucaO9WW9S5I\",\"return_url\":\"http:\\/\\/app.qingwuit.com\\/api\\/payment\\/alipay\",\"notify_url\":\"http:\\/\\/app.qingwuit.com\\/api\\/payment\\/alipay\",\"ali_public_key\":\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA+z64yozEqXS9GvcT7e758dPTt8qJ2+12wcpioEhaBbfqvowcZjUV9BPL1EgtJie5SNK9rFDFO\\/POWVlzj8BVD0viUteNe2amPRhZLxzT3HXn9VJH+dTwOEgZ1MSrDisxOi7\\/NCTF42zQl6\\/w2lK2FQI\\/c1kIoN91ivWlPqX3BbXG54hyngu5T9j2AimEhKWMhq6sL2gVoNILbJDLDN85AOfoGb1DZ59dFSez\\/GtssO0KXGqfc1R1AggmisksQOKAXSiSHejEHvU\\/Zx4lIvpCWEwBXx7qWbOD2MXWmsrn6W41f4AJYoJxQ2QRUkFlKpstAgVfhNMR6A+W1p7bh647GQIDAQAB\"}}', '阿里云支付配置');
INSERT INTO `configs` VALUES ('13', 'alioss', '{\"status\":false,\"access_id\":\"LTAI4F\",\"access_key\":\"i5Y8PA\",\"bucket\":\"qwshop\",\"endpoint\":\"oss-cn-qingdao.aliyuncs.com\",\"cdnDomain\":null,\"ssl\":false,\"isCName\":false}', '阿里云OSS');
INSERT INTO `configs` VALUES ('14', 'alisms', '{\"key\":\"xxx\",\"secret\":\"xxx\"}', '阿里云sms');
INSERT INTO `configs` VALUES ('15', 'amap_ak', '', '高德地图密钥');
INSERT INTO `configs` VALUES ('16', 'wechat_public', '', '微信公众号配置');
INSERT INTO `configs` VALUES ('17', 'wechat_pay', '{\"wechat_h5\":{\"app_id\":null,\"app_secret\":null,\"mch_id\":null,\"key\":null,\"notify_url\":null},\"wechat_public\":{\"app_id\":null,\"app_secret\":null,\"mch_id\":null,\"key\":null,\"notify_url\":null},\"wechat_app\":{\"app_id\":null,\"app_secret\":null,\"mch_id\":null,\"key\":null,\"notify_url\":null},\"wechat_mini\":{\"app_id\":null,\"app_secret\":null,\"mch_id\":null,\"key\":null,\"notify_url\":null},\"wechat_scan\":{\"app_id\":null,\"app_secret\":null,\"mch_id\":null,\"key\":null,\"notify_url\":null}}', '微信支付配置');
INSERT INTO `configs` VALUES ('18', 'paypal', '', 'paypal支付配置');
INSERT INTO `configs` VALUES ('19', 'oauth', '{\"weixinweb\":{\"client_id\":\"123\",\"client_secret\":\"456\",\"redirect\":\"11112\"}}', '第三方登陆配置');
INSERT INTO `configs` VALUES ('20', 'goods_verify', '0', '商品审核');
INSERT INTO `configs` VALUES ('21', 'cash_rate', '0', '提现手续费');
INSERT INTO `configs` VALUES ('22', 'kuaibao', '{\"app_id\":\"106\",\"app_key\":\"c3b848153db\"}', '快宝物流配置');
INSERT INTO `configs` VALUES ('23', 'task', '{\"confirm\":\"5\",\"cancel\":\"1\",\"settlement\":\"7\"}', '自动任务配置');
INSERT INTO `configs` VALUES ('24', 'integral', '{\"login\":{\"status\":true,\"integral\":1},\"register\":{\"status\":true,\"integral\":\"5\"},\"inviter\":{\"status\":false,\"integral\":1},\"order\":{\"status\":false,\"integral\":1}}', '');

-- ----------------------------
-- Table structure for coupons
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `money` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '优惠券金额',
  `use_money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '允许使用金额',
  `stock` int(10) unsigned NOT NULL DEFAULT 10 COMMENT '优惠券数量',
  `content` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '优惠券描述',
  `start_time` timestamp NOT NULL DEFAULT '2020-11-15 15:15:33' COMMENT '开始时间',
  `end_time` timestamp NOT NULL DEFAULT '2020-11-20 15:15:33' COMMENT '结束',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of coupons
-- ----------------------------
INSERT INTO `coupons` VALUES ('1', '2', '第一张优惠券', '10.00', '50.00', '100', '', '2020-11-15 04:32:45', '2021-01-16 04:32:45', '2020-11-15 16:33:21', '2020-11-15 19:26:05');
INSERT INTO `coupons` VALUES ('2', '2', '第二张优惠券', '100.00', '500.00', '10', '', '2020-11-10 06:52:00', '2021-01-19 06:52:00', '2020-11-15 18:52:29', '2020-11-15 19:26:21');
INSERT INTO `coupons` VALUES ('3', '2', '第三张优惠券', '200.00', '800.00', '99', '', '2020-11-15 06:52:30', '2021-12-01 06:52:30', '2020-11-15 18:52:51', '2020-11-16 13:49:16');

-- ----------------------------
-- Table structure for coupon_logs
-- ----------------------------
DROP TABLE IF EXISTS `coupon_logs`;
CREATE TABLE `coupon_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '优惠券' COMMENT '标题',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '优惠券ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '分销金额',
  `use_money` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '分佣率',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '状态 0未使用 1使用',
  `start_time` timestamp NOT NULL DEFAULT '2020-11-15 19:18:47' COMMENT '开始时间',
  `end_time` timestamp NOT NULL DEFAULT '2020-11-20 19:18:47' COMMENT '结束',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of coupon_logs
-- ----------------------------
INSERT INTO `coupon_logs` VALUES ('1', '第一张优惠券', '1', '1', '2', '38', '10.00', '50.00', '1', '2021-01-08 04:32:45', '2021-01-22 04:32:45', '2020-11-15 19:18:52', '2020-11-15 23:32:03');
INSERT INTO `coupon_logs` VALUES ('3', '第三张优惠券', '3', '1', '2', '0', '200.00', '800.00', '0', '2021-01-08 06:52:30', '2021-01-22 06:52:30', '2020-11-16 13:49:16', '2020-11-16 13:49:16');

-- ----------------------------
-- Table structure for distributions
-- ----------------------------
DROP TABLE IF EXISTS `distributions`;
CREATE TABLE `distributions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `lev_1` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '一级分销',
  `lev_2` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '二级分销',
  `lev_3` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '三级分销',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of distributions
-- ----------------------------
INSERT INTO `distributions` VALUES ('1', '2', '17', '0.50', '0.30', '0.20', '2020-11-07 21:26:36', '2020-11-07 21:26:36');
INSERT INTO `distributions` VALUES ('2', '2', '16', '0.30', '0.20', '0.10', '2020-11-07 21:27:12', '2020-11-07 21:27:12');

-- ----------------------------
-- Table structure for distribution_logs
-- ----------------------------
DROP TABLE IF EXISTS `distribution_logs`;
CREATE TABLE `distribution_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '分销',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `distribution_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '分销活动ID',
  `order_goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单商品ID',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '分销金额',
  `lev` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '分佣率',
  `commission` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '分佣金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '处理结果 0 等待分佣 1 分佣',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of distribution_logs
-- ----------------------------
INSERT INTO `distribution_logs` VALUES ('6', '一级分销', '1', '2', '28', '1', '26', '1.00', '0.50', '0.50', '1', '2020-11-07 21:52:04', '2020-11-09 14:57:08');
INSERT INTO `distribution_logs` VALUES ('7', '一级分销', '1', '2', '28', '2', '27', '1.00', '0.30', '0.30', '1', '2020-11-07 21:52:04', '2020-11-09 14:57:08');
INSERT INTO `distribution_logs` VALUES ('8', '一级分销', '1', '2', '28', '1', '26', '1.00', '0.50', '0.50', '1', '2020-11-07 21:52:04', '2020-11-09 14:57:08');

-- ----------------------------
-- Table structure for expresses
-- ----------------------------
DROP TABLE IF EXISTS `expresses`;
CREATE TABLE `expresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递公司名',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递编码',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of expresses
-- ----------------------------
INSERT INTO `expresses` VALUES ('1', '韵达快递', 'yd', null, null);
INSERT INTO `expresses` VALUES ('2', '申通快递', 'sto', null, null);
INSERT INTO `expresses` VALUES ('3', '圆通快递', 'yt', null, null);
INSERT INTO `expresses` VALUES ('4', '中通快递', 'zt', null, null);
INSERT INTO `expresses` VALUES ('5', '极兔快递', 'jt', null, null);
INSERT INTO `expresses` VALUES ('6', '百世快递', 'ht', null, null);
INSERT INTO `expresses` VALUES ('7', '邮政小包', 'postx', null, null);
INSERT INTO `expresses` VALUES ('8', 'EMS包裹', 'ems', null, null);
INSERT INTO `expresses` VALUES ('9', '邮政包裹', 'post', null, null);
INSERT INTO `expresses` VALUES ('10', '天天快递', 'tt', null, null);
INSERT INTO `expresses` VALUES ('11', '百世快运', 'best', null, null);
INSERT INTO `expresses` VALUES ('12', '优速快递', 'ys', null, null);
INSERT INTO `expresses` VALUES ('13', '德邦', 'dp', null, null);
INSERT INTO `expresses` VALUES ('14', '宅急送', 'zjs', null, null);
INSERT INTO `expresses` VALUES ('15', '京东', 'jd', null, null);
INSERT INTO `expresses` VALUES ('16', '中通快运', 'ztky', null, null);
INSERT INTO `expresses` VALUES ('17', '顺丰速运', 'sf', null, null);
INSERT INTO `expresses` VALUES ('18', '国通快递', 'gt', null, null);

-- ----------------------------
-- Table structure for favorites
-- ----------------------------
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `out_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '外部ID',
  `is_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '类型 0 收藏商品 1关注店铺',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of favorites
-- ----------------------------
INSERT INTO `favorites` VALUES ('6', '1', '2', '1', '2021-01-18 15:12:59', '2021-01-18 15:12:59');
INSERT INTO `favorites` VALUES ('10', '1', '16', '0', '2021-01-18 15:29:11', '2021-01-18 15:29:11');

-- ----------------------------
-- Table structure for freights
-- ----------------------------
DROP TABLE IF EXISTS `freights`;
CREATE TABLE `freights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '默认运费' COMMENT '标题',
  `f_weight` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '首重',
  `f_price` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '首重运费',
  `o_weight` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '超出重量每多少',
  `o_price` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '超出重量每次多少钱',
  `area_id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容ID',
  `area_name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容中文',
  `is_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0默认运费 1 配置运费',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of freights
-- ----------------------------
INSERT INTO `freights` VALUES ('8', '2', '默认运费', '0.00', '10.00', '0.00', '0.00', '', '', '0', '2020-11-06 16:21:52', '2020-11-06 19:20:14');
INSERT INTO `freights` VALUES ('9', '2', '自定义运费模版', '1.00', '10.00', '1.00', '5.00', '', '', '1', '2020-11-06 16:21:52', '2020-11-06 19:20:14');
INSERT INTO `freights` VALUES ('11', '2', '自定义运费模版3', '1.00', '10.00', '1.00', '5.00', '12', '', '1', '2020-11-06 16:22:06', '2020-11-06 19:20:14');

-- ----------------------------
-- Table structure for full_reductions
-- ----------------------------
DROP TABLE IF EXISTS `full_reductions`;
CREATE TABLE `full_reductions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `money` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '满减金额',
  `use_money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '满足金额',
  `start_time` timestamp NOT NULL DEFAULT '2020-11-16 10:51:34' COMMENT '开始时间',
  `end_time` timestamp NOT NULL DEFAULT '2020-11-21 10:51:34' COMMENT '结束',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of full_reductions
-- ----------------------------
INSERT INTO `full_reductions` VALUES ('2', '2', '12312', '10.00', '40.00', '2020-11-08 11:50:20', '2020-11-09 11:50:20', '2020-11-16 11:50:33', '2020-11-16 12:51:54');

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '品牌ID',
  `class_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '栏目ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商家ID',
  `goods_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_subname` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品副标题',
  `goods_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品编号',
  `goods_images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品图片',
  `goods_master_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图',
  `goods_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品价格',
  `goods_market_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '市场价格',
  `goods_stock` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '库存',
  `goods_weight` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品重量',
  `goods_sale` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '销售量',
  `goods_collect` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '收藏量',
  `goods_status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '上架状态',
  `freight_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '运费模版',
  `refuse_info` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goods_verify` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '审核状态',
  `goods_content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详情',
  `goods_content_mobile` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机端详情',
  `is_recommend` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否推荐商家首页',
  `is_matser` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否推荐主站首页',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('16', '2', '29', '2', '标题', '', '', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH.jpg', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH.jpg', '1.00', '2.00', '0', '3.00', '2', '0', '1', '0', null, '1', '<p>1</p>', '', '0', '1', '2020-08-02 18:45:08', '2021-01-14 14:30:13');
INSERT INTO `goods` VALUES ('17', '1', '26', '2', '品标题品标题品标题品标题品标题', '品标题品标题', '', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD.jpg,http://127.0.0.1:8000/storage/goods/2020-08-21/qmWgpiMNk2KI9yXyzmaTC9WcRaAX2Quqjpg3iB6f.jpg', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD.jpg', '0.00', '0.00', '0', '0.00', '2', '0', '1', '11', null, '1', '', '', '0', '1', '2020-08-02 18:45:45', '2020-11-15 21:34:21');

-- ----------------------------
-- Table structure for goods_attrs
-- ----------------------------
DROP TABLE IF EXISTS `goods_attrs`;
CREATE TABLE `goods_attrs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '所属店铺',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '属性名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods_attrs
-- ----------------------------
INSERT INTO `goods_attrs` VALUES ('3', '0', '大师', '2020-07-06 15:55:11', '2020-07-06 15:55:11');
INSERT INTO `goods_attrs` VALUES ('4', '1', '颜色', '2020-07-16 18:11:10', '2020-07-16 18:11:10');
INSERT INTO `goods_attrs` VALUES ('5', '1', '大小', '2020-07-16 18:11:30', '2020-07-16 18:11:30');
INSERT INTO `goods_attrs` VALUES ('6', '2', '颜色', '2020-07-29 11:56:00', '2020-07-29 11:56:00');
INSERT INTO `goods_attrs` VALUES ('7', '2', '大小', '2020-07-29 11:56:20', '2020-07-29 11:56:20');

-- ----------------------------
-- Table structure for goods_brands
-- ----------------------------
DROP TABLE IF EXISTS `goods_brands`;
CREATE TABLE `goods_brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thumb` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '品牌名称',
  `is_sort` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods_brands
-- ----------------------------
INSERT INTO `goods_brands` VALUES ('1', '', '华为', '0', '2020-07-27 14:17:14', '2020-07-27 14:17:07');
INSERT INTO `goods_brands` VALUES ('2', 'http://127.0.0.1:8000/storage/goods_brand/2020-08-23/K7ZXnYJhBgj8ZzHYNAetw6cq70OpPhXpvFZsDwIF.png', '小米', '0', '2020-07-27 14:17:18', '2020-08-23 16:25:41');

-- ----------------------------
-- Table structure for goods_classes
-- ----------------------------
DROP TABLE IF EXISTS `goods_classes`;
CREATE TABLE `goods_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `thumb` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `is_sort` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods_classes
-- ----------------------------
INSERT INTO `goods_classes` VALUES ('2', '0', '', '家用电器', '0', '2020-08-02 14:58:08', '2020-08-02 14:58:08');
INSERT INTO `goods_classes` VALUES ('3', '0', '', '图书 & 音像 & 电子书', '1', '2020-08-02 14:58:15', '2020-08-02 14:59:12');
INSERT INTO `goods_classes` VALUES ('4', '0', '', '手机 & 数码 & 通信', '2', '2020-08-02 14:58:22', '2020-08-02 14:59:16');
INSERT INTO `goods_classes` VALUES ('5', '0', '', '电脑 & 办公', '3', '2020-08-02 14:58:28', '2020-08-02 14:59:20');
INSERT INTO `goods_classes` VALUES ('6', '0', '', '家居 & 家具 & 家装', '4', '2020-08-02 14:58:34', '2020-08-02 14:59:22');
INSERT INTO `goods_classes` VALUES ('7', '0', '', '男装 & 女装 & 内衣', '5', '2020-08-02 14:58:42', '2020-08-02 14:59:25');
INSERT INTO `goods_classes` VALUES ('8', '2', '', '大家电', '0', '2020-08-02 14:58:57', '2020-08-02 14:58:57');
INSERT INTO `goods_classes` VALUES ('9', '2', '', '生活电器', '0', '2020-08-02 14:59:39', '2020-08-02 14:59:39');
INSERT INTO `goods_classes` VALUES ('10', '2', '', '节能电器', '0', '2020-08-02 14:59:47', '2020-08-02 14:59:47');
INSERT INTO `goods_classes` VALUES ('11', '3', '', '图书', '0', '2020-08-02 14:59:56', '2020-08-02 14:59:56');
INSERT INTO `goods_classes` VALUES ('12', '3', '', '磁带', '0', '2020-08-02 15:00:10', '2020-08-02 15:00:10');
INSERT INTO `goods_classes` VALUES ('13', '3', '', '对讲机', '0', '2020-08-02 15:00:18', '2020-08-02 15:00:18');
INSERT INTO `goods_classes` VALUES ('14', '4', '', '摄影机', '0', '2020-08-02 15:00:26', '2020-08-02 15:00:26');
INSERT INTO `goods_classes` VALUES ('15', '4', '', '手机', '0', '2020-08-02 15:00:37', '2020-08-02 15:00:37');
INSERT INTO `goods_classes` VALUES ('16', '4', '', '相机', '0', '2020-08-02 15:00:46', '2020-08-02 15:00:46');
INSERT INTO `goods_classes` VALUES ('17', '5', '', '方舟子', '0', '2020-08-02 15:00:56', '2020-08-02 15:00:56');
INSERT INTO `goods_classes` VALUES ('18', '5', '', '神州', '0', '2020-08-02 15:01:07', '2020-08-02 15:01:07');
INSERT INTO `goods_classes` VALUES ('19', '5', '', '戴尔', '0', '2020-08-02 15:01:17', '2020-08-02 15:01:17');
INSERT INTO `goods_classes` VALUES ('20', '6', '', '美的橱柜', '0', '2020-08-02 15:01:28', '2020-08-02 15:01:28');
INSERT INTO `goods_classes` VALUES ('21', '6', '', '日本沙发', '0', '2020-08-02 15:01:38', '2020-08-02 15:01:38');
INSERT INTO `goods_classes` VALUES ('22', '6', '', '真皮沙发', '0', '2020-08-02 15:01:47', '2020-08-02 15:01:47');
INSERT INTO `goods_classes` VALUES ('23', '7', '', '披风大衣', '0', '2020-08-02 15:01:56', '2020-08-02 15:01:56');
INSERT INTO `goods_classes` VALUES ('24', '7', '', '男装', '0', '2020-08-02 15:02:05', '2020-08-02 15:02:05');
INSERT INTO `goods_classes` VALUES ('25', '7', '', '孕妇装', '0', '2020-08-02 15:02:13', '2020-08-02 15:02:13');
INSERT INTO `goods_classes` VALUES ('26', '8', '', '空调', '0', '2020-08-02 17:19:23', '2020-08-02 18:17:04');
INSERT INTO `goods_classes` VALUES ('27', '8', '', '平板电视', '0', '2020-08-02 17:19:41', '2020-08-02 18:17:23');
INSERT INTO `goods_classes` VALUES ('28', '8', '', '洗衣机', '0', '2020-08-02 17:19:57', '2020-08-02 17:19:57');
INSERT INTO `goods_classes` VALUES ('29', '11', '', '童话', '0', '2020-08-02 18:17:45', '2020-08-02 18:17:45');
INSERT INTO `goods_classes` VALUES ('30', '16', '', '索尼', '0', '2020-08-02 18:27:30', '2020-08-02 18:27:30');
INSERT INTO `goods_classes` VALUES ('31', '18', '', '台式', '0', '2020-08-02 18:27:42', '2020-08-02 18:27:42');
INSERT INTO `goods_classes` VALUES ('32', '20', '', '双门', '0', '2020-08-02 18:28:04', '2020-08-02 18:28:04');
INSERT INTO `goods_classes` VALUES ('33', '24', '', '美系', '0', '2020-08-02 18:28:18', '2020-08-02 18:28:18');

-- ----------------------------
-- Table structure for goods_skus
-- ----------------------------
DROP TABLE IF EXISTS `goods_skus`;
CREATE TABLE `goods_skus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `spec_id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '属性ID',
  `sku_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SKU名称',
  `goods_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图',
  `goods_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品价格',
  `goods_market_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '市场价格',
  `goods_stock` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '库存',
  `goods_weight` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品重量',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods_skus
-- ----------------------------
INSERT INTO `goods_skus` VALUES ('19', '17', '13', '红色', '', '1.00', '0.00', '9', '0.10', '2020-08-02 18:45:45', '2021-01-14 14:39:36');
INSERT INTO `goods_skus` VALUES ('20', '17', '14', '白色', '', '2.00', '0.00', '46', '0.20', '2020-08-02 18:45:45', '2020-11-15 21:34:21');
INSERT INTO `goods_skus` VALUES ('21', '17', '15', '黑色', '', '50.00', '10.00', '53', '0.00', '2020-08-02 18:45:45', '2020-11-16 13:50:12');

-- ----------------------------
-- Table structure for goods_specs
-- ----------------------------
DROP TABLE IF EXISTS `goods_specs`;
CREATE TABLE `goods_specs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '所属属性',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规格名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of goods_specs
-- ----------------------------
INSERT INTO `goods_specs` VALUES ('1', '3', '0', '消息', '2020-07-06 16:02:12', '2020-07-06 16:02:12');
INSERT INTO `goods_specs` VALUES ('2', '3', '0', '对象', '2020-07-06 16:02:12', '2020-07-06 16:02:12');
INSERT INTO `goods_specs` VALUES ('5', '3', '0', '压缩', '2020-07-06 16:02:41', '2020-07-06 16:02:41');
INSERT INTO `goods_specs` VALUES ('6', '4', '0', '红色', '2020-07-16 18:11:10', '2020-07-16 18:11:10');
INSERT INTO `goods_specs` VALUES ('7', '4', '0', '白色', '2020-07-16 18:11:11', '2020-07-16 18:11:11');
INSERT INTO `goods_specs` VALUES ('8', '4', '0', '黑色', '2020-07-16 18:11:11', '2020-07-16 18:11:11');
INSERT INTO `goods_specs` VALUES ('9', '4', '0', '绿色', '2020-07-16 18:11:11', '2020-07-16 18:11:11');
INSERT INTO `goods_specs` VALUES ('10', '5', '0', 'x', '2020-07-16 18:11:30', '2020-07-16 18:11:30');
INSERT INTO `goods_specs` VALUES ('11', '5', '0', 'xl', '2020-07-16 18:11:30', '2020-07-16 18:11:30');
INSERT INTO `goods_specs` VALUES ('12', '5', '0', 'xxl', '2020-07-16 18:11:30', '2020-07-16 18:11:30');
INSERT INTO `goods_specs` VALUES ('13', '6', '0', '红色', '2020-07-29 11:56:01', '2020-07-29 11:56:01');
INSERT INTO `goods_specs` VALUES ('14', '6', '0', '白色', '2020-07-29 11:56:01', '2020-07-29 11:56:01');
INSERT INTO `goods_specs` VALUES ('15', '6', '0', '黑色', '2020-07-29 11:56:01', '2020-07-29 11:56:01');
INSERT INTO `goods_specs` VALUES ('16', '7', '0', 'XL', '2020-07-29 11:56:20', '2020-07-29 11:56:20');
INSERT INTO `goods_specs` VALUES ('17', '7', '0', 'XXL', '2020-07-29 11:56:20', '2020-07-29 11:56:20');
INSERT INTO `goods_specs` VALUES ('18', '7', '0', 'M', '2020-07-29 11:56:20', '2020-07-29 11:56:20');

-- ----------------------------
-- Table structure for integral_goods
-- ----------------------------
DROP TABLE IF EXISTS `integral_goods`;
CREATE TABLE `integral_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '栏目ID',
  `goods_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名',
  `goods_subname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '副标题',
  `goods_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '积分',
  `goods_market_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '市场金额',
  `goods_stock` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '库存',
  `goods_images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品图片',
  `goods_master_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图',
  `goods_sale` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '销售量',
  `goods_status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '上下架',
  `is_recommend` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '推荐',
  `goods_content` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详情',
  `goods_content_mobile` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机端详情',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of integral_goods
-- ----------------------------
INSERT INTO `integral_goods` VALUES ('1', '1', '123', '4444', '10.00', '100.00', '98', 'http://127.0.0.1:8000/storage/integrals/0/2020-11-09/HgTyg2LMUlc6Q48puimpxkISyQtNmtCixZbpZDDG.png', 'http://127.0.0.1:8000/storage/integrals/0/2020-11-09/HgTyg2LMUlc6Q48puimpxkISyQtNmtCixZbpZDDG.png', '0', '1', '1', '<p>eqw</p>', '<p>fvcvv</p>', '2020-11-09 19:21:46', '2020-11-10 19:27:12');

-- ----------------------------
-- Table structure for integral_goods_classes
-- ----------------------------
DROP TABLE IF EXISTS `integral_goods_classes`;
CREATE TABLE `integral_goods_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '积分栏目',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of integral_goods_classes
-- ----------------------------
INSERT INTO `integral_goods_classes` VALUES ('1', '二次元手办', '2020-11-09 15:20:27', '2020-11-09 15:20:58');
INSERT INTO `integral_goods_classes` VALUES ('2', '腾讯TGB', '2020-11-10 18:29:09', '2020-11-10 18:29:09');

-- ----------------------------
-- Table structure for integral_orders
-- ----------------------------
DROP TABLE IF EXISTS `integral_orders`;
CREATE TABLE `integral_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `order_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单标题',
  `order_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单图片',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总支付金额',
  `order_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '订单计算金额',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '订单支付 0 取消 1 等待支付 2订单完成',
  `delivery_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递订单号',
  `delivery_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递公司编码',
  `receive_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人名',
  `receive_tel` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人手机',
  `receive_area` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址信息',
  `receive_address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址信息',
  `pay_time` timestamp NOT NULL DEFAULT '2020-11-09 14:28:05' COMMENT '支付时间',
  `delivery_time` timestamp NOT NULL DEFAULT '2020-11-09 14:28:05' COMMENT '发货时间',
  `remark` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of integral_orders
-- ----------------------------
INSERT INTO `integral_orders` VALUES ('2', '1', '2020111019271219174', '123', 'http://127.0.0.1:8000/storage/integrals/0/2020-11-09/HgTyg2LMUlc6Q48puimpxkISyQtNmtCixZbpZDDG_150.png', '20.00', '20.00', '6', '2222222222', 'yd', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '2020-11-09 14:28:05', '2020-11-09 14:28:05', '', '2020-11-10 19:27:12', '2020-11-10 20:07:45');

-- ----------------------------
-- Table structure for integral_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `integral_order_goods`;
CREATE TABLE `integral_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `goods_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品图片',
  `buy_num` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '购买数量',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总价格',
  `goods_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品单价',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of integral_order_goods
-- ----------------------------
INSERT INTO `integral_order_goods` VALUES ('2', '2', '1', '1', '123', 'http://127.0.0.1:8000/storage/integrals/0/2020-11-09/HgTyg2LMUlc6Q48puimpxkISyQtNmtCixZbpZDDG_150.png', '2', '20.00', '10.00', '2020-11-10 19:27:12', '2020-11-10 19:27:12');

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `ename` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单英文',
  `icon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `link` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '链接',
  `is_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '类型0 后台 1商家',
  `is_sort` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('1', '0', '用户中心', '', 'iconchengyuan', '#', '0', '0', '2020-06-26 18:28:08', '2020-06-30 16:43:21');
INSERT INTO `menus` VALUES ('2', '1', '用户管理', '', '', '#', '0', '0', '2020-06-26 18:28:08', '2020-06-26 18:28:08');
INSERT INTO `menus` VALUES ('3', '2', '管理员', '', '', '/Admin/admins', '0', '1', '2020-06-26 18:28:08', '2020-06-27 18:02:31');
INSERT INTO `menus` VALUES ('4', '2', '平台用户', '', '', '/Admin/users', '0', '0', '2020-06-26 18:28:08', '2020-09-10 14:21:11');
INSERT INTO `menus` VALUES ('5', '1', '菜单管理', '', '', '#', '0', '0', '2020-06-26 18:28:08', '2020-06-26 18:28:08');
INSERT INTO `menus` VALUES ('6', '5', '后台菜单', '', '', '/Admin/menus', '0', '0', '2020-06-26 18:28:08', '2020-06-26 18:35:52');
INSERT INTO `menus` VALUES ('7', '5', '商家菜单', '', '', '/Admin/menus?is_type=1', '0', '0', '2020-06-26 18:28:08', '2020-06-26 18:35:41');
INSERT INTO `menus` VALUES ('8', '1', '接口管理', '', '', '', '0', '0', '2020-06-26 18:34:44', '2020-06-26 18:34:44');
INSERT INTO `menus` VALUES ('9', '8', '接口列表', '', '', '/Admin/permissions', '0', '0', '2020-06-26 18:35:03', '2020-06-26 18:35:03');
INSERT INTO `menus` VALUES ('10', '8', '接口分组', '', '', '/Admin/permission_groups', '0', '0', '2020-06-26 18:35:20', '2020-06-26 18:35:20');
INSERT INTO `menus` VALUES ('11', '1', '角色管理', '', '', '/Admin/roles', '0', '0', '2020-06-26 18:36:10', '2020-06-26 18:36:10');
INSERT INTO `menus` VALUES ('12', '0', '商品中心', '', 'iconchanpin1', '', '0', '2', '2020-06-28 19:54:03', '2020-06-30 16:43:49');
INSERT INTO `menus` VALUES ('13', '12', '商品分类', '', '', '/Admin/goods_classes', '0', '1', '2020-06-28 19:55:43', '2020-06-30 16:56:27');
INSERT INTO `menus` VALUES ('14', '12', '品牌管理', '', '', '/Admin/goods_brands', '0', '2', '2020-06-28 19:56:15', '2020-06-30 16:56:31');
INSERT INTO `menus` VALUES ('15', '12', '商品管理', '', '', '/Admin/goods', '0', '0', '2020-06-28 19:56:45', '2020-06-28 19:56:45');
INSERT INTO `menus` VALUES ('16', '0', '配置中心', '', 'iconshezhi', '', '0', '1', '2020-06-30 15:47:00', '2020-06-30 16:43:28');
INSERT INTO `menus` VALUES ('17', '16', '网站配置', '', '', '/Admin/configs/web', '0', '0', '2020-06-30 15:49:04', '2020-06-30 18:46:59');
INSERT INTO `menus` VALUES ('18', '16', '支付配置', '', '', '/Admin/configs/pay', '0', '8', '2020-06-30 15:49:35', '2020-11-11 10:12:49');
INSERT INTO `menus` VALUES ('19', '16', '上传配置', '', '', '/Admin/configs/upload', '0', '7', '2020-06-30 15:49:57', '2020-11-11 10:13:04');
INSERT INTO `menus` VALUES ('20', '16', '短信配置', '', '', '/Admin/configs/sms', '0', '9', '2020-06-30 15:50:25', '2020-11-11 10:12:41');
INSERT INTO `menus` VALUES ('21', '16', 'Oauth配置', '', '', '/Admin/configs/oauth', '0', '5', '2020-06-30 15:50:55', '2020-11-11 15:07:47');
INSERT INTO `menus` VALUES ('24', '16', '任务执行', '', '', '/Admin/configs/task', '0', '11', '2020-06-30 15:55:42', '2020-11-11 10:12:57');
INSERT INTO `menus` VALUES ('25', '0', '店铺中心', '', 'icondianpu', '', '0', '4', '2020-06-30 15:58:00', '2020-06-30 16:44:30');
INSERT INTO `menus` VALUES ('26', '0', '订单中心', '', 'icondingdan', '', '0', '3', '2020-06-30 15:58:21', '2020-06-30 16:44:27');
INSERT INTO `menus` VALUES ('27', '0', '积分商城', '', 'iconjifen', '', '0', '5', '2020-06-30 15:58:43', '2020-06-30 16:44:41');
INSERT INTO `menus` VALUES ('28', '0', '物流中心', '', 'iconwuliu', '', '0', '6', '2020-06-30 15:59:11', '2020-06-30 16:44:52');
INSERT INTO `menus` VALUES ('29', '0', '营销中心', '', 'iconVIP1', '', '0', '10', '2020-06-30 15:59:33', '2020-11-09 15:09:46');
INSERT INTO `menus` VALUES ('30', '0', '广告中心', '', 'iconphoto', '', '0', '11', '2020-06-30 16:00:48', '2020-11-09 15:09:40');
INSERT INTO `menus` VALUES ('31', '0', '数据统计', '', 'iconstatistics', '', '0', '12', '2020-06-30 16:01:21', '2020-11-09 15:09:37');
INSERT INTO `menus` VALUES ('32', '71', '站点协议', '', '', '/Admin/agreements', '0', '11', '2020-06-30 16:53:56', '2020-11-09 15:10:03');
INSERT INTO `menus` VALUES ('33', '30', '广告位管理', '', '', '/Admin/adv_positions', '0', '0', '2020-07-15 16:20:47', '2020-07-15 16:21:24');
INSERT INTO `menus` VALUES ('34', '30', '广告管理', '', '', '/Admin/advs', '0', '0', '2020-07-15 19:49:02', '2020-07-15 19:49:02');
INSERT INTO `menus` VALUES ('35', '28', '区域地址', '', '', '/Admin/areas', '0', '0', '2020-07-22 22:32:07', '2020-07-22 22:32:07');
INSERT INTO `menus` VALUES ('36', '25', '店铺管理', '', '', '/Admin/stores', '0', '0', '2020-07-25 16:17:29', '2020-07-25 17:55:01');
INSERT INTO `menus` VALUES ('37', '0', '商品中心', '', 'iconchanpin1', '', '1', '2', '2020-07-29 12:39:33', '2020-11-06 13:14:19');
INSERT INTO `menus` VALUES ('38', '37', '商品规格', '', '', '/Seller/goods_attrs', '1', '1', '2020-07-29 12:40:08', '2020-07-29 12:40:44');
INSERT INTO `menus` VALUES ('39', '37', '商品管理', '', '', '/Seller/goods', '1', '0', '2020-07-29 12:40:29', '2020-07-29 12:40:36');
INSERT INTO `menus` VALUES ('40', '26', '订单管理', '', '', '/Admin/orders', '0', '0', '2020-09-09 10:27:06', '2020-09-09 10:27:06');
INSERT INTO `menus` VALUES ('41', '26', '退款订单', '', '', '/Admin/orders?is_refund=1', '0', '1', '2020-09-09 10:29:25', '2020-09-28 15:45:46');
INSERT INTO `menus` VALUES ('42', '26', '退货订单', '', '', '/Admin/orders?is_return=1', '0', '2', '2020-09-09 10:29:58', '2020-09-28 15:46:16');
INSERT INTO `menus` VALUES ('43', '26', '订单评论', '', '', '/Admin/order_comments', '0', '3', '2020-09-09 10:30:29', '2020-09-23 16:16:53');
INSERT INTO `menus` VALUES ('44', '16', '快递配置', '', '', '/Admin/configs/kuaibao', '0', '10', '2020-09-09 15:51:25', '2020-09-09 15:52:12');
INSERT INTO `menus` VALUES ('45', '28', '物流公司', '', '', '/Admin/expresses', '0', '0', '2020-09-09 16:51:56', '2020-09-09 16:52:04');
INSERT INTO `menus` VALUES ('46', '0', '订单中心', '', 'icondingdan', '#', '1', '3', '2020-09-23 16:12:19', '2020-11-06 13:14:28');
INSERT INTO `menus` VALUES ('47', '46', '订单管理', '', '', '/Seller/orders', '1', '0', '2020-09-23 16:14:53', '2020-09-23 16:22:15');
INSERT INTO `menus` VALUES ('48', '46', '订单评论', '', '', '/Seller/order_comments', '1', '3', '2020-09-23 16:15:04', '2020-09-23 16:22:45');
INSERT INTO `menus` VALUES ('49', '46', '退货订单', '', '', '/Seller/orders?is_return=1', '1', '2', '2020-09-23 16:15:34', '2020-09-28 15:46:54');
INSERT INTO `menus` VALUES ('50', '46', '退款订单', '', '', '/Seller/orders?is_refund=1', '1', '1', '2020-09-23 16:15:46', '2020-09-28 15:46:40');
INSERT INTO `menus` VALUES ('52', '63', '商家配置', '', '', '/Seller/configs', '1', '0', '2020-10-06 14:06:34', '2020-11-06 20:59:39');
INSERT INTO `menus` VALUES ('54', '71', '帮助中心', '', '', '/Admin/articles', '0', '12', '2020-11-05 19:51:08', '2020-11-09 15:10:15');
INSERT INTO `menus` VALUES ('55', '0', '物流中心', '', 'iconwuliu', '', '1', '4', '2020-11-06 12:16:30', '2020-11-06 13:14:36');
INSERT INTO `menus` VALUES ('56', '0', '营销中心', '', 'iconVIP1', '', '1', '5', '2020-11-06 12:16:44', '2020-11-06 13:14:41');
INSERT INTO `menus` VALUES ('57', '29', '分销管理', '', '', '/Admin/distribution_logs', '0', '0', '2020-11-06 12:22:37', '2020-11-07 23:11:27');
INSERT INTO `menus` VALUES ('58', '56', '分销管理', '', '', '/Seller/distributions', '1', '0', '2020-11-06 12:22:59', '2020-11-07 11:01:41');
INSERT INTO `menus` VALUES ('59', '0', '客服中心', '', 'iconpinglun', '', '1', '6', '2020-11-06 12:25:36', '2020-11-06 13:14:50');
INSERT INTO `menus` VALUES ('60', '55', '配送运费', '', '', '/Seller/freights/form', '1', '0', '2020-11-06 13:07:24', '2020-11-06 13:30:25');
INSERT INTO `menus` VALUES ('61', '0', '数据统计', '', 'iconstatistics', '', '1', '9', '2020-11-06 13:09:58', '2020-11-06 13:10:05');
INSERT INTO `menus` VALUES ('63', '0', '店铺中心', '', 'icondianpu', '', '1', '1', '2020-11-06 13:12:32', '2020-11-06 13:14:09');
INSERT INTO `menus` VALUES ('64', '63', '店铺资金', '', '', '/Seller/money_logs', '1', '3', '2020-11-06 13:13:05', '2020-11-08 17:03:32');
INSERT INTO `menus` VALUES ('65', '63', '订单结算', '', '', '/Seller/order_settlements', '1', '4', '2020-11-06 13:13:46', '2020-11-08 14:16:27');
INSERT INTO `menus` VALUES ('66', '25', '订单结算', '', '', '/Admin/order_settlements', '0', '4', '2020-11-06 21:11:37', '2020-11-08 14:16:16');
INSERT INTO `menus` VALUES ('67', '1', '资金管理', '', '', '', '0', '6', '2020-11-08 17:33:01', '2020-11-08 17:33:55');
INSERT INTO `menus` VALUES ('68', '67', '资金日志', '', '', '/Admin/money_logs', '0', '0', '2020-11-08 17:33:19', '2020-11-08 17:34:26');
INSERT INTO `menus` VALUES ('69', '67', '提现管理', '', '', '/Admin/cashes', '0', '0', '2020-11-08 17:33:33', '2020-11-08 17:35:14');
INSERT INTO `menus` VALUES ('70', '63', '资金提现', '', '', '/Seller/cashes', '1', '5', '2020-11-08 20:00:56', '2020-11-08 20:01:23');
INSERT INTO `menus` VALUES ('71', '0', '文章中心', '', 'iconshuxie', '', '0', '9', '2020-11-09 15:09:18', '2020-11-09 15:11:13');
INSERT INTO `menus` VALUES ('72', '27', '商品分类', '', '', '/Admin/integral_goods_classes', '0', '0', '2020-11-09 15:12:49', '2020-11-09 15:12:49');
INSERT INTO `menus` VALUES ('73', '27', '商品管理', '', '', '/Admin/integral_goods', '0', '1', '2020-11-09 15:14:02', '2020-11-09 15:18:26');
INSERT INTO `menus` VALUES ('74', '27', '订单管理', '', '', '/Admin/integral_orders', '0', '2', '2020-11-09 15:14:29', '2020-11-09 15:18:31');
INSERT INTO `menus` VALUES ('75', '31', '会员分析', '', '', '/Admin/statistics/user', '0', '0', '2020-11-14 10:17:42', '2020-11-14 10:31:25');
INSERT INTO `menus` VALUES ('76', '31', '店铺分析', '', '', '/Admin/statistics/store', '0', '1', '2020-11-14 10:18:52', '2020-11-14 10:21:00');
INSERT INTO `menus` VALUES ('77', '31', '销量分析', '', '', '/Admin/statistics/order', '0', '2', '2020-11-14 10:19:21', '2020-11-14 10:21:05');
INSERT INTO `menus` VALUES ('78', '31', '支付分析', '', '', '/Admin/statistics/pay', '0', '5', '2020-11-14 10:20:37', '2020-11-14 10:21:10');
INSERT INTO `menus` VALUES ('79', '61', '销量分析', '', '', '/Seller/statistics/order', '1', '0', '2020-11-14 18:08:15', '2020-11-14 18:08:15');
INSERT INTO `menus` VALUES ('80', '56', '优惠券管理', '', '', '/Seller/coupons', '1', '1', '2020-11-15 14:54:44', '2020-11-15 14:58:48');
INSERT INTO `menus` VALUES ('81', '56', '秒杀管理', '', '', '/Seller/seckills', '1', '2', '2020-11-15 14:55:33', '2020-11-15 14:58:53');
INSERT INTO `menus` VALUES ('82', '56', '拼团管理', '', '', '/Seller/collectives', '1', '3', '2020-11-15 14:56:43', '2020-11-15 14:58:57');
INSERT INTO `menus` VALUES ('83', '56', '满减管理', '', '', '/Seller/full_reductions', '1', '4', '2020-11-15 14:58:29', '2020-11-15 14:59:01');
INSERT INTO `menus` VALUES ('85', '59', '在线客服', '', '', '/Seller/chats', '1', '0', '2020-11-17 11:53:59', '2020-11-17 11:53:59');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2020_06_02_141725_create_admins_table', '1');
INSERT INTO `migrations` VALUES ('2', '2020_06_02_231320_create_admin_role_table', '1');
INSERT INTO `migrations` VALUES ('3', '2020_06_02_231523_create_roles_table', '1');
INSERT INTO `migrations` VALUES ('4', '2020_06_02_231858_create_role_permissions_table', '1');
INSERT INTO `migrations` VALUES ('5', '2020_06_02_232022_create_permissions_table', '1');
INSERT INTO `migrations` VALUES ('6', '2020_06_02_232717_create_permission_groups_table', '1');
INSERT INTO `migrations` VALUES ('7', '2020_06_03_122512_create_users_table', '1');
INSERT INTO `migrations` VALUES ('8', '2020_06_03_181319_create_menus_table', '1');
INSERT INTO `migrations` VALUES ('9', '2020_06_03_181452_create_role_menus_table', '1');
INSERT INTO `migrations` VALUES ('10', '2020_06_07_203756_create_configs_table', '1');
INSERT INTO `migrations` VALUES ('12', '2020_06_09_164257_create_goods_brands_table', '1');
INSERT INTO `migrations` VALUES ('13', '2020_06_09_165422_create_goods_table', '1');
INSERT INTO `migrations` VALUES ('14', '2020_06_09_175013_create_goods_skus_table', '1');
INSERT INTO `migrations` VALUES ('15', '2020_06_09_180047_create_goods_attrs_table', '1');
INSERT INTO `migrations` VALUES ('16', '2020_06_09_180135_create_goods_specs_table', '1');
INSERT INTO `migrations` VALUES ('17', '2020_07_03_103520_create_sms_signs_table', '1');
INSERT INTO `migrations` VALUES ('18', '2020_07_03_103541_create_sms_logs_table', '1');
INSERT INTO `migrations` VALUES ('19', '2020_07_15_151137_create_stores_table', '2');
INSERT INTO `migrations` VALUES ('20', '2020_07_15_155308_create_adv_positions_table', '3');
INSERT INTO `migrations` VALUES ('21', '2020_07_15_155322_create_advs_table', '4');
INSERT INTO `migrations` VALUES ('22', '2020_07_15_160338_create_agreements_table', '4');
INSERT INTO `migrations` VALUES ('28', '2020_07_21_203423_create_store_classes_table', '5');
INSERT INTO `migrations` VALUES ('30', '2020_07_21_215554_create_areas_table', '6');
INSERT INTO `migrations` VALUES ('31', '2020_06_09_163803_create_goods_classes_table', '7');
INSERT INTO `migrations` VALUES ('34', '2020_08_31_075220_create_carts_table', '8');
INSERT INTO `migrations` VALUES ('36', '2020_09_04_150052_create_addresses_table', '9');
INSERT INTO `migrations` VALUES ('43', '2020_09_06_130845_create_orders_table', '10');
INSERT INTO `migrations` VALUES ('44', '2020_09_06_130931_create_order_goods_table', '10');
INSERT INTO `migrations` VALUES ('46', '2020_09_09_163129_create_expresses_table', '11');
INSERT INTO `migrations` VALUES ('47', '2020_09_13_135612_create_order_pays_table', '11');
INSERT INTO `migrations` VALUES ('48', '2020_09_15_125144_create_money_logs_table', '12');
INSERT INTO `migrations` VALUES ('49', '2020_09_15_164749_create_user_checks_table', '12');
INSERT INTO `migrations` VALUES ('51', '2020_09_16_171538_create_favorites_table', '13');
INSERT INTO `migrations` VALUES ('52', '2020_09_17_165908_create_user_wechats_table', '14');
INSERT INTO `migrations` VALUES ('55', '2020_09_23_190906_create_order_comments_table', '15');
INSERT INTO `migrations` VALUES ('60', '2020_09_28_123222_create_refunds_table', '16');
INSERT INTO `migrations` VALUES ('61', '2020_09_29_102253_create_cashes_table', '16');
INSERT INTO `migrations` VALUES ('63', '2020_11_05_194851_create_articles_table', '17');
INSERT INTO `migrations` VALUES ('64', '2020_11_06_125522_create_freights_table', '18');
INSERT INTO `migrations` VALUES ('67', '2020_11_06_204103_create_distributions_table', '19');
INSERT INTO `migrations` VALUES ('68', '2020_11_06_204925_create_distribution_logs_table', '19');
INSERT INTO `migrations` VALUES ('70', '2020_11_08_124622_create_order_settlements_table', '20');
INSERT INTO `migrations` VALUES ('71', '2020_11_09_140654_create_integral_goods_table', '21');
INSERT INTO `migrations` VALUES ('72', '2020_11_09_141000_create_integral_goods_classes_table', '21');
INSERT INTO `migrations` VALUES ('73', '2020_11_09_141017_create_integral_orders_table', '21');
INSERT INTO `migrations` VALUES ('74', '2020_11_09_142432_create_integral_order_goods_table', '21');
INSERT INTO `migrations` VALUES ('75', '2020_11_15_150214_create_coupons_table', '22');
INSERT INTO `migrations` VALUES ('77', '2020_11_15_164646_create_coupon_logs_table', '23');
INSERT INTO `migrations` VALUES ('78', '2020_11_16_105023_create_full_reductions_table', '24');
INSERT INTO `migrations` VALUES ('79', '2020_11_16_120607_create_seckills_table', '25');
INSERT INTO `migrations` VALUES ('80', '2020_11_16_171832_create_collectives_table', '26');
INSERT INTO `migrations` VALUES ('81', '2020_11_16_182805_create_collective_logs_table', '27');
INSERT INTO `migrations` VALUES ('82', '2020_11_19_131141_create_chat_friends_table', '28');
INSERT INTO `migrations` VALUES ('83', '2020_11_19_131300_create_chat_msgs_table', '28');

-- ----------------------------
-- Table structure for money_logs
-- ----------------------------
DROP TABLE IF EXISTS `money_logs`;
CREATE TABLE `money_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '未知变动' COMMENT '名称',
  `money` decimal(9,2) NOT NULL DEFAULT 0.00 COMMENT '变动金额',
  `is_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '变动类型 0 余额 1冻结 2积分',
  `is_seller` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否商家日志',
  `info` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of money_logs
-- ----------------------------
INSERT INTO `money_logs` VALUES ('1', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:09:37', '2020-09-23 14:09:37');
INSERT INTO `money_logs` VALUES ('2', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:09:45', '2020-09-23 14:09:45');
INSERT INTO `money_logs` VALUES ('3', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:10:16', '2020-09-23 14:10:16');
INSERT INTO `money_logs` VALUES ('4', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:11:36', '2020-09-23 14:11:36');
INSERT INTO `money_logs` VALUES ('5', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:12:15', '2020-09-23 14:12:15');
INSERT INTO `money_logs` VALUES ('6', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:12:54', '2020-09-23 14:12:54');
INSERT INTO `money_logs` VALUES ('7', '8', '订单支付', '-1.00', '0', '0', '', '2020-09-23 14:30:37', '2020-09-23 14:30:37');
INSERT INTO `money_logs` VALUES ('8', '8', '售后退款', '1.00', '0', '0', '2020091418433488438', '2020-09-28 18:43:42', '2020-09-28 18:43:42');
INSERT INTO `money_logs` VALUES ('9', '8', '资金提现', '-100.00', '0', '0', '', '2020-10-06 13:51:57', '2020-10-06 13:51:57');
INSERT INTO `money_logs` VALUES ('10', '8', '资金提现', '100.00', '1', '0', '', '2020-10-06 13:51:57', '2020-10-06 13:51:57');
INSERT INTO `money_logs` VALUES ('11', '8', '订单支付', '-12.00', '0', '0', '', '2020-11-07 21:34:03', '2020-11-07 21:34:03');
INSERT INTO `money_logs` VALUES ('12', '8', '订单支付', '0.00', '0', '0', '', '2020-11-07 21:36:05', '2020-11-07 21:36:05');
INSERT INTO `money_logs` VALUES ('13', '8', '订单支付', '-12.00', '0', '0', '', '2020-11-07 21:40:23', '2020-11-07 21:40:23');
INSERT INTO `money_logs` VALUES ('14', '8', '订单支付', '-12.00', '0', '0', '', '2020-11-07 21:48:21', '2020-11-07 21:48:21');
INSERT INTO `money_logs` VALUES ('15', '8', '订单支付', '-12.00', '0', '0', '', '2020-11-07 21:52:04', '2020-11-07 21:52:04');
INSERT INTO `money_logs` VALUES ('19', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-08 16:39:35', '2020-11-08 16:39:35');
INSERT INTO `money_logs` VALUES ('20', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-08 16:40:56', '2020-11-08 16:40:56');
INSERT INTO `money_logs` VALUES ('21', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-08 16:41:03', '2020-11-08 16:41:03');
INSERT INTO `money_logs` VALUES ('22', '2', '资金提现', '-100.00', '0', '1', '', '2020-11-08 20:26:26', '2020-11-08 20:26:26');
INSERT INTO `money_logs` VALUES ('23', '2', '资金提现', '100.00', '1', '1', '', '2020-11-08 20:26:26', '2020-11-08 20:26:26');
INSERT INTO `money_logs` VALUES ('24', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 20:50:44', '2020-11-08 20:50:44');
INSERT INTO `money_logs` VALUES ('25', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 20:51:39', '2020-11-08 20:51:39');
INSERT INTO `money_logs` VALUES ('26', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 20:54:07', '2020-11-08 20:54:07');
INSERT INTO `money_logs` VALUES ('27', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:01:21', '2020-11-08 21:01:21');
INSERT INTO `money_logs` VALUES ('28', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:05:25', '2020-11-08 21:05:25');
INSERT INTO `money_logs` VALUES ('29', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:08:02', '2020-11-08 21:08:02');
INSERT INTO `money_logs` VALUES ('30', '2', '资金提现', '-100.00', '1', '1', '', '2020-11-08 21:08:32', '2020-11-08 21:08:32');
INSERT INTO `money_logs` VALUES ('31', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:10:40', '2020-11-08 21:10:40');
INSERT INTO `money_logs` VALUES ('32', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:11:16', '2020-11-08 21:11:16');
INSERT INTO `money_logs` VALUES ('33', '8', '资金提现', '-100.00', '1', '0', '', '2020-11-08 21:14:48', '2020-11-08 21:14:48');
INSERT INTO `money_logs` VALUES ('34', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-08 21:25:12', '2020-11-08 21:25:12');
INSERT INTO `money_logs` VALUES ('35', '1', '分销分佣', '0.50', '0', '0', '', '2020-11-08 21:30:08', '2020-11-08 21:30:08');
INSERT INTO `money_logs` VALUES ('36', '1', '分销分佣', '0.30', '0', '0', '', '2020-11-08 21:30:08', '2020-11-08 21:30:08');
INSERT INTO `money_logs` VALUES ('37', '1', '分销分佣', '0.50', '0', '0', '', '2020-11-08 21:30:08', '2020-11-08 21:30:08');
INSERT INTO `money_logs` VALUES ('38', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-08 21:30:08', '2020-11-08 21:30:08');
INSERT INTO `money_logs` VALUES ('39', '1', '分销分佣', '0.50', '0', '0', '', '2020-11-09 14:57:08', '2020-11-09 14:57:08');
INSERT INTO `money_logs` VALUES ('40', '1', '分销分佣', '0.30', '0', '0', '', '2020-11-09 14:57:08', '2020-11-09 14:57:08');
INSERT INTO `money_logs` VALUES ('41', '1', '分销分佣', '0.50', '0', '0', '', '2020-11-09 14:57:08', '2020-11-09 14:57:08');
INSERT INTO `money_logs` VALUES ('42', '2', '分销分佣', '10.70', '0', '1', '', '2020-11-09 14:57:08', '2020-11-09 14:57:08');
INSERT INTO `money_logs` VALUES ('43', '2', '分销分佣', '12.00', '0', '1', '', '2020-11-09 14:58:55', '2020-11-09 14:58:55');
INSERT INTO `money_logs` VALUES ('44', '1', '订单支付', '-20.00', '2', '0', '', '2020-11-10 19:27:12', '2020-11-10 19:27:12');
INSERT INTO `money_logs` VALUES ('45', '1', 'users.money_log_inte', '1.00', '2', '0', '', '2020-11-11 10:56:16', '2020-11-11 10:56:16');
INSERT INTO `money_logs` VALUES ('46', '1', '登录积分', '1.00', '2', '0', '', '2020-11-11 10:57:29', '2020-11-11 10:57:29');
INSERT INTO `money_logs` VALUES ('47', '1', '登录积分', '1.00', '2', '0', '', '2020-11-11 16:08:53', '2020-11-11 16:08:53');
INSERT INTO `money_logs` VALUES ('48', '1', '登录积分', '1.00', '2', '0', '', '2020-11-11 16:15:36', '2020-11-11 16:15:36');
INSERT INTO `money_logs` VALUES ('49', '1', '登录积分', '1.00', '2', '0', '', '2020-11-13 17:53:27', '2020-11-13 17:53:27');
INSERT INTO `money_logs` VALUES ('50', '1', '订单支付', '-12.00', '0', '0', '', '2020-11-14 16:14:09', '2020-11-14 16:14:09');
INSERT INTO `money_logs` VALUES ('51', '8', '登录积分', '1.00', '2', '0', '', '2020-11-15 17:46:38', '2020-11-15 17:46:38');
INSERT INTO `money_logs` VALUES ('52', '8', '登录积分', '1.00', '2', '0', '', '2020-11-15 17:52:46', '2020-11-15 17:52:46');
INSERT INTO `money_logs` VALUES ('53', '8', '登录积分', '1.00', '2', '0', '', '2020-11-15 17:57:36', '2020-11-15 17:57:36');
INSERT INTO `money_logs` VALUES ('54', '8', '登录积分', '1.00', '2', '0', '', '2020-11-15 17:59:17', '2020-11-15 17:59:17');
INSERT INTO `money_logs` VALUES ('55', '8', '登录积分', '1.00', '2', '0', '', '2020-11-15 18:01:10', '2020-11-15 18:01:10');
INSERT INTO `money_logs` VALUES ('56', '1', '登录积分', '1.00', '2', '0', '', '2020-11-15 18:01:25', '2020-11-15 18:01:25');
INSERT INTO `money_logs` VALUES ('57', '1', '订单支付', '-50.00', '0', '0', '', '2020-11-15 23:32:16', '2020-11-15 23:32:16');
INSERT INTO `money_logs` VALUES ('58', '8', '登录积分', '1.00', '2', '0', '', '2020-11-19 15:23:23', '2020-11-19 15:23:23');
INSERT INTO `money_logs` VALUES ('59', '8', '登录积分', '1.00', '2', '0', '', '2020-11-19 16:07:47', '2020-11-19 16:07:47');
INSERT INTO `money_logs` VALUES ('60', '1', '登录积分', '1.00', '2', '0', '', '2020-11-23 09:51:07', '2020-11-23 09:51:07');
INSERT INTO `money_logs` VALUES ('61', '8', '登录积分', '1.00', '2', '0', '', '2020-11-23 11:01:03', '2020-11-23 11:01:03');
INSERT INTO `money_logs` VALUES ('62', '1', '登录积分', '1.00', '2', '0', '', '2020-11-23 11:01:47', '2020-11-23 11:01:47');
INSERT INTO `money_logs` VALUES ('63', '1', '登录积分', '1.00', '2', '0', '', '2021-01-12 13:50:06', '2021-01-12 13:50:06');
INSERT INTO `money_logs` VALUES ('64', '1', '登录积分', '1.00', '2', '0', '', '2021-01-14 10:47:27', '2021-01-14 10:47:27');
INSERT INTO `money_logs` VALUES ('65', '1', '登录积分', '1.00', '2', '0', '', '2021-01-15 15:35:43', '2021-01-15 15:35:43');
INSERT INTO `money_logs` VALUES ('66', '1', '登录积分', '1.00', '2', '0', '', '2021-01-15 18:26:02', '2021-01-15 18:26:02');
INSERT INTO `money_logs` VALUES ('67', '1', '登录积分', '1.00', '2', '0', '', '2021-01-15 18:26:54', '2021-01-15 18:26:54');
INSERT INTO `money_logs` VALUES ('68', '1', '登录积分', '1.00', '2', '0', '', '2021-01-15 18:28:14', '2021-01-15 18:28:14');
INSERT INTO `money_logs` VALUES ('69', '1', '登录积分', '1.00', '2', '0', '', '2021-01-15 18:46:40', '2021-01-15 18:46:40');
INSERT INTO `money_logs` VALUES ('70', '1', '登录积分', '1.00', '2', '0', '', '2021-01-18 10:09:31', '2021-01-18 10:09:31');
INSERT INTO `money_logs` VALUES ('71', '1', '登录积分', '1.00', '2', '0', '', '2021-01-18 13:34:43', '2021-01-18 13:34:43');
INSERT INTO `money_logs` VALUES ('72', '1', '登录积分', '1.00', '2', '0', '', '2021-01-18 14:26:53', '2021-01-18 14:26:53');
INSERT INTO `money_logs` VALUES ('73', '1', '登录积分', '1.00', '2', '0', '', '2021-01-18 15:11:54', '2021-01-18 15:11:54');
INSERT INTO `money_logs` VALUES ('74', '1', '登录积分', '1.00', '2', '0', '', '2021-01-18 16:16:48', '2021-01-18 16:16:48');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '优惠券ID',
  `collective_id` int(10) NOT NULL DEFAULT 0 COMMENT '拼团日志ID',
  `order_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单标题',
  `order_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单图片',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总支付金额',
  `order_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '订单计算金额',
  `order_balance` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '余额支付金额',
  `freight_money` decimal(5,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '运费金额',
  `coupon_money` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '优惠金额',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '订单支付 0 取消 1 等待支付 2等待发货 3确认收货 4等待评论 5售后 6订单完成',
  `refund_status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0 退款 1退货 2 处理结束',
  `is_settlement` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是否结算',
  `delivery_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递订单号',
  `delivery_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递公司编码',
  `receive_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人名',
  `receive_tel` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '收件人手机',
  `receive_area` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址信息',
  `receive_address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址信息',
  `payment_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付方式',
  `pay_time` timestamp NOT NULL DEFAULT '2020-09-09 16:30:13' COMMENT '支付时间',
  `delivery_time` timestamp NOT NULL DEFAULT '2020-09-09 16:30:13' COMMENT '发货时间',
  `receipt_time` timestamp NOT NULL DEFAULT '2020-09-09 16:30:13' COMMENT '确认收货时间',
  `comment_time` timestamp NOT NULL DEFAULT '2020-09-09 16:30:13' COMMENT '评论时间',
  `remark` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES ('1', '8', '2', '0', '0', '2020091018223983846', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '1.00', '1.00', '0.00', '0.00', '0.00', '0', '0', '0', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-09-10 18:22:39', '2020-09-24 12:25:15');
INSERT INTO `orders` VALUES ('2', '8', '2', '0', '0', '2020091018254689366', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '1.00', '1.00', '0.00', '0.00', '0.00', '0', '0', '0', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', 'scs', '2020-09-10 18:25:46', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('3', '8', '2', '0', '0', '2020091418433488438', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '1.00', '1.00', '0.00', '0.00', '0.00', '0', '2', '0', '4604719464579', 'yd', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', 'money', '2020-09-23 14:30:37', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-09-14 18:43:34', '2020-09-28 19:05:31');
INSERT INTO `orders` VALUES ('4', '8', '2', '0', '0', '2020092221141088672', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '3.00', '3.00', '0.00', '0.00', '0.00', '0', '0', '0', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-09-22 21:14:10', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('8', '1', '2', '0', '0', '2020110619053313039', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '1.00', '1.00', '0.00', '0.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-06 19:05:33', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('13', '1', '2', '0', '0', '2020110619124215405', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '11.00', '1.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-06 19:12:42', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('14', '1', '2', '0', '0', '2020110619195814751', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '2.00', '1.00', '0.00', '1.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-06 19:19:58', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('15', '1', '2', '0', '0', '2020110721131415465', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:13:14', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('16', '1', '2', '0', '0', '2020110721142511482', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:14:25', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('17', '1', '2', '0', '0', '2020110721145912334', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:14:59', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('23', '1', '2', '0', '0', '2020110721253912417', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:25:39', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('24', '1', '2', '0', '0', '2020110721315214526', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '15.00', '5.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:31:52', '2020-11-09 13:23:16');
INSERT INTO `orders` VALUES ('25', '8', '2', '0', '0', '2020110721335888082', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '6', '0', '1', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', 'money', '2020-11-07 21:34:03', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:33:58', '2020-11-09 14:58:55');
INSERT INTO `orders` VALUES ('26', '8', '2', '0', '0', '2020110721395389902', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '2', '0', '0', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', 'money', '2020-11-07 21:40:24', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:39:53', '2020-11-07 21:40:24');
INSERT INTO `orders` VALUES ('27', '8', '2', '0', '0', '2020110721475784128', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '2', '0', '0', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', 'money', '2020-11-07 21:48:22', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:47:57', '2020-11-07 21:48:22');
INSERT INTO `orders` VALUES ('28', '8', '2', '0', '0', '2020110721515681057', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '6', '0', '1', '', '', 'xsa', '18888888888', '北京市 市辖区 东城区', 'sds', 'money', '2020-11-07 21:52:04', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-07 21:51:56', '2020-11-09 14:57:08');
INSERT INTO `orders` VALUES ('29', '1', '2', '0', '0', '2020110913324511447', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '11.00', '1.00', '0.00', '10.00', '0.00', '6', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-09 13:32:45', '2020-11-09 13:32:46');
INSERT INTO `orders` VALUES ('30', '1', '2', '0', '0', '2020111416114811845', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '12.00', '2.00', '0.00', '10.00', '0.00', '2', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', 'money', '2020-11-14 16:14:10', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-14 16:11:48', '2020-11-14 16:14:10');
INSERT INTO `orders` VALUES ('31', '1', '2', '0', '0', '2020111521390319596', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '60.00', '50.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 21:39:03', '2020-11-15 23:29:55');
INSERT INTO `orders` VALUES ('32', '1', '2', '0', '0', '2020111522562814875', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '60.00', '50.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 22:56:28', '2020-11-15 22:56:28');
INSERT INTO `orders` VALUES ('33', '1', '2', '1', '0', '2020111522574411203', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '60.00', '50.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 22:57:44', '2020-11-15 22:57:44');
INSERT INTO `orders` VALUES ('34', '1', '2', '0', '0', '2020111523140717453', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '60.00', '50.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 23:14:07', '2020-11-15 23:14:07');
INSERT INTO `orders` VALUES ('35', '1', '2', '1', '0', '2020111523142716261', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '50.00', '50.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 23:14:27', '2020-11-15 23:14:27');
INSERT INTO `orders` VALUES ('36', '1', '2', '1', '0', '2020111523154414151', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '50.00', '50.00', '0.00', '10.00', '0.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 23:15:44', '2020-11-15 23:31:40');
INSERT INTO `orders` VALUES ('37', '1', '2', '1', '0', '2020111523225511463', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '50.00', '50.00', '0.00', '10.00', '10.00', '0', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 23:22:55', '2020-11-15 23:30:20');
INSERT INTO `orders` VALUES ('38', '1', '2', '1', '0', '2020111523320311176', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '50.00', '50.00', '0.00', '10.00', '10.00', '2', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', 'money', '2020-11-15 23:32:16', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-15 23:32:03', '2020-11-15 23:32:16');
INSERT INTO `orders` VALUES ('39', '1', '2', '0', '0', '2020111611504316971', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '50.00', '50.00', '0.00', '10.00', '10.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-16 11:50:43', '2020-11-16 11:50:44');
INSERT INTO `orders` VALUES ('41', '1', '2', '0', '0', '2020111613351312724', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '55.00', '50.00', '0.00', '10.00', '5.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-16 13:35:13', '2020-11-16 13:35:13');
INSERT INTO `orders` VALUES ('42', '1', '2', '0', '0', '2020111613501219898', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '55.00', '50.00', '0.00', '10.00', '5.00', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-16 13:50:12', '2020-11-16 13:50:12');
INSERT INTO `orders` VALUES ('44', '1', '2', '0', '-1', '2020111623013419966', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '10.85', '1.00', '0.00', '10.00', '0.15', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-16 23:01:34', '2020-11-16 23:01:35');
INSERT INTO `orders` VALUES ('49', '1', '2', '0', '1', '2020111623444615133', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '10.85', '1.00', '0.00', '10.00', '0.15', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-16 23:44:46', '2020-11-16 23:44:46');
INSERT INTO `orders` VALUES ('50', '1', '2', '0', '2', '2020111813204612745', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '10.80', '1.00', '0.00', '10.00', '0.20', '1', '0', '0', '', '', '搜索', '18888888888', '北京市 市辖区 东城区', '公告公共', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2020-11-18 13:20:46', '2020-11-18 13:20:46');
INSERT INTO `orders` VALUES ('51', '1', '2', '0', '0', '2021011414301213782', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '17.00', '7.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '李三', '18888888888', '山西省 太原市 长子县', '打扫打扫', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2021-01-14 14:30:12', '2021-01-14 14:30:13');
INSERT INTO `orders` VALUES ('52', '1', '2', '0', '0', '2021011414393617140', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '11.00', '1.00', '0.00', '10.00', '0.00', '1', '0', '0', '', '', '李三', '18888888888', '山西省 太原市 长子县', '打扫打扫', '', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '2020-09-09 16:30:13', '', '2021-01-14 14:39:36', '2021-01-14 14:39:36');

-- ----------------------------
-- Table structure for order_comments
-- ----------------------------
DROP TABLE IF EXISTS `order_comments`;
CREATE TABLE `order_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `score` decimal(5,2) unsigned NOT NULL DEFAULT 5.00 COMMENT '综合评分',
  `agree` decimal(5,2) unsigned NOT NULL DEFAULT 5.00 COMMENT '描述相符',
  `service` decimal(5,2) unsigned NOT NULL DEFAULT 5.00 COMMENT '服务态度',
  `speed` decimal(5,2) unsigned NOT NULL DEFAULT 5.00 COMMENT '发货速度',
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '回复内容',
  `reply_time` timestamp NOT NULL DEFAULT '2020-09-24 18:10:52' COMMENT '回复时间',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片逗号隔开',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of order_comments
-- ----------------------------
INSERT INTO `order_comments` VALUES ('1', '8', '3', '16', '2', '5.00', '5.00', '5.00', '5.00', '谢谢亲的回复！', '2020-09-24 19:30:51', '很不错！2', '', '2020-09-24 18:11:37', '2020-09-24 19:30:51');

-- ----------------------------
-- Table structure for order_goods
-- ----------------------------
DROP TABLE IF EXISTS `order_goods`;
CREATE TABLE `order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `sku_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'SKUID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `goods_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品图片',
  `sku_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SKU 名称',
  `buy_num` int(10) unsigned NOT NULL DEFAULT 1 COMMENT '购买数量',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总价格',
  `goods_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '商品单价',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_weight` decimal(6,2) NOT NULL DEFAULT 0.00 COMMENT '总重量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of order_goods
-- ----------------------------
INSERT INTO `order_goods` VALUES ('1', '1', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-09-10 18:22:39', '2020-09-10 18:22:39', '0.00');
INSERT INTO `order_goods` VALUES ('2', '2', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-09-10 18:25:46', '2020-09-10 18:25:46', '0.00');
INSERT INTO `order_goods` VALUES ('3', '3', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-09-14 18:43:34', '2020-09-14 18:43:34', '0.00');
INSERT INTO `order_goods` VALUES ('4', '4', '19', '17', '8', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-09-22 21:14:10', '2020-09-22 21:14:10', '0.00');
INSERT INTO `order_goods` VALUES ('5', '4', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '2', '2.00', '1.00', '2020-09-22 21:14:10', '2020-09-22 21:14:10', '0.00');
INSERT INTO `order_goods` VALUES ('6', '8', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-06 19:05:33', '2020-11-06 19:05:33', '0.00');
INSERT INTO `order_goods` VALUES ('11', '13', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-06 19:12:42', '2020-11-06 19:12:42', '0.00');
INSERT INTO `order_goods` VALUES ('12', '14', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-06 19:19:58', '2020-11-06 19:19:58', '0.00');
INSERT INTO `order_goods` VALUES ('13', '15', '20', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '白色', '1', '2.00', '2.00', '2020-11-07 21:13:14', '2020-11-07 21:13:14', '0.00');
INSERT INTO `order_goods` VALUES ('14', '16', '20', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '白色', '1', '2.00', '2.00', '2020-11-07 21:14:25', '2020-11-07 21:14:25', '0.00');
INSERT INTO `order_goods` VALUES ('15', '17', '20', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '白色', '1', '2.00', '2.00', '2020-11-07 21:14:59', '2020-11-07 21:14:59', '0.00');
INSERT INTO `order_goods` VALUES ('17', '23', '20', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '白色', '1', '2.00', '2.00', '2020-11-07 21:25:39', '2020-11-07 21:25:39', '0.20');
INSERT INTO `order_goods` VALUES ('18', '24', '0', '16', '1', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '2', '2.00', '1.00', '2020-11-07 21:31:52', '2020-11-07 21:31:52', '6.00');
INSERT INTO `order_goods` VALUES ('19', '24', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '3.00', '3.00', '2020-11-07 21:31:52', '2020-11-07 21:31:52', '0.00');
INSERT INTO `order_goods` VALUES ('20', '25', '19', '17', '8', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-07 21:33:58', '2020-11-07 21:33:58', '0.10');
INSERT INTO `order_goods` VALUES ('21', '25', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-07 21:33:58', '2020-11-07 21:33:58', '3.00');
INSERT INTO `order_goods` VALUES ('22', '26', '19', '17', '8', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-07 21:39:53', '2020-11-07 21:39:53', '0.10');
INSERT INTO `order_goods` VALUES ('23', '26', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-07 21:39:53', '2020-11-07 21:39:53', '3.00');
INSERT INTO `order_goods` VALUES ('24', '27', '19', '17', '8', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-07 21:47:57', '2020-11-07 21:47:57', '0.10');
INSERT INTO `order_goods` VALUES ('25', '27', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-07 21:47:57', '2020-11-07 21:47:57', '3.00');
INSERT INTO `order_goods` VALUES ('26', '28', '19', '17', '8', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-07 21:51:56', '2020-11-07 21:51:56', '0.10');
INSERT INTO `order_goods` VALUES ('27', '28', '0', '16', '8', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-07 21:51:56', '2020-11-07 21:51:56', '3.00');
INSERT INTO `order_goods` VALUES ('28', '29', '0', '16', '1', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-09 13:32:45', '2020-11-09 13:32:45', '3.00');
INSERT INTO `order_goods` VALUES ('29', '30', '20', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '白色', '1', '2.00', '2.00', '2020-11-14 16:11:48', '2020-11-14 16:11:48', '0.20');
INSERT INTO `order_goods` VALUES ('30', '31', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 21:39:03', '2020-11-15 21:39:03', '0.00');
INSERT INTO `order_goods` VALUES ('31', '32', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 22:56:28', '2020-11-15 22:56:28', '0.00');
INSERT INTO `order_goods` VALUES ('32', '33', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 22:57:44', '2020-11-15 22:57:44', '0.00');
INSERT INTO `order_goods` VALUES ('33', '34', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 23:14:07', '2020-11-15 23:14:07', '0.00');
INSERT INTO `order_goods` VALUES ('34', '35', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 23:14:27', '2020-11-15 23:14:27', '0.00');
INSERT INTO `order_goods` VALUES ('35', '36', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 23:15:44', '2020-11-15 23:15:44', '0.00');
INSERT INTO `order_goods` VALUES ('36', '37', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 23:22:55', '2020-11-15 23:22:55', '0.00');
INSERT INTO `order_goods` VALUES ('37', '38', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-15 23:32:03', '2020-11-15 23:32:03', '0.00');
INSERT INTO `order_goods` VALUES ('38', '39', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-16 11:50:44', '2020-11-16 11:50:44', '0.00');
INSERT INTO `order_goods` VALUES ('39', '41', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-16 13:35:13', '2020-11-16 13:35:13', '0.00');
INSERT INTO `order_goods` VALUES ('40', '42', '21', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '黑色', '1', '50.00', '50.00', '2020-11-16 13:50:12', '2020-11-16 13:50:12', '0.00');
INSERT INTO `order_goods` VALUES ('42', '44', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-16 23:01:34', '2020-11-16 23:01:34', '0.10');
INSERT INTO `order_goods` VALUES ('47', '49', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2020-11-16 23:44:46', '2020-11-16 23:44:46', '0.10');
INSERT INTO `order_goods` VALUES ('48', '50', '0', '16', '1', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '1', '1.00', '1.00', '2020-11-18 13:20:46', '2020-11-18 13:20:46', '3.00');
INSERT INTO `order_goods` VALUES ('49', '51', '0', '16', '1', '2', '标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/t9vMqzHlQQCobmNsoxrmT7dKzMBuXX1Uku6vRNxH_150.jpg', '-', '3', '3.00', '1.00', '2021-01-14 14:30:13', '2021-01-14 14:30:13', '9.00');
INSERT INTO `order_goods` VALUES ('50', '51', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '4', '4.00', '1.00', '2021-01-14 14:30:13', '2021-01-14 14:30:13', '0.40');
INSERT INTO `order_goods` VALUES ('51', '52', '19', '17', '1', '2', '品标题品标题品标题品标题品标题', 'http://127.0.0.1:8000/storage/goods/2020-08-06/f6m56l8l5rJ8UuaxjcciAP3KVONFlaORnRkWChBD_150.jpg', '红色', '1', '1.00', '1.00', '2021-01-14 14:39:36', '2021-01-14 14:39:36', '0.10');

-- ----------------------------
-- Table structure for order_pays
-- ----------------------------
DROP TABLE IF EXISTS `order_pays`;
CREATE TABLE `order_pays` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `pay_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付号',
  `trade_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT ' 支付平台单号',
  `order_ids` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单ID 逗号隔开',
  `payment_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付方式',
  `pay_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'o' COMMENT '支付类型 o订单 r充值',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总金额',
  `order_balance` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '余额支付',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '支付状态',
  `pay_time` timestamp NOT NULL DEFAULT '2020-09-14 18:36:39' COMMENT '支付时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_pays_pay_no_unique` (`pay_no`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of order_pays
-- ----------------------------
INSERT INTO `order_pays` VALUES ('37', '8', '2020091419333', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-14 19:33:48', '2020-09-14 19:33:48');
INSERT INTO `order_pays` VALUES ('38', '8', '202009222114384', '', '4', '', 'o', '3.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-22 21:14:31', '2020-09-22 21:14:31');
INSERT INTO `order_pays` VALUES ('39', '8', '202009222200284', '', '4', '', 'o', '3.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-22 22:00:27', '2020-09-22 22:00:27');
INSERT INTO `order_pays` VALUES ('40', '8', '202009222201284', '', '4', '', 'o', '3.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-22 22:01:20', '2020-09-22 22:01:20');
INSERT INTO `order_pays` VALUES ('41', '8', '202009222212584', '', '4', '', 'o', '3.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-22 22:12:59', '2020-09-22 22:12:59');
INSERT INTO `order_pays` VALUES ('42', '8', '202009222213184', '', '4', '', 'o', '3.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-22 22:13:11', '2020-09-22 22:13:11');
INSERT INTO `order_pays` VALUES ('43', '8', '202009231203183', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 12:03:14', '2020-09-23 12:03:14');
INSERT INTO `order_pays` VALUES ('44', '8', '202009231203283', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 12:03:20', '2020-09-23 12:03:20');
INSERT INTO `order_pays` VALUES ('45', '8', '202009231409383', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:09:37', '2020-09-23 14:09:37');
INSERT INTO `order_pays` VALUES ('46', '8', '202009231409483', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:09:45', '2020-09-23 14:09:45');
INSERT INTO `order_pays` VALUES ('47', '8', '202009231410183', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:10:16', '2020-09-23 14:10:16');
INSERT INTO `order_pays` VALUES ('48', '8', '202009231410583', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:10:52', '2020-09-23 14:10:52');
INSERT INTO `order_pays` VALUES ('49', '8', '202009231411083', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:11:05', '2020-09-23 14:11:05');
INSERT INTO `order_pays` VALUES ('50', '8', '202009231411383', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:11:36', '2020-09-23 14:11:36');
INSERT INTO `order_pays` VALUES ('51', '8', '202009231412183', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:12:15', '2020-09-23 14:12:15');
INSERT INTO `order_pays` VALUES ('52', '8', '202009231412583', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:12:54', '2020-09-23 14:12:54');
INSERT INTO `order_pays` VALUES ('53', '8', '202009231426383', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:26:36', '2020-09-23 14:26:36');
INSERT INTO `order_pays` VALUES ('54', '8', '202009231426483', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:26:40', '2020-09-23 14:26:40');
INSERT INTO `order_pays` VALUES ('56', '8', '202009231427083', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:27:03', '2020-09-23 14:27:03');
INSERT INTO `order_pays` VALUES ('57', '8', '202009231428383', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:28:38', '2020-09-23 14:28:38');
INSERT INTO `order_pays` VALUES ('58', '8', '202009231428583', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:28:51', '2020-09-23 14:28:51');
INSERT INTO `order_pays` VALUES ('59', '8', '202009231430183', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:30:15', '2020-09-23 14:30:15');
INSERT INTO `order_pays` VALUES ('60', '8', '202009231430283', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:30:22', '2020-09-23 14:30:22');
INSERT INTO `order_pays` VALUES ('61', '8', '202009231430383', '', '3', '', 'o', '1.00', '0.00', '0', '2020-09-14 18:36:39', '2020-09-23 14:30:37', '2020-09-23 14:30:37');
INSERT INTO `order_pays` VALUES ('62', '1', '2020110721321124', '', '24', '', 'o', '15.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:32:14', '2020-11-07 21:32:14');
INSERT INTO `order_pays` VALUES ('63', '1', '2020110721323124', '', '24', '', 'o', '15.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:32:34', '2020-11-07 21:32:34');
INSERT INTO `order_pays` VALUES ('64', '8', '2020110721340825', '', '25', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:34:03', '2020-11-07 21:34:03');
INSERT INTO `order_pays` VALUES ('65', '8', '2020110721360825', '', '', '', 'o', '0.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:36:05', '2020-11-07 21:36:05');
INSERT INTO `order_pays` VALUES ('66', '8', '2020110721402826', '', '26', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:40:23', '2020-11-07 21:40:23');
INSERT INTO `order_pays` VALUES ('67', '8', '2020110721482827', '', '27', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:48:21', '2020-11-07 21:48:21');
INSERT INTO `order_pays` VALUES ('68', '8', '2020110721520828', '', '28', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-07 21:52:04', '2020-11-07 21:52:04');
INSERT INTO `order_pays` VALUES ('69', '1', '2020110913324129', '', '29', '', 'o', '11.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-09 13:32:48', '2020-11-09 13:32:48');
INSERT INTO `order_pays` VALUES ('70', '1', '2020110913325129', '', '29', '', 'o', '11.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-09 13:32:53', '2020-11-09 13:32:53');
INSERT INTO `order_pays` VALUES ('71', '1', '2020110913331129', '', '29', '', 'o', '11.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-09 13:33:19', '2020-11-09 13:33:19');
INSERT INTO `order_pays` VALUES ('72', '1', '2020110913332129', '', '29', '', 'o', '11.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-09 13:33:27', '2020-11-09 13:33:27');
INSERT INTO `order_pays` VALUES ('73', '1', '2020110913335129', '', '29', '', 'o', '11.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-09 13:33:51', '2020-11-09 13:33:51');
INSERT INTO `order_pays` VALUES ('74', '1', '2020111416115130', '', '30', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-14 16:11:57', '2020-11-14 16:11:57');
INSERT INTO `order_pays` VALUES ('75', '1', '2020111416120130', '', '30', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-14 16:12:02', '2020-11-14 16:12:02');
INSERT INTO `order_pays` VALUES ('76', '1', '2020111416123130', '', '30', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-14 16:12:37', '2020-11-14 16:12:37');
INSERT INTO `order_pays` VALUES ('77', '1', '2020111416124130', '', '30', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-14 16:12:46', '2020-11-14 16:12:46');
INSERT INTO `order_pays` VALUES ('78', '1', '2020111416125130', '', '30', '', 'o', '12.00', '0.00', '0', '2020-09-14 18:36:39', '2020-11-14 16:12:52', '2020-11-14 16:12:52');
INSERT INTO `order_pays` VALUES ('79', '1', '2020111416140130', '', '30', 'money', 'o', '12.00', '0.00', '1', '2020-09-14 18:36:39', '2020-11-14 16:14:09', '2020-11-14 16:14:10');
INSERT INTO `order_pays` VALUES ('80', '1', '2020111523321138', '', '38', 'money', 'o', '50.00', '50.00', '1', '2020-09-14 18:36:39', '2020-11-15 23:32:16', '2020-11-15 23:32:16');

-- ----------------------------
-- Table structure for order_settlements
-- ----------------------------
DROP TABLE IF EXISTS `order_settlements`;
CREATE TABLE `order_settlements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `settlement_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '结算单号',
  `total_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '订单金额',
  `settlement_price` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '结算金额',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '结算状态',
  `info` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of order_settlements
-- ----------------------------
INSERT INTO `order_settlements` VALUES ('4', '28', '8', '2', '202011081552111122', '12.00', '12.00', '1', '手动结算处理.|商品分佣-0', '2020-11-08 15:52:11', '2020-11-08 15:52:11');
INSERT INTO `order_settlements` VALUES ('5', '28', '8', '2', '202011081555103714', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 15:55:10', '2020-11-08 15:55:10');
INSERT INTO `order_settlements` VALUES ('6', '28', '8', '2', '202011081636209940', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 16:36:20', '2020-11-08 16:36:20');
INSERT INTO `order_settlements` VALUES ('10', '28', '8', '2', '202011081639354269', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 16:39:35', '2020-11-08 16:39:35');
INSERT INTO `order_settlements` VALUES ('11', '28', '8', '2', '202011081640561813', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 16:40:56', '2020-11-08 16:40:56');
INSERT INTO `order_settlements` VALUES ('12', '28', '8', '2', '202011081641036661', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 16:41:03', '2020-11-08 16:41:03');
INSERT INTO `order_settlements` VALUES ('13', '28', '8', '2', '202011082125125302', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 21:25:12', '2020-11-08 21:25:12');
INSERT INTO `order_settlements` VALUES ('18', '28', '8', '2', '202011082130082195', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-08 21:30:08', '2020-11-08 21:30:08');
INSERT INTO `order_settlements` VALUES ('19', '28', '8', '2', '202011091457087796', '12.00', '10.70', '1', '手动结算处理.|商品分佣-1.3', '2020-11-09 14:57:08', '2020-11-09 14:57:08');
INSERT INTO `order_settlements` VALUES ('20', '25', '8', '2', '202011091458546878', '12.00', '12.00', '1', '手动结算处理.|商品分佣-0', '2020-11-09 14:58:54', '2020-11-09 14:58:54');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '权限分组ID',
  `name` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `apis` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口名称',
  `content` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接口描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', '1', '列表', 'admins.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('2', '1', '添加', 'admins.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('3', '1', '详情', 'admins.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('4', '1', '修改', 'admins.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('5', '1', '删除', 'admins.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('6', '2', '列表', 'users.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('7', '2', '添加', 'users.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('8', '2', '详情', 'users.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('9', '2', '修改', 'users.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('10', '2', '删除', 'users.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('11', '3', '列表', 'roles.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('12', '3', '添加', 'roles.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('13', '3', '详情', 'roles.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('14', '3', '修改', 'roles.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('15', '3', '删除', 'roles.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('16', '4', '列表', 'menus.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('17', '4', '添加', 'menus.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('18', '4', '详情', 'menus.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('19', '4', '修改', 'menus.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('20', '4', '删除', 'menus.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('21', '5', '列表', 'permissions.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('22', '5', '添加', 'permissions.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('23', '5', '详情', 'permissions.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('24', '5', '修改', 'permissions.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('25', '5', '删除', 'permissions.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('26', '6', '列表', 'permission_groups.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('27', '6', '添加', 'permission_groups.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('28', '6', '详情', 'permission_groups.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('29', '6', '修改', 'permission_groups.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('30', '6', '删除', 'permission_groups.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('31', '7', '列表', 'configs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('32', '7', '添加', 'configs.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('33', '8', '列表', 'agreements.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('34', '8', '添加', 'agreements.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('35', '8', '详情', 'agreements.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('36', '8', '修改', 'agreements.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('37', '8', '删除', 'agreements.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('38', '9', '列表', 'articles.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('39', '9', '添加', 'articles.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('40', '9', '详情', 'articles.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('41', '9', '修改', 'articles.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('42', '9', '删除', 'articles.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('43', '10', '列表', 'expresses.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('44', '10', '添加', 'expresses.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('45', '10', '详情', 'expresses.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('46', '10', '修改', 'expresses.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('47', '10', '删除', 'expresses.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('48', '11', '列表', 'sms_logs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('49', '11', '删除', 'sms_logs.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('50', '12', '列表', 'sms_signs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('51', '12', '添加', 'sms_signs.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('52', '12', '详情', 'sms_signs.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('53', '12', '修改', 'sms_signs.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('54', '12', '删除', 'sms_signs.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('55', '13', '列表', 'goods_classes.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('56', '13', '添加', 'goods_classes.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('57', '13', '详情', 'goods_classes.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('58', '13', '修改', 'goods_classes.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('59', '13', '删除', 'goods_classes.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('60', '14', '列表', 'stores.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('61', '14', '详情', 'stores.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('62', '14', '修改', 'stores.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('63', '14', '删除', 'stores.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('64', '15', '列表', 'areas.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('65', '15', '添加', 'areas.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('66', '15', '详情', 'areas.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('67', '15', '修改', 'areas.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('68', '15', '删除', 'areas.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('69', '16', '列表', 'goods_brands.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('70', '16', '添加', 'goods_brands.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('71', '16', '详情', 'goods_brands.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('72', '16', '修改', 'goods_brands.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('73', '16', '删除', 'goods_brands.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('74', '17', '列表', 'goods.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('75', '17', '详情', 'goods.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('76', '17', '修改', 'goods.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('77', '17', '删除', 'goods.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('78', '18', '列表', 'adv_positions.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('79', '18', '添加', 'adv_positions.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('80', '18', '详情', 'adv_positions.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('81', '18', '修改', 'adv_positions.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('82', '18', '删除', 'adv_positions.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('83', '19', '列表', 'advs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('84', '19', '添加', 'advs.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('85', '19', '详情', 'advs.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('86', '19', '修改', 'advs.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('87', '19', '删除', 'advs.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('88', '20', '列表', 'orders.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('89', '20', '详情', 'orders.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('90', '20', '修改', 'orders.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('91', '20', '删除', 'orders.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('92', '21', '列表', 'integral_orders.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('93', '21', '详情', 'integral_orders.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('94', '21', '修改', 'integral_orders.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('95', '22', '列表', 'order_comments.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('96', '22', '详情', 'order_comments.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('97', '22', '删除', 'order_comments.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('98', '23', '列表', 'distribution_logs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('99', '24', '列表', 'order_settlements.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('100', '24', '添加', 'order_settlements.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('101', '24', '详情', 'order_settlements.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('102', '25', '列表', 'money_logs.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('103', '26', '列表', 'cashes.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('104', '26', '添加', 'cashes.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('105', '26', '修改', 'cashes.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('106', '27', '列表', 'integral_goods_classes.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('107', '27', '添加', 'integral_goods_classes.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('108', '27', '详情', 'integral_goods_classes.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('109', '27', '修改', 'integral_goods_classes.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('110', '27', '删除', 'integral_goods_classes.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('111', '28', '列表', 'integral_goods.index', '列表展示', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('112', '28', '添加', 'integral_goods.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('113', '28', '详情', 'integral_goods.show', '单个详情', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('114', '28', '修改', 'integral_goods.update', '数据修改', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('115', '28', '删除', 'integral_goods.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('116', '21', '添加', 'integral_orders.store', '数据添加', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('117', '21', '删除', 'integral_orders.destroy', '数据删除', '2020-11-17 17:27:47', '2020-11-17 17:27:47');
INSERT INTO `permissions` VALUES ('118', '4', '菜单缓存清理', 'menus.clear_cache', '', '2020-11-17 17:36:00', '2020-11-17 17:36:00');
INSERT INTO `permissions` VALUES ('119', '7', '配置中心图上传(Logo)', 'configs.config_logo', '', '2020-11-17 17:37:11', '2020-11-17 17:37:11');
INSERT INTO `permissions` VALUES ('120', '7', '配置中心上传(icon)', 'configs.config_icon', '', '2020-11-17 17:38:01', '2020-11-17 17:38:01');
INSERT INTO `permissions` VALUES ('121', '29', '富文本编辑器图上传', 'public.editor', '', '2020-11-17 17:39:24', '2020-11-17 17:39:24');
INSERT INTO `permissions` VALUES ('122', '13', '分类缩略图上传', 'goods_classes.goods_class_upload', '', '2020-11-17 17:40:27', '2020-11-17 17:41:00');
INSERT INTO `permissions` VALUES ('123', '13', '缓存清除商品分类', 'goods_classes.clear_cache', '', '2020-11-17 17:40:43', '2020-11-17 17:40:43');
INSERT INTO `permissions` VALUES ('124', '15', '缓存清除行政地址', 'areas.clear_cache', '', '2020-11-17 17:42:19', '2020-11-17 17:42:19');
INSERT INTO `permissions` VALUES ('125', '16', '品牌缩略图上传', 'goods_brands.goods_brand_upload', '', '2020-11-17 17:43:02', '2020-11-17 17:43:02');
INSERT INTO `permissions` VALUES ('126', '19', '缩略图上传', 'advs.adv_upload', '', '2020-11-17 17:44:14', '2020-11-17 17:44:14');
INSERT INTO `permissions` VALUES ('127', '28', '图片上传', 'integral_goods.goods_upload', '', '2020-11-17 17:45:00', '2020-11-17 17:45:00');
INSERT INTO `permissions` VALUES ('128', '29', '首页数据统计', 'statistics.all', '', '2020-11-17 17:46:55', '2020-11-17 17:46:55');
INSERT INTO `permissions` VALUES ('129', '29', '用户数据统计', 'statistics.user', '', '2020-11-17 17:47:18', '2020-11-17 17:47:18');
INSERT INTO `permissions` VALUES ('130', '29', '订单数据统计', 'statistics.order', '', '2020-11-17 17:47:36', '2020-11-17 17:47:36');
INSERT INTO `permissions` VALUES ('131', '29', '店铺数据统计', 'statistics.store', '', '2020-11-17 17:48:06', '2020-11-17 17:48:06');
INSERT INTO `permissions` VALUES ('132', '29', '支付数据统计', 'statistics.pay', '', '2020-11-17 17:48:22', '2020-11-17 17:48:22');

-- ----------------------------
-- Table structure for permission_groups
-- ----------------------------
DROP TABLE IF EXISTS `permission_groups`;
CREATE TABLE `permission_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限分组名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permission_groups
-- ----------------------------
INSERT INTO `permission_groups` VALUES ('1', '后台管理员', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('2', '平台用户', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('3', '角色管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('4', '菜单管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('5', '接口管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('6', '接口分组', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('7', '配置管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('8', '协议管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('9', '文章管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('10', '快递公司', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('11', '短信日志', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('12', '短信签名', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('13', '商品分类', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('14', '店铺管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('15', '行政地区', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('16', '商品品牌', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('17', '商品管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('18', '广告位管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('19', '广告管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('20', '订单管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('21', '积分订单', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('22', '订单评论', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('23', '分销日志', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('24', '订单结算', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('25', '用户资金日志', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('26', '提现管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('27', '积分商品分类', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('28', '积分商品管理', '2020-11-17 17:25:18', '2020-11-17 17:25:18');
INSERT INTO `permission_groups` VALUES ('29', '公共接口', '2020-11-17 17:32:08', '2020-11-17 17:32:12');

-- ----------------------------
-- Table structure for refunds
-- ----------------------------
DROP TABLE IF EXISTS `refunds`;
CREATE TABLE `refunds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '订单ID',
  `refund_type` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '售后类型 0 退款 1退货',
  `refund_verify` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '售后状态 0 处理中 1同意 2拒绝',
  `refund_step` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '售后步骤  0等待用户填写单号 1等待商家 2商家确定收货并发货 3用户确定收货订单成功',
  `delivery_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递订单号',
  `delivery_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '快递公司编码',
  `re_delivery_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商家快递订单号',
  `re_delivery_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商家快递公司编码',
  `refund_remark` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '售后原因',
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `refuse_remark` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '拒绝原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of refunds
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', '超级管理员', '2020-11-17 17:49:35', '2020-11-17 17:49:35');

-- ----------------------------
-- Table structure for role_menus
-- ----------------------------
DROP TABLE IF EXISTS `role_menus`;
CREATE TABLE `role_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `menu_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_menus
-- ----------------------------
INSERT INTO `role_menus` VALUES ('1', '1', '9', null, null);
INSERT INTO `role_menus` VALUES ('2', '1', '10', null, null);
INSERT INTO `role_menus` VALUES ('3', '1', '4', null, null);
INSERT INTO `role_menus` VALUES ('4', '1', '3', null, null);
INSERT INTO `role_menus` VALUES ('5', '1', '7', null, null);
INSERT INTO `role_menus` VALUES ('6', '1', '6', null, null);
INSERT INTO `role_menus` VALUES ('7', '1', '11', null, null);
INSERT INTO `role_menus` VALUES ('8', '1', '68', null, null);
INSERT INTO `role_menus` VALUES ('9', '1', '69', null, null);
INSERT INTO `role_menus` VALUES ('10', '1', '17', null, null);
INSERT INTO `role_menus` VALUES ('11', '1', '21', null, null);
INSERT INTO `role_menus` VALUES ('12', '1', '19', null, null);
INSERT INTO `role_menus` VALUES ('13', '1', '18', null, null);
INSERT INTO `role_menus` VALUES ('14', '1', '20', null, null);
INSERT INTO `role_menus` VALUES ('15', '1', '44', null, null);
INSERT INTO `role_menus` VALUES ('16', '1', '24', null, null);
INSERT INTO `role_menus` VALUES ('17', '1', '15', null, null);
INSERT INTO `role_menus` VALUES ('18', '1', '13', null, null);
INSERT INTO `role_menus` VALUES ('19', '1', '14', null, null);
INSERT INTO `role_menus` VALUES ('20', '1', '40', null, null);
INSERT INTO `role_menus` VALUES ('21', '1', '41', null, null);
INSERT INTO `role_menus` VALUES ('22', '1', '42', null, null);
INSERT INTO `role_menus` VALUES ('23', '1', '43', null, null);
INSERT INTO `role_menus` VALUES ('24', '1', '36', null, null);
INSERT INTO `role_menus` VALUES ('25', '1', '66', null, null);
INSERT INTO `role_menus` VALUES ('26', '1', '72', null, null);
INSERT INTO `role_menus` VALUES ('27', '1', '73', null, null);
INSERT INTO `role_menus` VALUES ('28', '1', '74', null, null);
INSERT INTO `role_menus` VALUES ('29', '1', '35', null, null);
INSERT INTO `role_menus` VALUES ('30', '1', '45', null, null);
INSERT INTO `role_menus` VALUES ('31', '1', '32', null, null);
INSERT INTO `role_menus` VALUES ('32', '1', '54', null, null);
INSERT INTO `role_menus` VALUES ('33', '1', '57', null, null);
INSERT INTO `role_menus` VALUES ('34', '1', '33', null, null);
INSERT INTO `role_menus` VALUES ('35', '1', '34', null, null);
INSERT INTO `role_menus` VALUES ('36', '1', '75', null, null);
INSERT INTO `role_menus` VALUES ('37', '1', '76', null, null);
INSERT INTO `role_menus` VALUES ('38', '1', '77', null, null);
INSERT INTO `role_menus` VALUES ('39', '1', '78', null, null);

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
INSERT INTO `role_permissions` VALUES ('1', '1', '121', null, null);
INSERT INTO `role_permissions` VALUES ('2', '1', '128', null, null);
INSERT INTO `role_permissions` VALUES ('3', '1', '129', null, null);
INSERT INTO `role_permissions` VALUES ('4', '1', '130', null, null);
INSERT INTO `role_permissions` VALUES ('5', '1', '131', null, null);
INSERT INTO `role_permissions` VALUES ('6', '1', '132', null, null);
INSERT INTO `role_permissions` VALUES ('7', '1', '111', null, null);
INSERT INTO `role_permissions` VALUES ('8', '1', '112', null, null);
INSERT INTO `role_permissions` VALUES ('9', '1', '113', null, null);
INSERT INTO `role_permissions` VALUES ('10', '1', '114', null, null);
INSERT INTO `role_permissions` VALUES ('11', '1', '115', null, null);
INSERT INTO `role_permissions` VALUES ('12', '1', '127', null, null);
INSERT INTO `role_permissions` VALUES ('13', '1', '103', null, null);
INSERT INTO `role_permissions` VALUES ('14', '1', '104', null, null);
INSERT INTO `role_permissions` VALUES ('15', '1', '105', null, null);
INSERT INTO `role_permissions` VALUES ('16', '1', '102', null, null);
INSERT INTO `role_permissions` VALUES ('17', '1', '99', null, null);
INSERT INTO `role_permissions` VALUES ('18', '1', '100', null, null);
INSERT INTO `role_permissions` VALUES ('19', '1', '101', null, null);
INSERT INTO `role_permissions` VALUES ('20', '1', '98', null, null);
INSERT INTO `role_permissions` VALUES ('21', '1', '95', null, null);
INSERT INTO `role_permissions` VALUES ('22', '1', '96', null, null);
INSERT INTO `role_permissions` VALUES ('23', '1', '97', null, null);
INSERT INTO `role_permissions` VALUES ('24', '1', '92', null, null);
INSERT INTO `role_permissions` VALUES ('25', '1', '93', null, null);
INSERT INTO `role_permissions` VALUES ('26', '1', '94', null, null);
INSERT INTO `role_permissions` VALUES ('27', '1', '116', null, null);
INSERT INTO `role_permissions` VALUES ('28', '1', '117', null, null);
INSERT INTO `role_permissions` VALUES ('29', '1', '88', null, null);
INSERT INTO `role_permissions` VALUES ('30', '1', '89', null, null);
INSERT INTO `role_permissions` VALUES ('31', '1', '90', null, null);
INSERT INTO `role_permissions` VALUES ('32', '1', '91', null, null);
INSERT INTO `role_permissions` VALUES ('33', '1', '83', null, null);
INSERT INTO `role_permissions` VALUES ('34', '1', '84', null, null);
INSERT INTO `role_permissions` VALUES ('35', '1', '85', null, null);
INSERT INTO `role_permissions` VALUES ('36', '1', '86', null, null);
INSERT INTO `role_permissions` VALUES ('37', '1', '87', null, null);
INSERT INTO `role_permissions` VALUES ('38', '1', '126', null, null);
INSERT INTO `role_permissions` VALUES ('39', '1', '78', null, null);
INSERT INTO `role_permissions` VALUES ('40', '1', '79', null, null);
INSERT INTO `role_permissions` VALUES ('41', '1', '80', null, null);
INSERT INTO `role_permissions` VALUES ('42', '1', '81', null, null);
INSERT INTO `role_permissions` VALUES ('43', '1', '82', null, null);
INSERT INTO `role_permissions` VALUES ('48', '1', '69', null, null);
INSERT INTO `role_permissions` VALUES ('49', '1', '70', null, null);
INSERT INTO `role_permissions` VALUES ('50', '1', '71', null, null);
INSERT INTO `role_permissions` VALUES ('51', '1', '72', null, null);
INSERT INTO `role_permissions` VALUES ('52', '1', '73', null, null);
INSERT INTO `role_permissions` VALUES ('53', '1', '125', null, null);
INSERT INTO `role_permissions` VALUES ('54', '1', '64', null, null);
INSERT INTO `role_permissions` VALUES ('55', '1', '65', null, null);
INSERT INTO `role_permissions` VALUES ('56', '1', '66', null, null);
INSERT INTO `role_permissions` VALUES ('57', '1', '67', null, null);
INSERT INTO `role_permissions` VALUES ('58', '1', '68', null, null);
INSERT INTO `role_permissions` VALUES ('59', '1', '124', null, null);
INSERT INTO `role_permissions` VALUES ('60', '1', '60', null, null);
INSERT INTO `role_permissions` VALUES ('61', '1', '61', null, null);
INSERT INTO `role_permissions` VALUES ('62', '1', '62', null, null);
INSERT INTO `role_permissions` VALUES ('63', '1', '63', null, null);
INSERT INTO `role_permissions` VALUES ('64', '1', '55', null, null);
INSERT INTO `role_permissions` VALUES ('65', '1', '56', null, null);
INSERT INTO `role_permissions` VALUES ('66', '1', '57', null, null);
INSERT INTO `role_permissions` VALUES ('67', '1', '58', null, null);
INSERT INTO `role_permissions` VALUES ('68', '1', '59', null, null);
INSERT INTO `role_permissions` VALUES ('69', '1', '122', null, null);
INSERT INTO `role_permissions` VALUES ('70', '1', '123', null, null);
INSERT INTO `role_permissions` VALUES ('71', '1', '50', null, null);
INSERT INTO `role_permissions` VALUES ('72', '1', '51', null, null);
INSERT INTO `role_permissions` VALUES ('73', '1', '52', null, null);
INSERT INTO `role_permissions` VALUES ('74', '1', '53', null, null);
INSERT INTO `role_permissions` VALUES ('75', '1', '54', null, null);
INSERT INTO `role_permissions` VALUES ('76', '1', '48', null, null);
INSERT INTO `role_permissions` VALUES ('77', '1', '49', null, null);
INSERT INTO `role_permissions` VALUES ('78', '1', '43', null, null);
INSERT INTO `role_permissions` VALUES ('79', '1', '44', null, null);
INSERT INTO `role_permissions` VALUES ('80', '1', '45', null, null);
INSERT INTO `role_permissions` VALUES ('81', '1', '46', null, null);
INSERT INTO `role_permissions` VALUES ('82', '1', '47', null, null);
INSERT INTO `role_permissions` VALUES ('83', '1', '38', null, null);
INSERT INTO `role_permissions` VALUES ('84', '1', '39', null, null);
INSERT INTO `role_permissions` VALUES ('85', '1', '40', null, null);
INSERT INTO `role_permissions` VALUES ('86', '1', '41', null, null);
INSERT INTO `role_permissions` VALUES ('87', '1', '42', null, null);
INSERT INTO `role_permissions` VALUES ('88', '1', '33', null, null);
INSERT INTO `role_permissions` VALUES ('89', '1', '34', null, null);
INSERT INTO `role_permissions` VALUES ('90', '1', '35', null, null);
INSERT INTO `role_permissions` VALUES ('91', '1', '36', null, null);
INSERT INTO `role_permissions` VALUES ('92', '1', '37', null, null);
INSERT INTO `role_permissions` VALUES ('93', '1', '31', null, null);
INSERT INTO `role_permissions` VALUES ('94', '1', '32', null, null);
INSERT INTO `role_permissions` VALUES ('95', '1', '119', null, null);
INSERT INTO `role_permissions` VALUES ('96', '1', '120', null, null);
INSERT INTO `role_permissions` VALUES ('97', '1', '26', null, null);
INSERT INTO `role_permissions` VALUES ('98', '1', '27', null, null);
INSERT INTO `role_permissions` VALUES ('99', '1', '28', null, null);
INSERT INTO `role_permissions` VALUES ('100', '1', '29', null, null);
INSERT INTO `role_permissions` VALUES ('101', '1', '30', null, null);
INSERT INTO `role_permissions` VALUES ('102', '1', '21', null, null);
INSERT INTO `role_permissions` VALUES ('103', '1', '22', null, null);
INSERT INTO `role_permissions` VALUES ('104', '1', '23', null, null);
INSERT INTO `role_permissions` VALUES ('105', '1', '24', null, null);
INSERT INTO `role_permissions` VALUES ('106', '1', '25', null, null);
INSERT INTO `role_permissions` VALUES ('107', '1', '16', null, null);
INSERT INTO `role_permissions` VALUES ('108', '1', '17', null, null);
INSERT INTO `role_permissions` VALUES ('109', '1', '18', null, null);
INSERT INTO `role_permissions` VALUES ('110', '1', '19', null, null);
INSERT INTO `role_permissions` VALUES ('111', '1', '20', null, null);
INSERT INTO `role_permissions` VALUES ('112', '1', '118', null, null);
INSERT INTO `role_permissions` VALUES ('113', '1', '11', null, null);
INSERT INTO `role_permissions` VALUES ('114', '1', '12', null, null);
INSERT INTO `role_permissions` VALUES ('115', '1', '13', null, null);
INSERT INTO `role_permissions` VALUES ('116', '1', '14', null, null);
INSERT INTO `role_permissions` VALUES ('117', '1', '15', null, null);
INSERT INTO `role_permissions` VALUES ('118', '1', '6', null, null);
INSERT INTO `role_permissions` VALUES ('119', '1', '7', null, null);
INSERT INTO `role_permissions` VALUES ('120', '1', '8', null, null);
INSERT INTO `role_permissions` VALUES ('121', '1', '9', null, null);
INSERT INTO `role_permissions` VALUES ('122', '1', '10', null, null);
INSERT INTO `role_permissions` VALUES ('123', '1', '1', null, null);
INSERT INTO `role_permissions` VALUES ('124', '1', '2', null, null);
INSERT INTO `role_permissions` VALUES ('125', '1', '3', null, null);
INSERT INTO `role_permissions` VALUES ('126', '1', '4', null, null);
INSERT INTO `role_permissions` VALUES ('127', '1', '5', null, null);
INSERT INTO `role_permissions` VALUES ('128', '1', '106', null, null);
INSERT INTO `role_permissions` VALUES ('129', '1', '107', null, null);
INSERT INTO `role_permissions` VALUES ('130', '1', '108', null, null);
INSERT INTO `role_permissions` VALUES ('131', '1', '109', null, null);
INSERT INTO `role_permissions` VALUES ('132', '1', '110', null, null);
INSERT INTO `role_permissions` VALUES ('133', '1', '74', null, null);
INSERT INTO `role_permissions` VALUES ('134', '1', '75', null, null);
INSERT INTO `role_permissions` VALUES ('135', '1', '76', null, null);
INSERT INTO `role_permissions` VALUES ('136', '1', '77', null, null);

-- ----------------------------
-- Table structure for seckills
-- ----------------------------
DROP TABLE IF EXISTS `seckills`;
CREATE TABLE `seckills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '商品ID',
  `discount` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '折扣',
  `start_time` timestamp NOT NULL DEFAULT '2020-11-16 12:11:23' COMMENT '开始时间',
  `end_time` timestamp NOT NULL DEFAULT '2020-11-21 12:11:23' COMMENT '结束',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of seckills
-- ----------------------------
INSERT INTO `seckills` VALUES ('1', '2', '17', '10.00', '2020-11-18 12:00:00', '2020-11-18 13:00:00', '2020-11-16 12:48:21', '2020-11-18 12:28:49');

-- ----------------------------
-- Table structure for sms_logs
-- ----------------------------
DROP TABLE IF EXISTS `sms_logs`;
CREATE TABLE `sms_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信类型名称',
  `content` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '发送状态',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '发送ID',
  `error_msg` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '错误信息',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sms_logs
-- ----------------------------
INSERT INTO `sms_logs` VALUES ('3', '15073010919', 'register', '2124', '0', '127.0.0.1', 'JSON参数不合法', '2020-09-10 17:17:43', '2020-09-10 17:17:44');
INSERT INTO `sms_logs` VALUES ('4', '15073010919', 'register', '1983', '1', '127.0.0.1', '', '2020-09-10 17:21:24', '2020-09-10 17:21:24');
INSERT INTO `sms_logs` VALUES ('5', '15073010919', 'register', '8489', '1', '127.0.0.1', '', '2020-09-10 17:22:31', '2020-09-10 17:22:31');
INSERT INTO `sms_logs` VALUES ('6', '17369417688', 'register', '9300', '1', '127.0.0.1', '', '2020-09-10 17:22:50', '2020-09-10 17:22:50');
INSERT INTO `sms_logs` VALUES ('7', '17369417688', 'register', '8162', '1', '127.0.0.1', '', '2020-09-10 17:37:53', '2020-09-10 17:30:53');
INSERT INTO `sms_logs` VALUES ('8', '17369417688', 'forget_password', '5719', '1', '127.0.0.1', '', '2020-09-10 18:05:41', '2020-09-10 18:05:41');
INSERT INTO `sms_logs` VALUES ('9', '15073010918', 'register', '7066', '0', '127.0.0.1', 'Client error: `GET http://dysmsapi.aliyuncs.com?Re', '2021-01-12 13:37:19', '2021-01-12 13:37:19');

-- ----------------------------
-- Table structure for sms_signs
-- ----------------------------
DROP TABLE IF EXISTS `sms_signs`;
CREATE TABLE `sms_signs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `val` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模版',
  `content` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sms_signs
-- ----------------------------
INSERT INTO `sms_signs` VALUES ('1', 'register', '代发兔', 'SMS_186000191', '注册', '2020-09-10 15:21:21', '2020-09-10 15:21:21');
INSERT INTO `sms_signs` VALUES ('2', 'forget_password', '代发兔', 'SMS_186000190', '忘记密码', '2020-09-10 15:37:57', '2020-09-10 15:37:57');
INSERT INTO `sms_signs` VALUES ('3', 'edit_user', '代发兔', 'SMS_186000189', '修改资料', '2020-09-10 15:38:25', '2020-09-10 15:38:25');

-- ----------------------------
-- Table structure for stores
-- ----------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `store_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '神秘商户' COMMENT '商户名称',
  `store_logo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺LOGO',
  `store_face_image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺门面',
  `store_mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺电话',
  `store_description` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '该商户很懒，什么也没留下' COMMENT '店铺描述',
  `store_slide` varchar(550) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺幻灯片',
  `store_mobile_slide` varchar(550) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺app幻灯片',
  `store_company_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司名称',
  `province_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '省ID',
  `city_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '市ID',
  `region_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '区ID',
  `store_lat` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '39.92' COMMENT '纬度',
  `store_lng` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '116.46' COMMENT '经度',
  `area_info` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地区信息',
  `store_address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `business_license` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '营业执照',
  `business_license_no` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '营业执照号码',
  `legal_person` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '法人',
  `store_phone` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '法人电话',
  `id_card_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证号码',
  `id_card_t` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证上',
  `id_card_b` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证下',
  `emergency_contact` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '紧急联系人',
  `emergency_contact_phone` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '紧急联系人电话',
  `store_money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '账号余额',
  `store_frozen_money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '冻结资金',
  `store_status` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '店铺状态',
  `store_verify` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '店铺审核状态',
  `store_refuse_info` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `after_sale_service` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '售后服务',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of stores
-- ----------------------------
INSERT INTO `stores` VALUES ('2', '1', '青梧商城', 'http://127.0.0.1:8000/storage/store_join/2/2020-07-25/j1ZydoBpEO4iCQ1niSDuInX083uDRIydwcBk1MPx.png', '', '18888888888', '该商户很懒，什么也没留下', '', 'http://localhost:8000/storage/store_slide/1/2020-10-07/CQ6siACC0fK2Okgou2HWcADxWUN2RX9BpFI4bli4.jpg,http://localhost:8000/storage/store_slide/1/2020-10-07/yMz3U69AYjGssCNzdeJcQnd9p5jVo4saMLQ10O5e.png,http://localhost:8000/storage/store_slide/1/2020-10-07/R15PFrrSLegpJ5Jsr3X4piFyUxy0Y3Xuo9SxHzsO.png', '岳阳青梧信息', '18', '199', '2091', '29.436446', '112.967179', '湖南省 岳阳市 君山区', '工业园208', 'http://127.0.0.1:8000/storage/store_join/2/2020-07-23/mTnLdIS1SDTDkco1KHfIaDn81K3f67VVb3EPi2Pu.jpeg', '123123124', '打工', '18888888888', '430xxxx', 'http://127.0.0.1:8000/storage/store_join/2/2020-07-23/A9winx4aGzaAv814q3c80aS4mClGgTtTe8CCKYKq.jpeg', 'http://127.0.0.1:8000/storage/store_join/2/2020-07-23/gLmGTDKwL0wSo7uic9XQ6Z7WiHFrjNb7hkVvq9sD.jpeg', '发哥', '18888888888', '54.80', '0.00', '1', '3', '被拒绝了', '<p>18888888888</p>', '2020-07-21 18:37:01', '2020-11-09 14:58:55');

-- ----------------------------
-- Table structure for store_classes
-- ----------------------------
DROP TABLE IF EXISTS `store_classes`;
CREATE TABLE `store_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '店铺ID',
  `class_id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '栏目ID',
  `class_name` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '栏目名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of store_classes
-- ----------------------------
INSERT INTO `store_classes` VALUES ('2', '2', '[[\"1\",\"2\",\"4\"],[\"2\",\"8\",\"26\"],[\"3\",\"11\",\"29\"]]', '[[\"\\u670d\\u88c5\",\"\\u8863\\u670d\",\"\\u65e5\\u7cfb\"],[\"\\u5bb6\\u7528\\u7535\\u5668\",\"\\u5927\\u5bb6\\u7535\",\"\\u7a7a\\u8c03\"],[\"\\u56fe\\u4e66 & \\u97f3\\u50cf & \\u7535\\u5b50\\u4e66\",\"\\u56fe\\u4e66\",\"\\u7ae5\\u8bdd\"]]', null, '2020-08-02 18:19:39');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `pay_password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付密码',
  `nickname` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '性别',
  `avatar` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电话',
  `email` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '余额',
  `frozen_money` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '冻结资金',
  `integral` decimal(9,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '积分',
  `inviter_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '邀请人ID',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT '登陆IP',
  `login_time` timestamp NOT NULL DEFAULT '2020-07-05 14:16:47' COMMENT '登陆时间',
  `last_login_time` timestamp NOT NULL DEFAULT '2020-07-05 14:16:47' COMMENT '最后一次登陆',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'xxx', '$2y$10$TnUza7yGyIObzC2PSXWbWOaMr3EItm4Z8B.bSIAWeIyObYgJ7BgZe', 'eeafb716f93fa090d7716749a6eefa72', 'UF0神秘人', '1', 'http://127.0.0.1:8000/storage/avatars/1/2021-01-15/QIIZdR03XXyQGx8kZMJmHVmpbkHWChCAs3FjuaQ3.png', '18888888888', '', '40.60', '0.00', '42.00', '0', '127.0.0.1', '2021-01-18 16:16:48', '2021-01-18 15:11:54', '2020-09-10 14:28:40', '2021-01-18 16:16:48');
INSERT INTO `users` VALUES ('8', '1736941768', '$2y$10$9GTb5t36Y8Dlj1rxSlhUxeqJV0AAscsYE7wL4ClEhG29GDVXDJMfO', '4c797f0acb616d6a8894dc5bb8a337d3', '17369417688_102', '0', '', '17369417688', '', '52.00', '0.00', '30.00', '1', '127.0.0.1', '2020-11-23 11:01:03', '2020-11-19 16:07:47', '2020-09-10 17:45:09', '2020-11-23 11:01:03');

-- ----------------------------
-- Table structure for user_checks
-- ----------------------------
DROP TABLE IF EXISTS `user_checks`;
CREATE TABLE `user_checks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `card_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '身份证号',
  `card_t` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '身份证图片上',
  `card_b` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '身份证图片下',
  `bank_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_checks
-- ----------------------------
INSERT INTO `user_checks` VALUES ('3', '8', '李四', '43092115621102011145', 'http://127.0.0.1:8000/storage/user_check/8/2020-09-23/Zy8SRXSf0MeykvrYa6JsrvoK3cIpnWGmoxhFQ0Tb.jpg', 'http://127.0.0.1:8000/storage/user_check/8/2020-09-23/fUGdmekTb8BnLpJ5IodhcHVw5R6R0pITE2swCUEa.jpg', '43092115621102011145', '中国工商银行', '2020-09-23 15:45:58', '2020-09-23 15:49:37');
INSERT INTO `user_checks` VALUES ('4', '1', '234', '43092115621102011145', 'http://127.0.0.1:8000/storage/user_check/1/2021-01-18/VOisj1PnDBvL18NitdYS4scT06O3B6X2rnnfhPHu.png', 'http://127.0.0.1:8000/storage/user_check/1/2021-01-18/2cy5rjwmAP4NS2YMkbMS6ipBQIAaLszxvHckFr6V.png', '43092115621102011145', '234', '2021-01-18 16:49:43', '2021-01-18 16:49:43');

-- ----------------------------
-- Table structure for user_wechats
-- ----------------------------
DROP TABLE IF EXISTS `user_wechats`;
CREATE TABLE `user_wechats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
  `nickname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '普通用户性别，1 为男性，2 为女性',
  `headimgurl` tinyint(3) unsigned NOT NULL COMMENT '用户头像',
  `openid` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信单一平台指定标识',
  `unionid` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信平台唯一标识',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_wechats
-- ----------------------------
