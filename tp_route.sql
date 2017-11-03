/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : tp_route

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-11-03 13:43:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_access
-- ----------------------------
DROP TABLE IF EXISTS `tp_access`;
CREATE TABLE `tp_access` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `rule_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '权限规则分类,请加应用前缀,如admin_',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=353 DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
-- Records of tp_access
-- ----------------------------
INSERT INTO `tp_access` VALUES ('339', '2', 'xtgl', 'admin_url');
INSERT INTO `tp_access` VALUES ('340', '2', '/admin/admins/index', 'admin_url');
INSERT INTO `tp_access` VALUES ('341', '2', '/admin/admins/add', 'admin_url');
INSERT INTO `tp_access` VALUES ('342', '2', '/admin/admins/edit', 'admin_url');
INSERT INTO `tp_access` VALUES ('343', '2', '/admin/role/index', 'admin_url');
INSERT INTO `tp_access` VALUES ('344', '2', '/admin/access/authorize', 'admin_url');
INSERT INTO `tp_access` VALUES ('345', '2', '/admin/role/add', 'admin_url');
INSERT INTO `tp_access` VALUES ('346', '2', '/admin/role/edit', 'admin_url');
INSERT INTO `tp_access` VALUES ('347', '2', '/admin/menu/index', 'admin_url');
INSERT INTO `tp_access` VALUES ('348', '2', '/admin/menu/add', 'admin_url');
INSERT INTO `tp_access` VALUES ('349', '2', '/admin/menu/edit', 'admin_url');
INSERT INTO `tp_access` VALUES ('350', '2', 'dygl', 'admin_url');
INSERT INTO `tp_access` VALUES ('351', '2', '/admin/guide/index', 'admin_url');
INSERT INTO `tp_access` VALUES ('352', '2', '/admin/user/index', 'admin_url');

-- ----------------------------
-- Table structure for tp_admins
-- ----------------------------
DROP TABLE IF EXISTS `tp_admins`;
CREATE TABLE `tp_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `account` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `salt` varchar(6) NOT NULL DEFAULT '' COMMENT '密码盐',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `login_time` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `error_num` int(2) NOT NULL DEFAULT '0' COMMENT '登录错误次数',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admins
-- ----------------------------
INSERT INTO `tp_admins` VALUES ('1', '1', 'admin', 'de5c085aaf9eef48b36ef0aab2c878db', 'pengcx', '13225962996', '9VJPzm', '1509089597', '1509095247', '1509668571', '127.0.0.1', '0', '1');
INSERT INTO `tp_admins` VALUES ('2', '2', 'pengcx', 'bb3d48fc85168b7607973082563c1cf2', 'pengcx', '13225962992', '4yKihe', '1509583031', '0', '1509610604', '127.0.0.1', '0', '1');

