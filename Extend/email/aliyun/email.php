<?php    
// require('wp-load.php');
$accessKeyId=jinsom_get_option('jinsom_upload_aliyun_oss_id');
$accessKeySecret=jinsom_get_option('jinsom_upload_aliyun_oss_key');
include_once 'aliyun-php-sdk-core/Config.php';
use Dm\Request\V20151123 as Dm;            
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$accessKeyId,$accessKeySecret);        
$client = new DefaultAcsClient($iClientProfile);    
$request = new Dm\SingleSendMailRequest();     
