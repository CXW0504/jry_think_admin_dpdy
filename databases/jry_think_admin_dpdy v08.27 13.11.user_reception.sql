ALTER TABLE `jry_think_admin_dpdy`.`dpdy_user_reception`   
  CHANGE `type` `type` TINYINT(3) UNSIGNED DEFAULT 1  NULL  COMMENT '用户类型，1抵押专员2调评专员3普通用户';
