CREATE TABLE `dpdy_project_error_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned DEFAULT NULL COMMENT '所属项目编号',
  `name` varchar(200) DEFAULT NULL COMMENT '错误代码',
  `desc` text COMMENT '错误代码解释',
  `ad_time` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(3) unsigned DEFAULT '99' COMMENT '状态，99正常98删除',
  `del_time` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='项目对应的接口错误代码对照表，保存有接口的全部错误代码'