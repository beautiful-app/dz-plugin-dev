<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class Checkip
{
    function ipaccessok() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $g_checkipcountry = $_config['g_checkipcountry'];
        $g_checkipregion = $_config['g_checkipregion'];
        $g_checkipcity = $_config['g_checkipcity'];
        $webbianma = $_G['charset'];
        
        $ipInfo = array();
        if (!empty($g_checkipcountry) || !empty($g_checkipregion) || !empty($g_checkipcity)) {
            $ipInfo = $this->get_ip_data();
            $ipInfo = $this->getbianma($ipInfo,$webbianma);
        }else{
            return true;
        }
        
        if(!empty($g_checkipcountry) && !empty($ipInfo)){
            $country_arr = array_filter(array_map("trim", explode("|",$g_checkipcountry)));
            if (!empty($country_arr) && isset($ipInfo['country'])) {
                if($this->CheckCountry($country_arr, $ipInfo['country'])){
                    return false;
                }
            }
        }
        if(!empty($g_checkipregion) && !empty($ipInfo)){
            $region_arr = array_filter(array_map("trim", explode("|",$g_checkipregion)));
            if (!empty($region_arr) && isset($ipInfo['region'])) {
                if($this->CheckRegion($region_arr, $ipInfo['region'])){
                    return false;
                }
            }
        }
        if(!empty($g_checkipcity) && !empty($ipInfo)){
            $city_arr = array_filter(array_map("trim", explode("|",$g_checkipcity)));
            if (!empty($city_arr) && isset($ipInfo['city'])) {
                if($this->CheckCity($city_arr, $ipInfo['city'])){
                    return false;
                }
            }
        }
        return true;
    }//f ro m w w w.m oqu8. co m
    
    private function CheckCountry($country_arr,$user_country){
        foreach($country_arr as $value){
            if(preg_match("/^{$value}*/",$user_country)){
                return true;
            }
        }
        return false;
    }
    private function CheckRegion($region_arr,$user_region){
        foreach($region_arr as $value){
            if(preg_match("/^{$value}*/", $user_region)){
                return true;
            }
        }
        return false;
    }
    
    private function CheckCity($city_arr,$user_city){
        foreach($city_arr as $value){
            if(preg_match("/^{$value}*/", $user_city)){
                return true;
            }
        }
        return false;
    }

    private function get_ip_data(){
        $clentip = $this->get_client_ip();
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://ip.taobao.com/service/getIpInfo.php?ip=".$clentip);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $ipdata = json_decode($data);
        if($ipdata->code){
            return false;
        }
        $data = (array) $ipdata->data;
        return $data;
    }

    //get ip
    public function get_client_ip()
    {
        global $_G;
        if (isset($_G['clientip']) and !empty($_G['clientip']))
        {
            return $_G['clientip'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP']) and !empty($_SERVER['HTTP_CLIENT_IP']))
        {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            return strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
        }
        if (isset($_SERVER['HTTP_PROXY_USER']) and !empty($_SERVER['HTTP_PROXY_USER']))
        {
            return $_SERVER['HTTP_PROXY_USER'];
        }
        if (isset($_SERVER['REMOTE_ADDR']) and !empty($_SERVER['REMOTE_ADDR']))
        {
            return $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            return "0.0.0.0";
        }
    }
    private function getbianma($data, $webbianma = "gbk")
    {
        if ($webbianma == "gbk") {
            foreach ($data as $datakey => $datav){
                $data[$datakey] = diconv($datav, 'UTF-8', 'GBK');
            }
        }
        return $data;
    }
}
?>