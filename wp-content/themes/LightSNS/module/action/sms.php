<?php

//签名生成
function jinsom_alisms_request($accessKeyId, $accessKeySecret, $domain, $params, $security=false, $method='POST') {
$apiParams = array_merge(array (
"SignatureMethod" => "HMAC-SHA1",
"SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
"SignatureVersion" => "1.0",
"AccessKeyId" => $accessKeyId,
"Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
"Format" => "JSON",
), $params);
ksort($apiParams);

$sortedQueryStringTmp = "";
foreach ($apiParams as $key => $value) {
$sortedQueryStringTmp .= "&" . jinsom_alisms_encode($key) . "=" . jinsom_alisms_encode($value);
}

$stringToSign = "${method}&%2F&" . jinsom_alisms_encode(substr($sortedQueryStringTmp, 1));

$sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));

$signature = jinsom_alisms_encode($sign);

$url = ($security ? 'https' : 'http')."://{$domain}/";

try {
$content = jinsom_alisms_fetchContent($url,$method,"Signature={$signature}{$sortedQueryStringTmp}");
return json_decode($content);
} catch( \Exception $e) {
return false;
}
}


function jinsom_alisms_encode($str){
$res = urlencode($str);
$res = preg_replace("/\+/", "%20", $res);
$res = preg_replace("/\*/", "%2A", $res);
$res = preg_replace("/%7E/", "~", $res);
return $res;
}

function jinsom_alisms_fetchContent($url,$method,$body){
$ch = curl_init();

if($method == 'POST') {
curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
} else {
$url .= '?'.$body;
}

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
"x-sdk-client" => "php/2.0.0"
));

if(substr($url, 0,5) == 'https') {
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
}

$rtn = curl_exec($ch);

if($rtn === false) {
// 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
// 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
}
curl_close($ch);

return $rtn;
}

//阿里发送短信
function jinsom_alisms($phone){
$code=rand(100000,999999);
$params = array ();
$security = true;
$accessKeyId = jinsom_get_option('jinsom_upload_aliyun_oss_id');
$accessKeySecret = jinsom_get_option('jinsom_upload_aliyun_oss_key');
$params["PhoneNumbers"] = $phone;
$params["SignName"] = jinsom_get_option('jinsom_alidayu_sm_name');
$params["TemplateCode"] = jinsom_get_option('jinsom_alidayu_sm_id');
$params['TemplateParam'] = Array ("code" => $code);
if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
$params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
}
$o=array_merge($params, array(
"RegionId" => "cn-hangzhou",
"Action" => "SendSms",
"Version" => "2017-05-25",
));
$content = jinsom_alisms_request($accessKeyId,$accessKeySecret,"dysmsapi.aliyuncs.com",$o,$security);

if($content->Code=='OK'){
global $wpdb;
$ip = $_SERVER['REMOTE_ADDR'];
$Template=get_term_meta( 1 ,'v' ,true );
if(!$Template){$code=rand(100000,999999);}
$table_name = $wpdb->prefix . 'jin_code';
$time=current_time('mysql');
$wpdb->query( " DELETE FROM $table_name WHERE code_main='$phone';");
$wpdb->query( "INSERT INTO $table_name (code_main,code_number,code_ip,code_type,code_time) VALUES ('$phone','$code','$ip','phone','$time')"); 
}

return $content;
}




//腾讯发短信
function jinsom_tentsms($phone){
$appid = jinsom_get_option('jinsom_tentsms_appid');  //自己的短信appid
$appkey = jinsom_get_option('jinsom_tentsms_appkey'); //自己的短信appkey
$sign=jinsom_get_option('jinsom_tentsms_sign');
$random = rand(100000, 999999);//生成随机数
$params = array($random);
$templId = jinsom_get_option('jinsom_tentsms_templid'); //自己短信模板id
$curTime = time();
$wholeUrl = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms". "?sdkappid=" . $appid . "&random=" . $random;
$nationCode='86';

// 按照协议组织 post 包体
$data = new \stdClass();//创建一个没有成员方法和属性的空对象
$tel = new \stdClass();
$tel->nationcode = "".$nationCode;
$tel->mobile = "".$phone;
$data->tel = $tel;
$data->sig=hash("sha256", "appkey=".$appkey."&random=".$random."&time=".$curTime."&mobile=".$phone);// 生成签名
$data->tpl_id = $templId;
$data->params = $params;
$data->sign = $sign;
$data->time = $curTime;
$data->extend = '';
$data->ext = '';

$content = json_decode(jinsom_tentsms_sendCurlPost($wholeUrl, $data));

if($content->errmsg=='OK'){
global $wpdb;
$ip = $_SERVER['REMOTE_ADDR'];
$Template=get_term_meta( 1 ,'v' ,true );
if(!$Template){$code=rand(100000,999999);}
$table_name = $wpdb->prefix . 'jin_code';
$time=current_time('mysql');
$wpdb->query( " DELETE FROM $table_name WHERE code_main='$phone';");
$wpdb->query( "INSERT INTO $table_name (code_main,code_number,code_ip,code_type,code_time) VALUES ('$phone','$random','$ip','phone','$time')"); 
}

return $content;


}

function jinsom_tentsms_sendCurlPost($url, $dataObj){
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$ret = curl_exec($curl);
if (false == $ret) {
// curl_exec failed
$result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
} else {
$rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if (200 != $rsp) {
$result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp
    . " " . curl_error($curl) ."\"}";
} else {
$result = $ret;
}
}
curl_close($curl);

return $result;
}

