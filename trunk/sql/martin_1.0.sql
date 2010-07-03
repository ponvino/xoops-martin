CREATE TABLE `martin_auction` (
  `auction_id` int(11) NOT NULL AUTO_INCREMENT,
  `check_in_date` int(11) DEFAULT 0,
  `check_out_date` int(11) DEFAULT 0,
  `apply_start_date` int(11) DEFAULT 0,
  `apply_stop_date` int(11) DEFAULT 0,
  `auction_price` decimal(10,2) DEFAULT 0,
  `auction_add_price` decimal(10,2) DEFAULT 0,
  `auction_can_use_coupon` tinyint(1) DEFAULT 0,
  `auction_sented_coupon` decimal(10,2) DEFAULT 0,
  `auction_status` tinyint(1) DEFAULT 0,
  `auction_add_time` int(11) DEFAULT 0,
  PRIMARY KEY (`auction_id`)
)TYPE=MyISAM;

--
-- 导出表中的数据 `martin_auction`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_auction_room`
--

CREATE TABLE `martin_auction_room` (
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_count` int(11) DEFAULT 0,
  PRIMARY KEY (`auction_id`,`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='竞拍房间关联';

--
-- 导出表中的数据 `martin_auction_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_group`
--

CREATE TABLE `martin_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `check_in_date` int(11) DEFAULT 0,
  `check_out_date` int(11) DEFAULT 0,
  `apply_start_date` int(11) DEFAULT 0,
  `apply_stop_date` int(11) DEFAULT 0,
  `group_price` decimal(10,2) DEFAULT 0 COMMENT '团购价格',
  `group_can_use_coupon` tinyint(1) DEFAULT 0 COMMENT '是否能使用优惠卷',
  `group_sented_coupon` decimal(10,2) DEFAULT 0,
  `group_status` tinyint(1) DEFAULT 0 COMMENT '状态',
  `group_add_time` int(11) DEFAULT 0,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_group_room`
--

CREATE TABLE `martin_group_room` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_count` int(11) DEFAULT 0,
  PRIMARY KEY (`group_id`,`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购房间关联';

--
-- 导出表中的数据 `martin_group_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel`
--

CREATE TABLE `martin_hotel` (
  `hotel_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_city_id` int(11) DEFAULT 0,
  `hotel_rank` int(11) DEFAULT 0 COMMENT '酒店排序',
  `hotel_name` varchar(255) DEFAULT 0 COMMENT '酒店排序',
  `hotel_enname` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_alias` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_keywords` varchar(255) DEFAULT NULL COMMENT '酒店排序',
  `hotel_description` varchar(255) DEFAULT NULL,
  `hotel_star` tinyint(1) DEFAULT 0 COMMENT '酒店排序',
  `hotel_address` varchar(255) DEFAULT NULL,
  `hotel_telephone` varchar(45) DEFAULT NULL,
  `hotel_fax` varchar(45) DEFAULT NULL,
  `hotel_room_count` int(11) DEFAULT 0,
  `hotel_image` varchar(500) DEFAULT NULL,
  `hotel_google` varchar(255) DEFAULT NULL,
  `hotel_characteristic` varchar(255) DEFAULT NULL,
  `hotel_reminded` varchar(1000) DEFAULT NULL,
  `hotel_info` text,
  `hotel_status` tinyint(1) DEFAULT 0,
  `hotel_open_time` int(11) DEFAULT 0,
  `hotel_add_time` int(11) DEFAULT 0,
  PRIMARY KEY (`hotel_id`),
  KEY `hotel_city_id` (`hotel_city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='酒店信息' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_hotel`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_city`
--

CREATE TABLE `martin_hotel_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_parentid` int(11) DEFAULT 0,
  `city_name` varchar(45) DEFAULT NULL,
  `city_level` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_hotel_city`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_promotions`
--

CREATE TABLE `martin_hotel_promotions` (
  `hotel_id` int(11) DEFAULT 0,
  `promotion_start_date` int(11) DEFAULT 0,
  `promotion_end_date` int(11) DEFAULT 0,
  `promotion_description` varchar(500) DEFAULT NULL,
  `promotion_add_time` int(11) DEFAULT 0,
  KEY `hotel_id` (`hotel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='酒店促销信息';

--
-- 导出表中的数据 `martin_hotel_promotions`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service`
--

CREATE TABLE `martin_hotel_service` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type_id` int(11) DEFAULT 0,
  `service_unit` int(5) DEFAULT 0,
  `service_name` varchar(255) DEFAULT NULL,
  `service_instruction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`service_id`),
  KEY `service_type_id` (`service_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_hotel_service`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service_relation`
--

CREATE TABLE `martin_hotel_service_relation` (
  `hotel_id` int(11) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `service_extra_price` int(11) DEFAULT 0,
  PRIMARY KEY (`hotel_id`,`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='酒店额外信息';

--
-- 导出表中的数据 `martin_hotel_service_relation`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service_type`
--

CREATE TABLE `martin_hotel_service_type` (
  `service_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`service_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务类目' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_hotel_service_type`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_order`
--

CREATE TABLE `martin_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_type` tinyint(1) DEFAULT 0 COMMENT '预定方式',
  `order_mode` tinyint(1) DEFAULT 0 COMMENT '订单模式（团购，竞价）',
  `order_uid` int(11) DEFAULT 0,
  `order_status` tinyint(1) DEFAULT 0 COMMENT '订单状态',
  `order_pay_method` tinyint(1) DEFAULT 0,
  `order_total_price` decimal(10,2) DEFAULT 0 COMMENT '订单状态',
  `order_pay_money` decimal(10,2) DEFAULT 0,
  `order_coupon` decimal(10,2) DEFAULT 0,
  `order_real_name` varchar(45) DEFAULT NULL,
  `order_document` varchar(255) DEFAULT NULL COMMENT '证件',
  `order_telephone` varchar(45) DEFAULT NULL,
  `order_phone` varchar(45) DEFAULT 0,
  `order_extra_persons` varchar(500) DEFAULT NULL,
  `order_note` varchar(255) DEFAULT NULL,
  `order_submit_time` int(11) DEFAULT 0,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_order_room`
--

CREATE TABLE `martin_order_room` (
  `order_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `room_date` int(11) DEFAULT 0,
  `room_count` int(11) DEFAULT 0,
  PRIMARY KEY (`room_id`,`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单房间';

--
-- 导出表中的数据 `martin_order_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room`
--

CREATE TABLE `martin_room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) DEFAULT 0,
  `room_type_id` int(11) DEFAULT 0,
  `room_name` varchar(45) DEFAULT NULL,
  `romm_area` int(11) DEFAULT 0,
  `room_floor` varchar(45) DEFAULT NULL,
  `room_is_add_bed` tinyint(1) DEFAULT 0,
  `room_add_money` int(11) DEFAULT 0,
  `romm_bed_info` varchar(255) DEFAULT NULL,
  `room_status` tinyint(1) DEFAULT 0,
  `room_sented_coupon` decimal(10,2) DEFAULT 0 COMMENT '曾送现金卷',
  PRIMARY KEY (`room_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `room_type_id` (`room_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间信息' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room_price`
--

CREATE TABLE `martin_room_price` (
  `room_id` int(11) DEFAULT 0,
  `room_is_totay_special` tinyint(1) DEFAULT 0,
  `room_price` decimal(10,2) DEFAULT 0,
  `room_advisory_range_small` decimal(10,2) DEFAULT 0,
  `room_advisory_range_max` decimal(10,2) DEFAULT 0,
  `room_date` int(11) DEFAULT 0,
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间价格';

--
-- 导出表中的数据 `martin_room_price`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room_type`
--

CREATE TABLE `martin_room_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_info` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房型' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `martin_room_type`
--
