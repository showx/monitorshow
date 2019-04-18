/*
 Navicat MySQL Data Transfer

 Source Server         : docker
 Source Server Type    : MySQL
 Source Server Version : 50642
 Source Host           : 127.0.0.1
 Source Database       : phpshow

 Target Server Type    : MySQL
 Target Server Version : 50642
 File Encoding         : utf-8

 Date: 04/18/2019 15:16:35 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `mon_server`
-- ----------------------------
DROP TABLE IF EXISTS `mon_server`;
CREATE TABLE `mon_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL COMMENT '服务器id',
  `server_name` varchar(50) NOT NULL DEFAULT '' COMMENT '服务器名称',
  `cpu` varchar(20) NOT NULL DEFAULT '0' COMMENT 'cpu占比',
  `memory` varchar(20) NOT NULL DEFAULT '0' COMMENT '内存占比',
  `disk` varchar(20) NOT NULL DEFAULT '0' COMMENT '磁盘占比',
  `date` date NOT NULL COMMENT '日期',
  `times` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间戳',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remark` varchar(100) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
