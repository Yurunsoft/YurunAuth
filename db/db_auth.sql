/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1_3306
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : db_auth

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-10-19 17:12:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ya_role
-- ----------------------------
DROP TABLE IF EXISTS `ya_role`;
CREATE TABLE `ya_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级角色ID。顶级角色为0',
  `name` varchar(255) DEFAULT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ya_role
-- ----------------------------

-- ----------------------------
-- Table structure for ya_role_rule
-- ----------------------------
DROP TABLE IF EXISTS `ya_role_rule`;
CREATE TABLE `ya_role_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `rule_id` int(10) unsigned NOT NULL COMMENT '权限规则ID',
  PRIMARY KEY (`id`),
  KEY `rule_id` (`rule_id`),
  KEY `role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ya_role_rule
-- ----------------------------

-- ----------------------------
-- Table structure for ya_rule
-- ----------------------------
DROP TABLE IF EXISTS `ya_rule`;
CREATE TABLE `ya_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级权限ID，顶级权限为0',
  `name` varchar(64) NOT NULL COMMENT '权限名称',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ya_rule
-- ----------------------------

-- ----------------------------
-- Table structure for ya_user_role
-- ----------------------------
DROP TABLE IF EXISTS `ya_user_role`;
CREATE TABLE `ya_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ya_user_role
-- ----------------------------

-- ----------------------------
-- Table structure for ya_user_rule
-- ----------------------------
DROP TABLE IF EXISTS `ya_user_rule`;
CREATE TABLE `ya_user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `rule_id` int(10) unsigned NOT NULL COMMENT '权限规则ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `rule_id` (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ya_user_rule
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
