<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_init extends CI_Migration {

	public function up()
	{		
		$sql = "CREATE TABLE `settings` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` mediumtext NOT NULL,
  `option_group` varchar(55) NOT NULL DEFAULT 'site',
  `auto_load` enum('no','yes') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`,`option_name`),
  KEY `option_name` (`option_name`),
  KEY `auto_load` (`auto_load`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
		
	}

	public function down()
	{
		$this->dbforge->drop_table('settings');
	}

} /* end migration */