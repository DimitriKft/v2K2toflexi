

CREATE TABLE `#__k2toflexi_log` (
  `title_item` varchar(512) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `message` varchar(512) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__k2toflexi_tags` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `published` smallint(6) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_name` (`name`),
    KEY `idx_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__k2toflexi_attachments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `itemID` int(11) NOT NULL,
    `filename` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `titleAttribute` text NOT NULL,
    `hits` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_hits` (`hits`),
    KEY `idx_itemID` (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__k2toflexi_categories` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` text NOT NULL,
    `parent` int(11) DEFAULT '0',
    `extraFieldsGroup` int(11) NOT NULL,
    `published` smallint(6) NOT NULL DEFAULT '0',
    `access` int(11) NOT NULL DEFAULT '0',
    `ordering` int(11) NOT NULL DEFAULT '0',
    `image` varchar(255) NOT NULL,
    `params` text NOT NULL,
    `trash` smallint(6) NOT NULL DEFAULT '0',
    `plugins` mediumtext NOT NULL,
    `language` char(7) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_access` (`access`),
    KEY `idx_category` (`published`,`access`,`trash`),
    KEY `idx_language` (`language`),
    KEY `idx_ordering` (`ordering`),
    KEY `idx_parent` (`parent`),
    KEY `idx_published` (`published`),
    KEY `idx_trash` (`trash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__k2toflexi_extra_fields_groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__k2toflexi_extra_fields` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `value` text NOT NULL,
    `type` varchar(255) NOT NULL,
    `group` int(11) NOT NULL,
    `published` tinyint(4) NOT NULL,
    `ordering` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_group` (`group`),
    KEY `idx_published` (`published`),
    KEY `idx_ordering` (`ordering`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__k2toflexi_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `catid` int(11) NOT NULL,
    `published` smallint(6) NOT NULL DEFAULT '0',
    `introtext` mediumtext NOT NULL,
    `fulltext` mediumtext NOT NULL,
    `video` text,
    `gallery` varchar(255) DEFAULT NULL,
    `extra_fields` mediumtext NOT NULL,
    `extra_fields_search` mediumtext NOT NULL,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` int(11) NOT NULL DEFAULT '0',
    `created_by_alias` varchar(255) NOT NULL,
    `checked_out` int(10) unsigned NOT NULL,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(11) NOT NULL DEFAULT '0',
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `trash` smallint(6) NOT NULL DEFAULT '0',
    `access` int(11) NOT NULL DEFAULT '0',
    `ordering` int(11) NOT NULL DEFAULT '0',
    `featured` smallint(6) NOT NULL DEFAULT '0',
    `featured_ordering` int(11) NOT NULL DEFAULT '0',
    `image_caption` text NOT NULL,
    `image_credits` varchar(255) NOT NULL,
    `video_caption` text NOT NULL,
    `video_credits` varchar(255) NOT NULL,
    `hits` int(10) unsigned NOT NULL,
    `params` text NOT NULL,
    `metadesc` text NOT NULL,
    `metadata` text NOT NULL,
    `metakey` text NOT NULL,
    `plugins` mediumtext NOT NULL,
    `language` char(7) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_access` (`access`),
    KEY `idx_catid` (`catid`),
    KEY `idx_created_by` (`created_by`),
    KEY `idx_created` (`created`),
    KEY `idx_featured_ordering` (`featured_ordering`),
    KEY `idx_featured` (`featured`),
    KEY `idx_hits` (`hits`),
    KEY `idx_item` (`published`,`publish_up`,`publish_down`,`trash`,`access`),
    KEY `idx_language` (`language`),
    KEY `idx_ordering` (`ordering`),
    KEY `idx_published` (`published`),
    KEY `idx_publish_down` (`publish_down`),
    KEY `idx_publish_up` (`publish_up`),
    KEY `idx_trash` (`trash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__k2_log` (
    `status` int(11) NOT NULL,
    `response` text NOT NULL,
    `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
