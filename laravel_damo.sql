/*
 Navicat Premium Data Transfer

 Source Server         : 虚拟机homestead数据库
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : 192.168.10.10:3306
 Source Schema         : laravel_damo

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 20/05/2021 11:09:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for info_users
-- ----------------------------
DROP TABLE IF EXISTS `info_users`;
CREATE TABLE `info_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id编号',
  `user_account` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户账号',
  `nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户密码',
  `user_authority` tinyint(1) NULL DEFAULT 0 COMMENT '用户权限:0，1，2...',
  `creator` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'admin' COMMENT '该用户创建人',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户头像地址',
  `forbidden` tinyint(1) NULL DEFAULT 0 COMMENT '账号状态：0  启用， 1 冻结',
  `is_update_password` tinyint(1) NULL DEFAULT 0 COMMENT '用户是否修改过密码：0 未修改，1 已修改',
  `created_at` timestamp(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp(0) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of info_users
-- ----------------------------
INSERT INTO `info_users` VALUES (1, 'admin', 'ohhh', '$2y$10$uR4lv5KjcdjTIg6uRJTeuenWbqAQ/F6rQnDGxnTcQy5RkSYRIfiJq', 1, 'admin', NULL, 0, 0, '2021-01-14 06:43:54', '2021-01-14 06:43:54');

SET FOREIGN_KEY_CHECKS = 1;
