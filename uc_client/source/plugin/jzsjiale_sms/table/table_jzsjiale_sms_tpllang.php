<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_tpllang extends discuz_table
{
	public function __construct() {
		$this->_table = 'jzsjiale_sms_tpllang';
		$this->_pk    = 'id';
		parent::__construct();
	}
	public function getone(){
		return DB::fetch_first('SELECT * FROM %t ORDER BY id DESC limit 0,1',array($this->_table));
		
	}
	
	public function get_by_id($id){
	    return DB::fetch_first('SELECT * FROM %t WHERE id = '.$id,array($this->_table));
	
	}
	
	public function getall(){
		return DB::fetch_all('SELECT * FROM %t ORDER BY id DESC',array($this->_table));
	
	}
	
	
	public function delall(){
	    return DB::delete($this->_table,'id>0');
	
	}
	
	public function updatelang($enname,$cnname){
	    return DB::update($this->_table,array('cnname' => $cnname),"enname = '".$enname."'");
	
	}
}

?>