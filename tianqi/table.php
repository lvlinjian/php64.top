<?php
/**
 * Name:某天气预报数据表结构
 * By: php64.top - 油果
 * @date    2018-01-08 15:05:53
**/
## 数据表结构
		$sql="CREATE TABLE `tianqi` (
		  `days` DATE NOT NULL comment '日期',  
		  `week` CHAR(9) COMMENT '星期',
		  `cityId` CHAR(9) NOT NULL COMMENT '城市ID',
		  `cityno` CHAR(19) NOT NULL COMMENT '城市拼音',
		  `citynm` CHAR(21) NOT NULL COMMENT '城市名称',
		  `temperature` CHAR(13) COMMENT '今日气温',
		  `temperature_curr` CHAR(6) COMMENT '动态气温',
		  `humidity` CHAR(4) COMMENT '相对湿度',
		  `weather` CHAR(43) COMMENT '今日天气', 
		  `weather_curr` CHAR(22) COMMENT '动态天气',
		  `weather_icon` VARCHAR(55) COMMENT '天气图标',
		  `wind` VARCHAR(20) COMMENT '风向',
		  `winp` CHAR(9) COMMENT '风力',
		  `temp_high` TINYINT(1) COMMENT '最高温度',
		  `temp_low` TINYINT(1) COMMENT '最低温度',
		  `temp_curr` TINYINT(1) COMMENT '当前温度',
		  `tq_stime` CHAR(10) DEFAULT NULL comment '更新time戳',  
		  PRIMARY KEY (`cityId`),UNIQUE KEY `citynm` (`citynm`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";






