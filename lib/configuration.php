<?php
	class Configuration{
		private $db_server = '127.0.0.1';
		private $db_port = '5984';
		private $db_database = 'verge';
		private $db_admin_user = 'suthaw';
		private $db_admin_password = 'admin123';

		public function __get($property){
			if(getenv($property)){
				return getenv($property);
			}else{
				return $this->$property;
			}
		}
	}