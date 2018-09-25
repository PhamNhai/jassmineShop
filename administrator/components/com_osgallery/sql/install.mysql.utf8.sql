CREATE TABLE IF NOT EXISTS #__os_gallery (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) DEFAULT NULL,
  `published` int(1) DEFAULT '1',
  `params` TEXT NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS #__os_gallery_img (
  `id` int(11) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `ordering` int(11) NOT NULL,
  `params` TEXT NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS #__os_gallery_categories (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fk_gal_id` int(11) unsigned DEFAULT NULL COMMENT 'The gallery id from table __os_gallery',
  `name` varchar(255) DEFAULT NULL,
  `ordering` int(11) NOT NULL,
  `params` TEXT NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`fk_gal_id`) REFERENCES #__os_gallery(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS #__os_gallery_connect (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fk_cat_id` int(11) unsigned DEFAULT NULL  COMMENT 'The id from table __os_gallery_categories',
  `fk_gal_img_id` int(11) unsigned DEFAULT NULL COMMENT 'The img id from table __os_gallery_img',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_cat_id`) REFERENCES #__os_gallery_categories(`id`),
  FOREIGN KEY (`fk_gal_img_id`) REFERENCES #__os_gallery_img(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;