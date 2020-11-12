<?php

class Auto_Backup extends DB_Item
{
	function Auto_Backup( $id=0 ) {
		$this->DB_Item( $id, AUTOBACKUPS_TABLE );
	}

	function load( $id ) {
		DB_Item::load( $id );
		$this->fields['secret_cron'] = md5( $this->fields['id'] . $this->fields['secret_id'] );
		return $this->fields;
	}
	
}

?>