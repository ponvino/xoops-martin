ALTER TABLE  `7mogjl_users` ADD  `phone` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci AFTER  `user_mailok` DEFAULT '' ,
ADD  `telephone` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci  AFTER  `phone` DEFAULT '',
ADD  `company` VARCHAR( 255 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci  AFTER  `telephone` DEFAULT '',
ADD  `nationality` INT( 11 )  AFTER  `company` DEFAULT 0,
ADD  `document` INT( 11 )  AFTER  `nationality` DEFAULT 0,
ADD  `document_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci AFTER  `document` DEFAULT '';
