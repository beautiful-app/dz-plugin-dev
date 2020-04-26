<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_user extends discuz_table
{
	public function __construct() {
		$this->_table = 'jzsjiale_sms_user';
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
	
	public function getallByPhone($phone){
	    return DB::fetch_all('SELECT * FROM %t WHERE phone = '.$phone,array($this->_table));
	
	}
	
	public function fetch_by_phone($phone) {
	    $user = array();
	    if($phone) {
	        $user = DB::fetch_first('SELECT * FROM %t WHERE phone=%s', array($this->_table, $phone));
	    }
	    return $user;
	}
    public function fetch_by_username($username) {
		$user = array();
		if($username) {
			$user = DB::fetch_first('SELECT * FROM %t WHERE username=%s', array($this->_table, $username));
		}
		return $user;
	}
	
	public function fetch_by_uid($uid) {
	    $user = array();
	    if($uid) {
	        $user = DB::fetch_first('SELECT * FROM %t WHERE uid=%d', array($this->_table, $uid));
	    }
	    return $user;
	}
	
	public function delalluser(){
	    return DB::delete($this->_table,'id>0');
	
	}
	
	public function deletebyid($id){
	    return DB::delete($this->_table,'id = '.$id);
	
	}
	
	public function deletebyphone($phone){
	    return DB::delete($this->_table,'phone = '.$phone);
	
	}
	
	public function deletebyuid($uid){
	    return DB::delete($this->_table,'uid = '.$uid);
	
	}
    
	public function count_by_map($map,$status="") {
	    $where = " 1=1 ";
	
	    foreach ($map as $mapkey => $mapvalue){
	        if($mapkey == "keyword"){
	            $where .=  ' and ('.DB::field('phone', '%'.$mapvalue.'%', 'like').' or '.DB::field('username', '%'.$mapvalue.'%', 'like').') ';
	        }else{
	            $where .=  ' and '.$mapkey.' = '.$mapvalue.' ';
	        }
	
	    }
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
	
	//$map title,categoryid,flag
	public function range_by_map($map = array(),$start = 0, $limit = 0, $sort = '',$status="") {
	    $where = " 1=1 ";
	
	    foreach ($map as $mapkey => $mapvalue){
	        if($mapkey == "keyword"){
	            $where .=  ' and ('.DB::field('phone', '%'.$mapvalue.'%', 'like').' or '.DB::field('username', '%'.$mapvalue.'%', 'like').') ';
	        }else{
	            $where .=  ' and '.$mapkey.' = '.$mapvalue.' ';
	        }
	
	    }
	    if($sort) {
	        $this->checkpk();
	    }
	    return DB::fetch_all('SELECT * FROM '.DB::table($this->_table).' WHERE '.$where.($sort ? ' ORDER BY '.DB::order($this->_pk, $sort) : '').DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
}

?>