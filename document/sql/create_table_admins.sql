CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(63) NOT NULL DEFAULT '' COMMENT '用户名',
  `phone` char(11) NOT NULL COMMENT '手机号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像',
  `last_login` int(11) DEFAULT NULL COMMENT '上次登陆时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态：0禁用 1启用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;