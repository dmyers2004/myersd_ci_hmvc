<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Acl extends CI_Migration {

	public function up()
	{		
		$sql[] = "CREATE TABLE `access` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`resource` varchar(255) DEFAULT NULL,
		`active` tinyint(1) DEFAULT '0',
		`created` timestamp NULL DEFAULT NULL,
		`modified` timestamp NULL DEFAULT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
		$sql[] = "CREATE TABLE `group` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(32) DEFAULT NULL,
		`created` timestamp NULL DEFAULT NULL,
		`modified` timestamp NULL DEFAULT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
				
		$sql[] = "CREATE TABLE `group_access` (
		`group_id` int(11) unsigned NOT NULL,
		`access_id` int(11) unsigned NOT NULL,
		PRIMARY KEY (`group_id`,`access_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		
		$sql[] = "CREATE TABLE `user` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`group_id` int(11) DEFAULT NULL,
		`created` timestamp NULL DEFAULT NULL,
		`modified` timestamp NULL DEFAULT NULL,
		`email` varchar(255) DEFAULT NULL,
		`password` varchar(40) DEFAULT NULL,
		`name` varchar(32) DEFAULT NULL,
		`salt` varchar(8) DEFAULT NULL,
		`active` tinyint(1) DEFAULT '0',
		`tries` tinyint(1) DEFAULT NULL,
		`forgot_key` varchar(72) DEFAULT NULL,
		`forgot_timeframe` timestamp NULL DEFAULT NULL,
		`remember_key` varchar(72) DEFAULT NULL,
		`remember_timeframe` timestamp NULL DEFAULT NULL,
		`activate_key` varchar(72) DEFAULT NULL,
		`activate_timeframe` timestamp NULL DEFAULT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		foreach ($sql as $q) {
			$this->db->query($q);
		}
	}

	public function down()
	{
		$this->dbforge->drop_table('settings');
	}

} /* end migration */