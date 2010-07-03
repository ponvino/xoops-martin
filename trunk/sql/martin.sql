-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 05 月 18 日 17:05
-- 服务器版本: 5.1.37
-- PHP 版本: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `xoops_gjl`
--

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_auction`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_auction` (
  `auction_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_name` VARCHAR( 255 ) DEFAULT '',
  `auction_info` text DEFAULT '',
  `check_in_date` int(11) DEFAULT '0',
  `check_out_date` int(11) DEFAULT '0',
  `apply_start_date` int(11) DEFAULT '0',
  `apply_end_date` int(11) DEFAULT '0',
  `auction_price` decimal(10,2) DEFAULT '0.00',
  `auction_low_price` decimal(10,2) DEFAULT '0.00',
  `auction_add_price` decimal(10,2) DEFAULT '0.00',
  `auction_can_use_coupon` tinyint(1) DEFAULT '0',
  `auction_sented_coupon` decimal(10,2) DEFAULT '0.00',
  `auction_status` tinyint(1) DEFAULT '0',
  `auction_add_time` int(11) DEFAULT '0',
  PRIMARY KEY (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `7mogjl_martin_auction_bid` (
  `bid_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '出价ID',
  `auction_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bid_price` decimal(11,2) NOT NULL COMMENT '出价',
  `bid_time` int(11) NOT NULL,
  `checck_in-time` int(11) NOT NULL,
  `check_out_time` int(11) NOT NULL,
  `bid_count` int(11) NOT NULL,
  `bid_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`bid_id`),
  KEY `auction_id` (`auction_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='出价' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_auction_room`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_auction_room` (
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_count` int(11) DEFAULT '0',
  PRIMARY KEY (`auction_id`,`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='竞拍房间关联';

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_group`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR( 255 ) DEFAULT '',
  `group_info` text DEFAULT '',
  `check_in_date` int(11) DEFAULT '0',
  `check_out_date` int(11) DEFAULT '0',
  `apply_start_date` int(11) DEFAULT '0',
  `apply_end_date` int(11) DEFAULT '0',
  `group_price` decimal(10,2) DEFAULT '0.00' COMMENT '团购价格',
  `group_can_use_coupon` tinyint(1) DEFAULT '0' COMMENT '是否能使用优惠卷',
  `group_sented_coupon` decimal(10,2) DEFAULT '0.00',
  `group_status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `group_add_time` int(11) DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='团购' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `7mogjl_martin_group_join` (
  `join_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `room_number` int(11) NOT NULL,
  `join_time` int(11) NOT NULL,
  PRIMARY KEY (`join_id`),
  KEY `group_id` (`group_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='参加团购' AUTO_INCREMENT=1 ;

--
-- 表的结构 `7mogjl_martin_group_room`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_group_room` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_count` int(11) DEFAULT '0',
  PRIMARY KEY (`group_id`,`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='团购房间关联';

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel` (
  `hotel_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_city_id` VARCHAR( 255 ) DEFAULT '' ,
  `hotel_environment` VARCHAR( 255 ) DEFAULT '' ,
  `hotel_rank` int(11) DEFAULT '0' COMMENT '酒店排序',
  `hotel_name` varchar(255) DEFAULT '0' COMMENT '酒店排序',
  `hotel_enname` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_alias` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_keywords` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_tags` varchar(255) DEFAULT NULL COMMENT '酒店tag',
  `hotel_description` varchar(255) DEFAULT NULL,
  `hotel_star` tinyint(1) DEFAULT '0' COMMENT '酒店排序',
  `hotel_address` varchar(255) DEFAULT NULL,
  `hotel_telephone` varchar(45) DEFAULT NULL,
  `hotel_fax` varchar(45) DEFAULT NULL,
  `hotel_room_count` int(11) DEFAULT '0',
  `hotel_icon` VARCHAR( 255 ) COMMENT '酒店图片',
  `hotel_image` varchar(1000) DEFAULT NULL,
  `hotel_google` varchar(255) DEFAULT NULL,
  `hotel_characteristic` varchar(255) DEFAULT NULL,
  `hotel_reminded` varchar(1000) DEFAULT NULL,
  `hotel_info` text,
  `hotel_status` tinyint(1) DEFAULT '0',
  `hotel_open_time` int(11) DEFAULT '0',
  `hotel_add_time` int(11) DEFAULT '0',
  PRIMARY KEY (`hotel_id`),
  KEY `hotel_city_id` (`hotel_city_id`),
  KEY `hotel_alias` (`hotel_alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel_city`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_parentid` int(11) DEFAULT '0',
  `city_name` varchar(45) DEFAULT NULL,
  `city_level` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel_promotions`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel_promotions` (
  `promotion_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT '0',
  `promotion_start_date` int(11) DEFAULT '0',
  `promotion_end_date` int(11) DEFAULT '0',
  `promotion_description` text,
  `promotion_add_time` int(11) DEFAULT '0',
  PRIMARY KEY (`promotion_id`),
  KEY `hotel_id` (`hotel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店促销信息' ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel_service`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel_service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type_id` int(11) DEFAULT '0',
  `service_unit` varchar(45) DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `service_instruction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`service_id`),
  KEY `service_type_id` (`service_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='服务' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel_service_relation`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel_service_relation` (
  `hotel_id` int(11) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `service_extra_price` int(11) DEFAULT '0',
  PRIMARY KEY (`hotel_id`,`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='酒店额外信息';

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_hotel_service_type`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_hotel_service_type` (
  `service_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`service_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='服务类目' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_order`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_type` tinyint(1) DEFAULT '0' COMMENT '预定方式',
  `order_mode` tinyint(1) DEFAULT '0' COMMENT '订单模式（团购，竞价）',
  `order_uid` int(11) DEFAULT '0',
  `order_status` tinyint(1) DEFAULT '0' COMMENT '订单状态',
  `order_pay_method` tinyint(1) DEFAULT '0',
  `order_total_price` decimal(10,2) DEFAULT '0.00' COMMENT '订单状态',
  `order_pay_money` decimal(10,2) DEFAULT '0.00',
  `order_coupon` decimal(10,2) DEFAULT '0.00',
  `order_sented_coupon` decimal(10,2) DEFAULT '0.00',
  `order_real_name` varchar(45) DEFAULT NULL,
  `order_document_type` tinyint(1) DEFAULT '0' COMMENT '证件类型',
  `order_document` varchar(255) DEFAULT NULL COMMENT '证件',
  `order_telephone` varchar(45) DEFAULT NULL,
  `order_phone` varchar(45) DEFAULT '0',
  `order_extra_persons` varchar(500) DEFAULT NULL,
  `order_note` varchar(255) DEFAULT NULL,
  `order_status_time` int(11) DEFAULT '0',
  `order_submit_time` int(11) DEFAULT '0',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_order_room`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_order_room` (
  `order_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_date` int(11) DEFAULT '0',
  `room_count` int(11) DEFAULT '0',
  KEY (`room_id`),
  KEY(`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='订单房间';

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `7mogjl_martin_order_query_room` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `room_date` int(11) DEFAULT NULL,
  `room_count` int(11) DEFAULT NULL,
  `room_price` decimal(10,2) DEFAULT '0.00',
  KEY (`order_id`),
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='查询预定' AUTO_INCREMENT=1 ;


--
-- 表的结构 `7mogjl_martin_room`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT '0',
  `room_type_id` int(11) DEFAULT '0',
  `room_bed_type` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '客房床型',
  `room_name` varchar(45) DEFAULT NULL,
  `room_area` int(11) DEFAULT '0',
  `room_floor` varchar(45) DEFAULT NULL,
  `room_initial_price` decimal(10,2) DEFAULT '0.00',
  `room_is_add_bed` tinyint(1) DEFAULT '0',
  `room_add_money` int(11) DEFAULT '0',
  `room_bed_info` varchar(255) DEFAULT NULL,
  `room_status` tinyint(1) DEFAULT '0',
  `room_sented_coupon` decimal(10,2) DEFAULT '0.00' COMMENT '曾送现金卷',
  PRIMARY KEY (`room_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `room_type_id` (`room_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='房间信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_room_price`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_room_price` (
  `room_id` int(11) DEFAULT '0',
  `room_is_totay_special` tinyint(1) DEFAULT '0',
  `room_price` decimal(10,2) DEFAULT '0.00',
  `room_advisory_range_small` decimal(10,2) DEFAULT '0.00',
  `room_advisory_range_max` decimal(10,2) DEFAULT '0.00',
  `room_date` int(11) DEFAULT '0',
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='房间价格';

-- --------------------------------------------------------

--
-- 表的结构 `7mogjl_martin_room_type`
--

CREATE TABLE IF NOT EXISTS `7mogjl_martin_room_type` (
  `room_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_info` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`room_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='房型' AUTO_INCREMENT=1 ;
