/*
Navicat MySQL Data Transfer

Source Server         : DIBA
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : application

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-04-27 11:05:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `permission`
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(45) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES ('1', 'index', '1');
INSERT INTO `permission` VALUES ('5', 'add', '1');
INSERT INTO `permission` VALUES ('6', 'edit', '1');
INSERT INTO `permission` VALUES ('7', 'delete', '1');
INSERT INTO `permission` VALUES ('8', 'update', '1');
INSERT INTO `permission` VALUES ('9', 'sandbox', '1');
INSERT INTO `permission` VALUES ('10', 'change-password', '2');
INSERT INTO `permission` VALUES ('11', 'index', '3');
INSERT INTO `permission` VALUES ('12', 'index', '4');

-- ----------------------------
-- Table structure for `resource`
-- ----------------------------
DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of resource
-- ----------------------------
INSERT INTO `resource` VALUES ('1', 'Application\\Controller\\Index');
INSERT INTO `resource` VALUES ('2', 'Users\\Controller\\User');
INSERT INTO `resource` VALUES ('3', 'Application\\Controller\\Inventory');
INSERT INTO `resource` VALUES ('4', 'Inventario\\Controller\\Skeleton');

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', 'Manager', 'Active');
INSERT INTO `role` VALUES ('2', 'Employee', 'Active');
INSERT INTO `role` VALUES ('3', 'Customer', 'Active');
INSERT INTO `role` VALUES ('4', 'Guest', 'Active');

-- ----------------------------
-- Table structure for `role_permission`
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_permission
-- ----------------------------
INSERT INTO `role_permission` VALUES ('1', '1', '1');
INSERT INTO `role_permission` VALUES ('9', '1', '5');
INSERT INTO `role_permission` VALUES ('10', '1', '6');
INSERT INTO `role_permission` VALUES ('11', '2', '7');
INSERT INTO `role_permission` VALUES ('12', '3', '8');
INSERT INTO `role_permission` VALUES ('13', '4', '7');
INSERT INTO `role_permission` VALUES ('14', '1', '9');
INSERT INTO `role_permission` VALUES ('15', '1', '10');
INSERT INTO `role_permission` VALUES ('16', '1', '11');
INSERT INTO `role_permission` VALUES ('17', '1', '12');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(101) NOT NULL,
  `password` varchar(45) NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `login_attempt_time` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `last_signed_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'eulernunez@gmail.com', '55c15b94ab5e279e471ccbb4ac7857780929b826', '0', '0', '', '', 'Active', null);
INSERT INTO `users` VALUES ('2', 'kaushal@osscube.com', 'd4cb903787695a544172af6f0af88fef583a81c8', '0', '0', '', '', 'Active', null);
INSERT INTO `users` VALUES ('3', 'mohit.singh@osscube.com', 'd4cb903787695a544172af6f0af88fef583a81c8', '0', '0', 'Mohit', 'Kumar', 'Active', null);
INSERT INTO `users` VALUES ('4', 'arvind.singh@osscube.com', 'd4cb903787695a544172af6f0af88fef583a81c8', '0', '0', 'Arvind', 'Singh', 'Active', null);
INSERT INTO `users` VALUES ('5', 'tarun.singhal@osscube.com', 'd4cb903787695a544172af6f0af88fef583a81c8', '0', '0', 'Tarun', 'Singhal', 'Active', null);

-- ----------------------------
-- Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES ('1', '1', '1');
INSERT INTO `user_role` VALUES ('2', '2', '2');
INSERT INTO `user_role` VALUES ('3', '3', '3');
INSERT INTO `user_role` VALUES ('4', '4', '4');
INSERT INTO `user_role` VALUES ('5', '5', '5');
