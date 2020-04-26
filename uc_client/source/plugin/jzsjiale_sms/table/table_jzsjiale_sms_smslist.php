<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_smslist extends discuz_table
{
	public function __construct() {
		$this->_table = 'jzsjiale_sms_smslist';
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
	public function count_all_by_day() {
	    $where = " status = 1 and date(FROM_UNIXTIME(dateline))=curdate() ";

	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
	
	public function count_by_phone_day($phone="") {
	    $where = " status = 1 and date(FROM_UNIXTIME(dateline))=curdate() ";
	    if(!empty($phone)){
	        $where .= " and phone = ".$phone.' ';
	    }else{
	        return 999;
	    }
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
	public function count_by_map($map,$status="") {
	    $where = " 1=1 ";
	     
	    foreach ($map as $mapkey => $mapvalue){
	        if($mapkey == "dateline"){
	            $where .=  ' and UNIX_TIMESTAMP(date(FROM_UNIXTIME(dateline))) >= '.$mapvalue.' ';
	        }elseif($mapkey == "status"){
	            if($mapvalue != 'all'){
	                $where .=  ' and '.$mapkey.' = '.$mapvalue.' ';
	            }
	        }elseif($mapkey == "phone"){
	            $where .=' and '.DB::field('phone', '%'.$mapvalue.'%', 'like').' ';
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
	        if($mapkey == "dateline"){
	            $where .=  ' and UNIX_TIMESTAMP(date(FROM_UNIXTIME(dateline))) >= '.$mapvalue.' ';
	        }elseif($mapkey == "status"){
	            if($mapvalue != 'all'){
	                $where .=  ' and '.$mapkey.' = '.$mapvalue.' ';
	            }
	        }elseif($mapkey == "phone"){
	            $where .=' and '.DB::field('phone', '%'.$mapvalue.'%', 'like').' ';
	        }else{
	            $where .=  ' and '.$mapkey.' = '.$mapvalue.' ';
	        }
	
	    }
	    if($sort) {
	        $this->checkpk();
	    }
	    return DB::fetch_all('SELECT * FROM '.DB::table($this->_table).' WHERE '.$where.($sort ? ' ORDER BY '.DB::order($this->_pk, $sort) : '').DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
	
	public function count_by_uid_day($uid="") {
	    $where = " status = 1 and date(FROM_UNIXTIME(dateline))=curdate() ";
	    if(!empty($uid)){
	        $where .= " and uid = ".$uid.' ';
	    }else{
	        return 999;
	    }
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
    
}

?>