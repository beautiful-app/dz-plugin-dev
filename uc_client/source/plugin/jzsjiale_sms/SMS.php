<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}
define('ENABLE_HTTP_PROXY', FALSE);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');
//include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Config.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Exception/ClientException.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Exception/ServerException.php';

include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Http/HttpResponse.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Http/HttpHelper.php';

include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Auth/Credential.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Auth/ISigner.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Auth/ShaHmac1Signer.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Auth/ShaHmac256Signer.php';

include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Profile/IClientProfile.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Profile/DefaultProfile.php';



include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/AcsRequest.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/AcsResponse.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/RoaAcsRequest.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/RpcAcsRequest.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/IAcsClient.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/DefaultAcsClient.php';

include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Regions/EndpointProvider.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Regions/ProductDomain.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Regions/Endpoint.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/aliyun-php-sdk-core/Regions/EndpointConfig.php';

require_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/Dysmsapi/Request/V20170525/SendSmsRequest.php';
require_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/sdk/Dysmsapi/Request/V20170525/QuerySendDetailsRequest.php';

class SMS
{

    public $accesskeyid;
    
    public $accesskeysecret;
    
    public function __construct($accesskeyid = "",$accesskeysecret = ""){
        $this->accesskeyid = $accesskeyid;
        $this->accesskeysecret = $accesskeysecret ;
    }
    
    
    public function smssend($phoneNumbers = "",$signName = "",$templateCode = "",$templateParam = "")
    {
        if(empty($phoneNumbers) || empty($signName) || empty($templateCode) || empty($templateParam)){
            return;
        }

        date_default_timezone_set('Asia/Shanghai');
    
        try {
            $accessKeyId = $this->accesskeyid;
            $accessKeySecret = $this->accesskeysecret;
            
            $product = "Dysmsapi";
            
            $domain = "dysmsapi.aliyuncs.com";
             
            $region = "cn-hangzhou";
            
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
            $acsClient= new DefaultAcsClient($profile);
            
            $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
            
            $request->setPhoneNumbers($phoneNumbers);
            
            $request->setSignName($signName);
            
            $request->setTemplateCode($templateCode);
            
            $request->setTemplateParam($templateParam);
            
            //$request->setOutId("1234");
            
            $acsResponse = $acsClient->getAcsResponse($request);
            //var_dump($acsResponse);
            
            return json_encode($acsResponse);
        }catch (Exception $e) {   
          return  $e->getMessage(); 
        } 
        
    }
}