-- ----------------------------
-- Table structure for tp_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_log`;
CREATE TABLE `tp_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '操作链接',
  `data` text,
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_log
-- ----------------------------
INSERT INTO `tp_admin_log` VALUES ('1', '1', '/admin/guide/add', '{\"id\":\"\",\"name\":\"\\u6d4b\\u8bd5\\u554a\",\"mobile\":\"13225962995\",\"thumb\":\"http:\\/\\/local.tp_route.com\\/uploads\\\\20171102\\\\33a4992572ff74df2ba8cc3d077433fa.jpg\",\"status\":\"1\"}', '1509601035');
INSERT INTO `tp_admin_log` VALUES ('3', '1', '/admin/guide/add', '{\"id\":\"\",\"name\":\"\\u8fd8\\u6709\\u8fd9\\u6837\",\"mobile\":\"13225962998\",\"thumb\":\"http:\\/\\/local.tp_route.com\\/uploads\\\\20171102\\\\20f2fcfbbd3fef3579df2e99c0dc4bbb.jpg\",\"photo\":[\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509601101123471.jpg\"],\"status\":\"1\"}', '1509601105');
INSERT INTO `tp_admin_log` VALUES ('5', '1', '/admin/guide/add', '{\"id\":\"\",\"name\":\"\\u53cd\\u5bf9\\u65b9\\u7b54\\u590d\",\"mobile\":\"13225962999\",\"thumb\":\"http:\\/\\/local.tp_route.com\\/uploads\\\\20171102\\\\64a700e837f1a46f4dda5b85232f57d4.jpg\",\"photo\":[\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509601289107678.jpg\"],\"status\":\"1\"}', '1509601293');
INSERT INTO `tp_admin_log` VALUES ('6', '1', '/admin/guide/edit?id=4', '{\"id\":\"4\"}', '1509601442');
INSERT INTO `tp_admin_log` VALUES ('7', '1', '/admin/guide/edit?id=4', '{\"id\":\"4\",\"name\":\"\\u53ef\\u4ee5\\u7684\",\"mobile\":\"13225962990\",\"thumb\":\"http:\\/\\/local.tp_route.com\\/uploads\\\\20171102\\\\14c0b4bd5a958cc36939f4b1545151f4.jpg\",\"photo\":[\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509599205914597.jpg\",\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509599205582521.jpg\"],\"status\":\"1\"}', '1509601447');
INSERT INTO `tp_admin_log` VALUES ('8', '1', '/admin/menu/edit/id/3.html', '{\"id\":\"3\",\"name\":\"\\u6587\\u7ae0\\u7ba1\\u7406\",\"pid\":\"0\",\"url\":\"wzgl\",\"desc\":\"\",\"list_order\":\"99\",\"status\":\"1\",\"ismenu\":\"1\",\"islog\":\"0\"}', '1509601802');
INSERT INTO `tp_admin_log` VALUES ('9', '1', '/admin/menu/edit/id/9.html', '{\"id\":\"9\",\"name\":\"\\u6587\\u7ae0\\u5217\\u8868\",\"pid\":\"3\",\"url\":\"\\/admin\\/article\\/index\",\"desc\":\"\",\"list_order\":\"99\",\"status\":\"1\",\"ismenu\":\"1\",\"islog\":\"0\"}', '1509601827');
INSERT INTO `tp_admin_log` VALUES ('10', '1', '/admin/admins/edit?id=2', '{\"id\":\"2\"}', '1509605192');
INSERT INTO `tp_admin_log` VALUES ('11', '1', '/admin/menu/add/pid/9.html', '{\"id\":\"\",\"name\":\"\\u6dfb\\u52a0\",\"pid\":\"9\",\"url\":\"\\/admin\\/article\\/add\",\"desc\":\"\",\"list_order\":\"\",\"status\":\"1\",\"ismenu\":\"0\",\"islog\":\"1\"}', '1509605266');
INSERT INTO `tp_admin_log` VALUES ('12', '1', '/admin/menu/add/pid/9.html', '{\"id\":\"\",\"name\":\"\\u7f16\\u8f91\",\"pid\":\"9\",\"url\":\"\\/admin\\/article\\/edit\",\"desc\":\"\",\"list_order\":\"\",\"status\":\"1\",\"ismenu\":\"0\",\"islog\":\"1\"}', '1509605296');
INSERT INTO `tp_admin_log` VALUES ('13', '1', '/admin/menu/add/pid/9.html', '{\"id\":\"\",\"name\":\"\\u5220\\u9664\",\"pid\":\"9\",\"url\":\"\\/admin\\/article\\/del\",\"desc\":\"\",\"list_order\":\"\",\"status\":\"1\",\"ismenu\":\"0\",\"islog\":\"1\"}', '1509605329');
INSERT INTO `tp_admin_log` VALUES ('14', '1', '/admin/article/edit?id=1', '{\"id\":\"1\"}', '1509605350');
INSERT INTO `tp_admin_log` VALUES ('15', '1', '/admin/article/edit?id=1', '{\"id\":\"1\"}', '1509606569');
INSERT INTO `tp_admin_log` VALUES ('16', '1', '/admin/access/authorize?id=2', '{\"id\":\"2\"}', '1509610147');
INSERT INTO `tp_admin_log` VALUES ('17', '2', '/admin/admins/edit?id=2', '{\"id\":\"2\"}', '1509610411');
INSERT INTO `tp_admin_log` VALUES ('18', '1', '/admin/admins/add', '{\"id\":\"\",\"account\":\"trtrrtr\",\"realname\":\"trtrtrt\",\"mobile\":\"13225962995\",\"password\":\"gfgfgfg\",\"role_id\":\"2\",\"status\":\"1\"}', '1509610516');
INSERT INTO `tp_admin_log` VALUES ('19', '1', '/admin/admins/del?id=3', '{\"id\":\"3\"}', '1509610523');
INSERT INTO `tp_admin_log` VALUES ('20', '1', '/admin/admins/edit?id=2', '{\"id\":\"2\"}', '1509611521');

