<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_member extends discuz_table
{
	public function __construct() {
		$this->_table = 'common_member_profile';
		$this->_pk    = 'uid';
		parent::__construct();
	}

	
	public function fetch_by_mobile($phone) {
	    $user = array();
	    if($phone) {
	        $user = DB::fetch_first('SELECT * FROM %t WHERE mobile=%s', array($this->_table, $phone));
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
	
	public function fetch_member_by_uid($uid) {
	    $user = array();
	    if($uid) {
	        $user = DB::fetch_first('SELECT * FROM '.DB::table('common_member').' WHERE uid='.$uid);
	    }
	    return $user;
	}
	
	public function fetch_all_with_username_and_mobile($keyword="",$start = 0, $limit = 0, $sort = '') {
	    $dzuserlist = array();
	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";  
	    }
	    $dzuserlist = DB::fetch_all("SELECT sf.uid as uid,sf.mobile as phone,s.username as username,s.regdate as dateline
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE ".$wheresql.($sort ? ' ORDER BY sf.mobile desc,s.uid '. $sort : '').DB::limit($start, $limit));
	   
	    return $dzuserlist;
	}

	public function count_all_with_username_and_mobile($keyword="") {

	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";  
	    }
	     $count = (int) DB::result_first("SELECT count(*)
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE ".$wheresql);
	
	    return $count;
	}//f r o m w w w.m oqu8. co m
	
	
	public function fetch_all_with_username_and_mobile_chachong($start = 0, $limit = 0, $sort = '') {
	    $dzuserlist = array();
	 
	    $dzuserchongfulist = DB::fetch_all("SELECT mobile FROM ".DB::table('common_member_profile')." WHERE mobile <> '' GROUP BY mobile having count(1)>1");
	
	    $mobiles = array();
    	foreach ($dzuserchongfulist as $dzuserdata){
    	    if(!empty($dzuserdata['mobile'])){
    	        $mobiles[]=$dzuserdata['mobile'];
    	    }
    	    
    	}
    	
    	if (!empty($mobiles)){
    	    $dzuserlist = DB::fetch_all("SELECT sf.uid as uid,sf.mobile as phone,s.username as username,s.regdate as dateline
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE sf.mobile in (".dimplode($mobiles).") ".($sort ? ' ORDER BY sf.mobile desc,s.uid '. $sort : '').DB::limit($start, $limit));
    	     
    	}
    	
	    return $dzuserlist;
	}
	
	public function count_all_with_username_and_mobile_chachong() {
	
	    $count = 0;
	    $dzuserchongfulist = DB::fetch_all("SELECT mobile FROM ".DB::table('common_member_profile')." WHERE mobile <> '' GROUP BY mobile having count(1)>1");
	
	    $mobiles = array();
    	foreach ($dzuserchongfulist as $dzuserdata){
    	    if(!empty($dzuserdata['mobile'])){
    	        $mobiles[]=$dzuserdata['mobile'];
    	    }
    	}
    	
    	if (!empty($mobiles)){
    	    $count = (int) DB::result_first("SELECT count(*)
    				FROM ".DB::table('common_member_profile')." sf
    				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
    	            WHERE sf.mobile in (".dimplode($mobiles).") ");
    	}
	    return $count;
	}
	
	
	
	public function fetch_all_with_username_and_mobile_verify($keyword="",$start = 0, $limit = 0, $sort = '',$verify = 1) {
	    $dzuserlist = array();
	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";
	    }
	    $dzuserlist = DB::fetch_all("SELECT sf.uid as uid,sf.mobile as phone,s.username as username,s.regdate as dateline,sv.verify".$verify." as verify
				FROM (".DB::table('common_member')." s
				LEFT JOIN ".DB::table('common_member_profile')." sf USING(uid))
	            LEFT JOIN ".DB::table('common_member_verify')." sv USING(uid)
	            WHERE ".$wheresql.($sort ? ' ORDER BY sf.mobile desc,s.uid '. $sort : '').DB::limit($start, $limit));
	
	    return $dzuserlist;
	}
	
	public function count_all_with_username_and_mobile_verify($keyword="") {
	
	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";
	    }
	    $count = (int) DB::result_first("SELECT count(*)
				FROM (".DB::table('common_member')." s
				LEFT JOIN ".DB::table('common_member_profile')." sf USING(uid))
	            LEFT JOIN ".DB::table('common_member_verify')." sv USING(uid)
	            WHERE ".$wheresql);
	
	    return $count;
	}
	
	public function count_by_isexists_mobile($isexistsmobile=true) {
	    $where = " ";
	    if($isexistsmobile){
	        $where .= " mobile is not null and mobile <> '' ";
	    }else{
	        $where .= " mobile is null or mobile = '' ";
	    }
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table($this->_table).' WHERE '.$where);
	    return $count;
	}
	
	public function range_by_isexists_mobile($isexistsmobile=true,$start = 0, $limit = 0, $sort = '') {
	    $where = " ";
	    if($isexistsmobile){
	        $where .= " mobile is not null and mobile <> '' ";
	    }else{
	        $where .= " mobile is null or mobile = '' ";
	    }
	    if($sort) {
	        $this->checkpk();
	    }
	    return DB::fetch_all('SELECT * FROM '.DB::table($this->_table).' WHERE '.$where.($sort ? ' ORDER BY '.DB::order($this->_pk, $sort) : '').DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
	
	
	public function count_common_member_verify() {
	    $count = (int) DB::result_first("SELECT count(*) FROM ".DB::table('common_member_verify'));
	    return $count;
	}
	
	public function range_common_member_verify($start = 0, $limit = 0, $sort = '') {
	    if($sort) {
	        $this->checkpk();
	    }
	    return DB::fetch_all('SELECT * FROM '.DB::table('common_member_verify').($sort ? ' ORDER BY '.DB::order($this->_pk, $sort) : '').DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
}

?>