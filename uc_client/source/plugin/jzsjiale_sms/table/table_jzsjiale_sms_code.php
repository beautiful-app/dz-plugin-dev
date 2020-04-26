<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_code extends discuz_table
{
	public function __construct() {
		$this->_table = 'jzsjiale_sms_code';
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
	
	public function getallByIds($ids){
	    return DB::fetch_all('SELECT * FROM %t WHERE id in ('.$ids.')',array($this->_table));
	
	}
	
	public function delalluser(){
	    return DB::delete($this->_table,'id>0');
	
	}
	
	public function deletebyid($id){
	    return DB::delete($this->_table,'id = '.$id);
	
	}
	
    public function deleteweek1(){
	     
	    return DB::delete($this->_table,' UNIX_TIMESTAMP(date(FROM_UNIXTIME(dateline))) < '.strtotime(date("Y-m-d", strtotime("-1 week"))));
	
	}
	
	public function deleteby_seccode_and_phone($phone,$seccode){
	    return DB::delete($this->_table,'seccode = '.$seccode.' and phone = '.$phone);
	
	}
	
	public function fetchfirst_by_phone($phone) {
	    $code = array();
	    if($phone) {
	        $code = DB::fetch_first('SELECT * FROM %t WHERE phone=%s ORDER BY id DESC', array($this->_table, $phone));
	    }
	    return $code;
	}
	
	public function count_by_phone_day($phone="") {
	    $where = " date(FROM_UNIXTIME(dateline))=curdate() ";
	    if(!empty($phone)){
	        $where .= " and phone = ".$phone.' ';
	    }else{
	        return 999;
	    }
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
	
	public function fetchfirst_by_phone_and_seccode($phone,$seccode) {
	    $code = array();
	    if($phone && $seccode) {
	        $code = DB::fetch_first('SELECT * FROM %t WHERE phone=%s and seccode=%s ORDER BY id DESC', array($this->_table, $phone,$seccode));
	    }
	    return $code;
	}
    
}

?>