-- ----------------------------
-- Table structure for tp_article
-- ----------------------------
DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` text COMMENT '文章内容',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1显示 0隐藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_article
-- ----------------------------
INSERT INTO `tp_article` VALUES ('1', '也是奇怪', '<p><em><strong>费大幅度发的说法是</strong></em><br/></p>', '1509598958', '0', '1');

-- ----------------------------
-- Table structure for tp_guide
-- ----------------------------
DROP TABLE IF EXISTS `tp_guide`;
CREATE TABLE `tp_guide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导游姓名',
  `grade` tinyint(2) NOT NULL DEFAULT '1' COMMENT '等级 1，2，3，4.....',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `photo` text COMMENT '照片',
  `charge` int(10) NOT NULL DEFAULT '0' COMMENT '服务费',
  `mobile` varchar(12) NOT NULL DEFAULT '' COMMENT '手机号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  `delete_time` int(10) DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`),
  KEY `dx_id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_guide
-- ----------------------------
INSERT INTO `tp_guide` VALUES ('3', '辅导辅导', '1', 'http://local.tp_route.com/uploads\\20171101\\160413bccff4583805b068e5ff924fe3.jpg', '{\"0\":\"\\/ueditor\\/php\\/upload\\/image\\/20171101\\/1509520084137193.jpg\",\"1\":\"\\/ueditor\\/php\\/upload\\/image\\/20171101\\/1509520084518791.jpg\"}', '0', '13225962992', '1509520089', '0', '1', '1509598958');
INSERT INTO `tp_guide` VALUES ('4', '可以的', '1', 'http://local.tp_route.com/uploads\\20171102\\14c0b4bd5a958cc36939f4b1545151f4.jpg', '{\"0\":\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509599205914597.jpg\",\"1\":\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509599205582521.jpg\"}', '0', '13225962990', '1509599213', '1509601447', '1', null);
INSERT INTO `tp_guide` VALUES ('5', '还有这样', '1', 'http://local.tp_route.com/uploads\\20171102\\20f2fcfbbd3fef3579df2e99c0dc4bbb.jpg', '{\"0\":\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509601101123471.jpg\"}', '0', '13225962998', '1509601105', '0', '1', null);
INSERT INTO `tp_guide` VALUES ('6', '反对方答复', '1', 'http://local.tp_route.com/uploads\\20171102\\64a700e837f1a46f4dda5b85232f57d4.jpg', '{\"0\":\"\\/ueditor\\/php\\/upload\\/image\\/20171102\\/1509601289107678.jpg\"}', '0', '13225962999', '1509601293', '0', '1', null);

-- ----------------------------
-- Table structure for tp_menu
-- ----------------------------
DROP TABLE IF EXISTS `tp_menu`;
CREATE TABLE `tp_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT '链接',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1显示 0隐藏',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `list_order` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '菜单等级',
  `ismenu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是后台菜单 1是 0不是',
  `islog` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否需要记录操作日志  1 是 0 否',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_menu
-- ----------------------------
INSERT INTO `tp_menu` VALUES ('1', 'xtgl', '系统管理', '', '1', 'smgui', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('2', 'dygl', '导游管理', '', '1', '', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('3', 'wzgl', '文章管理', '', '1', '', '99', '0', '0', '1', '0');
INSERT INTO `tp_menu` VALUES ('4', '/admin/admins/index', '管理员管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('5', '/admin/role/index', '角色管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('6', '/admin/menu/index', '菜单管理', '', '1', '', '99', '1', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('7', '/admin/guide/index', '导游列表', '', '1', '', '99', '2', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('8', '/admin/user/index', '导游账号', '', '1', '', '99', '2', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('9', '/admin/article/index', '文章列表', '', '1', '', '99', '3', '1', '1', '0');
INSERT INTO `tp_menu` VALUES ('10', '/admin/admins/add', '添加', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('11', '/admin/admins/edit', '修改', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('12', '/admin/admins/del', '删除', '', '1', '', '99', '4', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('13', '/admin/menu/add', '添加', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('14', '/admin/menu/edit', '修改', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('15', '/admin/menu/del', '删除', '', '1', '', '99', '6', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('16', '/admin/access/authorize', '权限设置', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('17', '/admin/role/add', '添加', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('18', '/admin/role/edit', '修改', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('19', '/admin/role/del', '删除', '', '1', '', '99', '5', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('20', '/admin/guide/add', '添加', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('21', '/admin/guide/edit', '编辑', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('22', '/admin/guide/del', '删除', '', '1', '', '99', '7', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('23', '/admin/article/add', '添加', '', '1', '', '99', '9', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('24', '/admin/article/edit', '编辑', '', '1', '', '99', '9', '2', '0', '1');
INSERT INTO `tp_menu` VALUES ('25', '/admin/article/del', '删除', '', '1', '', '99', '9', '2', '0', '1');

-- ----------------------------
-- Table structure for tp_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_role
-- ----------------------------
INSERT INTO `tp_role` VALUES ('1', '超级管理员', '权限最大', '1');
INSERT INTO `tp_role` VALUES ('2', '开发的', '搬砖的的！', '1');
