CREATE TABLE `lite_recharge_package`
(
    `id`           int unsigned            NOT NULL AUTO_INCREMENT,
    `name`         varchar(255)            NOT NULL COMMENT '套餐名称',
    `describe`     varchar(255)            NOT NULL COMMENT '套餐描述',
    `sell_price`   decimal(10, 2) unsigned NOT NULL COMMENT '套餐价格',
    `draw_number`  int unsigned            NOT NULL COMMENT '绘画次数',
    `sort`         int unsigned            NOT NULL DEFAULT '0' COMMENT '排序',
    `status`       tinyint(1)              NOT NULL DEFAULT '1' COMMENT '套餐状态：1-开启；0-关闭；',
    `create_time`  int                              DEFAULT NULL COMMENT '创建时间',
    `update_time`  int                              DEFAULT NULL COMMENT '更新时间',
    `delete_time`  int                              DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT ='充值套餐';

alter table lite_recharge_order
    add column `recharge_package_snapshot` json COMMENT '充值套餐快照';
