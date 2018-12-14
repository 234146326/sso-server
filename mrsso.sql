/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : mrsso

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2015-03-11 09:49:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_service_ticket`
-- ----------------------------
DROP TABLE IF EXISTS `pre_service_ticket`;
CREATE TABLE `pre_service_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pre_service_ticket
-- ----------------------------

-- ----------------------------
-- Table structure for `pre_users`
-- ----------------------------
DROP TABLE IF EXISTS `pre_users`;
CREATE TABLE `pre_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '用户密码',
  `apitoken` varchar(255) NOT NULL COMMENT 'ssoapi对外密钥',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`username`) USING BTREE,
  KEY `password` (`password`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='用户表_by_jiang';

-- ----------------------------
-- Records of pre_users
-- ----------------------------
INSERT INTO `pre_users` VALUES ('1', 'admin', '96e79218965eb72c92a549dd5a330112', 'oyzzO7YxmgJHlAfdK5HaZMscegJPcTrw5drPQRS6bjlfAkTB6NELPvqpc12q');
