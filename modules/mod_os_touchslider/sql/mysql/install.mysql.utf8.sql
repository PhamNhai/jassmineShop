CREATE TABLE IF NOT EXISTS #__os_touch_slider (
  `id` int(11) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) DEFAULT NULL,
  `module_id` int(11) unsigned DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `params` TEXT NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS #__os_touch_slider_text (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fk_ts_id` varchar(255) DEFAULT NULL,
  `fk_ts_img_id` varchar(255) DEFAULT NULL,
  `text_html` LONGBLOB DEFAULT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`fk_ts_id`) REFERENCES #__os_touch_slider(`module_id`),
   FOREIGN KEY (`fk_ts_img_id`) REFERENCES #__os_touch_slider(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;