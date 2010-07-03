ALTER TABLE  `7mogjl_users` ADD  `phone` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `user_mailok` ,
ADD  `telephone` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `phone` ,
ADD  `company` VARCHAR( 255 ) CHARACTER SET ucs2 COLLATE ucs2_general_ci NOT NULL AFTER  `telephone` ,
ADD  `nationality` INT( 11 ) NOT NULL AFTER  `company` ,
ADD  `document` INT( 11 ) NOT NULL AFTER  `nationality` ,
ADD  `document_value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `document`
