CREATE TABLE IF NOT EXISTS `cm_sites_categories_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) DEFAULT NULL,
  `parentID` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

CREATE TABLE IF NOT EXISTS `cm_sites_elements_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_url` varchar(1000) DEFAULT NULL,
  `meta_title` varchar(1000) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  `short_desc` varchar(600) DEFAULT NULL,
  `desc` text,
  `date_add` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `ord` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=184 ;

CREATE TABLE IF NOT EXISTS `cm_sites_relations_pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_relations` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typ` varchar(255) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `size` float(11,0) DEFAULT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `desc` text,
  `active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=480 ;